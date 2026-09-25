<?php

/**
 * RecommendationService
 *
 * Builds the product recommendations shown on the EcommerceDemo storefront.
 *
 * The `products` table has no category, brand or tag column, so similarity is
 * derived from the words that already exist in the product name. A word
 * several product names share ("jean", "shirt", "sunglasse") names a product
 * family and scores highest, while a word only one product uses ("midnight")
 * is a descriptor and scores lowest. Price proximity is only a tie-breaker.
 *
 * The description is deliberately not matched on. Every description in this
 * catalogue is marketing copy built from the same handful of words ("style",
 * "casual", "everyday"), so matching them links a pair of sunglasses to a pair
 * of jeans and drowns out the real signal.
 *
 * Existing tables are reused rather than duplicated:
 *   products       candidates, availability comes from quantity > 0
 *   order_items    purchase history and the popularity ranking
 *   cart           current cart behaviour
 *   product_views  view history, the only added table (database/product_views.sql)
 *
 * The catalogue and the popularity ranking are cached on disk for CACHE_TTL
 * seconds so the repeated work is not redone on every page view.
 */
class RecommendationService
{
    /* ---- scoring card: every point below is additive ---- */

    const SCORE_PURCHASED_WORD = 4;   // name word shared with a product this user bought
    const SCORE_VIEWED_WORD    = 3;   // name word shared with a product this user viewed
    const SCORE_SHARED_FAMILY  = 3;   // family word in both names, acts as the category match
    const SCORE_SHARED_TAG     = 1;   // one off word in both names, acts as a tag match
    const SCORE_CART_WORD      = 2;   // name word shared with a product in this user's cart
    const SCORE_PRICE_MATCH    = 1;   // price within 20% of the product being viewed
    const SCORE_POPULAR        = 1;   // sold in the past, scaled against the top seller

    /* caps so no single signal can run away with the score */
    const MAX_PURCHASED_POINTS = 8;
    const MAX_VIEWED_POINTS    = 6;
    const MAX_CART_POINTS      = 4;
    const MAX_FAMILY_POINTS    = 6;

    /* a name word used by at least this many products describes a product
       family ("jean", "shirt"); anything rarer is just a descriptor */
    const FAMILY_WORD_MIN_PRODUCTS = 3;

    const PRICE_MATCH_RATIO   = 0.20;
    const CACHE_TTL           = 300;
    const VIEW_DEDUPE_MINUTES = 30;

    private $conn;
    private $user_id;
    private static $view_table_checked = false;

    public function __construct($conn, $user_id = 0)
    {
        $this->conn = $conn;
        $this->user_id = max(0, (int) $user_id);
    }

    /**
     * Build the recommendation list.
     *
     *   $options:
     *   context_product_id  product being viewed, enables similarity scoring
     *   exclude_ids         product ids to leave out (current product, cart)
     *   limit               how many products to return, 1 to 8
     *
     * Always returns up to $limit products. Anything the scoring cannot tell
     * apart ends up at the bottom with a neutral label, which keeps the strip
     * from rendering half empty on a quiet catalogue.
     */
    public function recommend($options = array())
    {
        $context_product_id = max(0, (int) ($options['context_product_id'] ?? 0));
        $limit = max(1, min(8, (int) ($options['limit'] ?? 4)));

        $exclude_ids = array();
        if (isset($options['exclude_ids'])) {
            foreach ($options['exclude_ids'] as $excluded_id) {
                $excluded_id = (int) $excluded_id;
                if ($excluded_id > 0) {
                    $exclude_ids[$excluded_id] = true;
                }
            }
        }

        // The product being viewed is never recommended back to the viewer,
        // whether or not the caller remembered to exclude it.
        if ($context_product_id > 0) {
            $exclude_ids[$context_product_id] = true;
        }

        $catalogue = $this->catalogue();
        $products = $catalogue['products'];
        if ($products === array()) {
            return array();
        }

        $context = isset($products[$context_product_id]) ? $products[$context_product_id] : null;
        $signals = $this->load_user_signals($products);
        $purchased_ids = $signals['purchased_ids'];

        $scored = array();
        foreach ($products as $product_id => $product) {
            if (isset($exclude_ids[$product_id])) {
                continue;
            }
            // Never re-suggest something this user already bought.
            if ($this->user_id > 0 && in_array($product_id, $purchased_ids, true)) {
                continue;
            }

            $result = $this->score($product, $context, $signals, $catalogue);
            $result['id'] = $product_id;
            $result['name'] = $product['name'];
            $result['description'] = $product['description'];
            $result['price'] = $product['price'];
            $result['quantity'] = $product['quantity'];
            $result['image'] = $product['image'];
            $scored[] = $result;
        }

        usort($scored, array($this, 'compare'));

        return array_slice($scored, 0, $limit);
    }

    /**
     * Store a product view for an authenticated user.
     * Repeat views of the same product inside VIEW_DEDUPE_MINUTES are ignored.
     */
    public function record_view($product_id)
    {
        if ($this->user_id <= 0) {
            return false;
        }

        $product_id = (int) $product_id;
        if ($product_id <= 0 || !$this->view_table_exists()) {
            return false;
        }

        $check_query = "SELECT id FROM product_views
                        WHERE user_id = $this->user_id
                          AND product_id = $product_id
                          AND created_at >= DATE_SUB(NOW(), INTERVAL " . self::VIEW_DEDUPE_MINUTES . " MINUTE)
                        LIMIT 1";
        $check_result = $this->conn->query($check_query);
        if ($check_result instanceof mysqli_result) {
            $already_viewed = $check_result->num_rows > 0;
            $check_result->free();
            if ($already_viewed) {
                return true;
            }
        }

        $insert_query = "INSERT INTO product_views (user_id, product_id) VALUES ($this->user_id, $product_id)";
        $inserted = $this->conn->query($insert_query);

        return $inserted === true;
    }

    /**
     * Product ids currently sitting in this user's cart.
     */
    public function cart_product_ids()
    {
        if ($this->user_id <= 0) {
            return array();
        }

        return $this->ids_from_query(
            "SELECT product_id FROM cart WHERE user_id = $this->user_id"
        );
    }

    /* ================= scoring ================= */

    private function score($product, $context, $signals, $catalogue)
    {
        $score = 0.0;
        $buckets = array(
            'similar'    => 0.0,
            'purchased'  => 0.0,
            'viewed'     => 0.0,
            'cart'       => 0.0,
            'popular'    => 0.0,
        );

        // 1. similarity to the product currently being viewed
        if ($context !== null) {
            $family_words = 0;
            $tag_words = 0;
            foreach (array_intersect($product['name_words'], $context['name_words']) as $word) {
                if (($catalogue['name_word_counts'][$word] ?? 0) >= self::FAMILY_WORD_MIN_PRODUCTS) {
                    $family_words++;
                } else {
                    $tag_words++;
                }
            }

            $buckets['similar'] += min(self::MAX_FAMILY_POINTS, $family_words * self::SCORE_SHARED_FAMILY);
            $buckets['similar'] += $tag_words * self::SCORE_SHARED_TAG;

            if ($context['price'] > 0
                && abs($product['price'] - $context['price']) / $context['price'] <= self::PRICE_MATCH_RATIO) {
                $buckets['similar'] += self::SCORE_PRICE_MATCH;
            }
        }

        // 2. this user's own behaviour, never anybody else's
        $purchased_words = 0;
        $viewed_words = 0;
        $cart_words = 0;
        foreach ($product['name_words'] as $word) {
            if (!empty($signals['purchased'][$word])) {
                $purchased_words++;
            }
            if (!empty($signals['viewed'][$word])) {
                $viewed_words++;
            }
            if (!empty($signals['cart'][$word])) {
                $cart_words++;
            }
        }

        $buckets['purchased'] = min(self::MAX_PURCHASED_POINTS, $purchased_words * self::SCORE_PURCHASED_WORD);
        $buckets['viewed'] = min(self::MAX_VIEWED_POINTS, $viewed_words * self::SCORE_VIEWED_WORD);
        $buckets['cart'] = min(self::MAX_CART_POINTS, $cart_words * self::SCORE_CART_WORD);

        // 3. popularity, scaled so the best seller contributes exactly one point
        $popularity_score = (float) ($catalogue['popularity'][$product['id']] ?? 0);
        $buckets['popular'] = self::SCORE_POPULAR * $popularity_score;

        foreach ($buckets as $points) {
            $score += $points;
        }

        return array(
            'score' => round($score, 4),
            'points' => $buckets,
            'reason_code' => $this->reason_code($buckets, $context),
            'reason' => $this->reason_text($buckets, $context),
        );
    }

    private function reason_code($buckets, $context)
    {
        $order = array('similar', 'purchased', 'viewed', 'cart', 'popular');
        $best = 'similar';
        foreach ($order as $bucket) {
            if ($buckets[$bucket] > $buckets[$best]) {
                $best = $bucket;
            }
        }

        if ($buckets[$best] <= 0) {
            return 'discovery';
        }
        if ($best === 'similar' && $context === null) {
            return 'discovery';
        }

        return $best;
    }

    private function reason_text($buckets, $context)
    {
        switch ($this->reason_code($buckets, $context)) {
            case 'similar':
                return 'Similar to ' . $context['name'];
            case 'purchased':
                return 'Based on your previous orders';
            case 'viewed':
                return 'More like what you viewed';
            case 'cart':
                return 'Matches something in your cart';
            case 'popular':
                return 'Popular right now';
        }

        return 'More choices for you';
    }

    /**
     * Highest score first. On a tie the better supported match wins: stronger
     * similarity, then more popular, then newer.
     */
    public function compare($left, $right)
    {
        if ($left['score'] !== $right['score']) {
            return $right['score'] <=> $left['score'];
        }

        $buckets = array('similar', 'purchased', 'viewed', 'cart', 'popular');
        foreach ($buckets as $bucket) {
            if ($left['points'][$bucket] !== $right['points'][$bucket]) {
                return $right['points'][$bucket] <=> $left['points'][$bucket];
            }
        }

        return $right['id'] <=> $left['id'];
    }

    /* ================= data loading ================= */

    /**
     * Available products plus their derived words and popularity.
     * Cached on disk because it is identical for every visitor.
     */
    private function catalogue()
    {
        $cached = $this->cache_read('catalogue');
        if ($cached !== null) {
            return $cached;
        }

        $products = array();
        $result = $this->conn->query(
            "SELECT id, name, description, price, quantity, image
             FROM products
             WHERE quantity > 0
             ORDER BY id DESC"
        );
        if ($result instanceof mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $product_id = (int) $row['id'];
                $products[$product_id] = array(
                    'id' => $product_id,
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => (float) $row['price'],
                    'quantity' => (int) $row['quantity'],
                    'image' => $row['image'],
                    'name_words' => $this->words($row['name']),
                );
            }
            $result->free();
        }

        $catalogue = array(
            'products' => $products,
            'popularity' => $this->load_popularity(),
            'name_word_counts' => $this->count_name_words($products),
        );

        $this->cache_write('catalogue', $catalogue);

        return $catalogue;
    }

    /**
     * How many product names each word appears in. A word used by several
     * products names a product family, a word used once is only a descriptor.
     */
    private function count_name_words($products)
    {
        $counts = array();
        foreach ($products as $product) {
            foreach ($product['name_words'] as $word) {
                if (isset($counts[$word])) {
                    $counts[$word]++;
                } else {
                    $counts[$word] = 1;
                }
            }
        }

        return $counts;
    }

    /**
     * How often each product has been bought, scaled so the top seller is 1.0.
     * Falls back to view counts when there is no order data at all.
     */
    private function load_popularity()
    {
        $counts = array();
        $result = $this->conn->query(
            "SELECT product_id, SUM(quantity) AS sold
             FROM order_items
             GROUP BY product_id"
        );
        if ($result instanceof mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $counts[(int) $row['product_id']] = (float) $row['sold'];
            }
            $result->free();
        }

        if ($counts === array() && $this->view_table_exists()) {
            $result = $this->conn->query(
                "SELECT product_id, COUNT(*) AS views
                 FROM product_views
                 GROUP BY product_id"
            );
            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $counts[(int) $row['product_id']] = (float) $row['views'];
                }
                $result->free();
            }
        }

        return $this->scale($counts);
    }

    /**
     * Divide by the largest value so the best seller scores 1.0.
     */
    private function scale($counts)
    {
        $top = 0.0;
        foreach ($counts as $count) {
            $top = max($top, $count);
        }
        if ($top <= 0) {
            return array();
        }

        $scaled = array();
        foreach ($counts as $product_id => $count) {
            $scaled[(int) $product_id] = $count / $top;
        }

        return $scaled;
    }

    /**
     * The signed in user's own words, grouped by how strong each signal is.
     * Only $_SESSION['user_id'] is ever read here.
     */
    private function load_user_signals($products)
    {
        $signals = array(
            'purchased' => array(),
            'viewed' => array(),
            'cart' => array(),
            'purchased_ids' => array(),
        );

        if ($this->user_id <= 0) {
            return $signals;
        }

        $queries = array(
            'purchased' => "SELECT oi.product_id
                            FROM order_items oi
                            INNER JOIN orders o ON o.id = oi.order_id
                            WHERE o.user_id = $this->user_id",
            'viewed' => "SELECT product_id
                         FROM product_views
                         WHERE user_id = $this->user_id
                         ORDER BY created_at DESC
                         LIMIT 50",
            'cart' => "SELECT product_id
                       FROM cart
                       WHERE user_id = $this->user_id",
        );

        foreach ($queries as $signal => $query) {
            $product_ids = $this->ids_from_query($query);

            if ($signal === 'purchased') {
                $signals['purchased_ids'] = $product_ids;
            }

            foreach (array_unique($product_ids) as $product_id) {
                if (!isset($products[$product_id])) {
                    continue;
                }
                foreach ($products[$product_id]['name_words'] as $word) {
                    if (isset($signals[$signal][$word])) {
                        $signals[$signal][$word]++;
                    } else {
                        $signals[$signal][$word] = 1;
                    }
                }
            }
        }

        return $signals;
    }

    private function ids_from_query($query)
    {
        $ids = array();
        $result = $this->conn->query($query);
        if ($result instanceof mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $ids[] = (int) $row['product_id'];
            }
            $result->free();
        }

        return $ids;
    }

    /**
     * Reduce a name or description to comparable words.
     * Short filler words are dropped and simple plurals are folded together so
     * "Jeans" and "Jean" match.
     */
    private function words($text)
    {
        $ignored = array(
            'all', 'and', 'are', 'best', 'for', 'from', 'its', 'new', 'our', 'premium',
            'quality', 'that', 'the', 'this', 'with', 'you', 'your', 'use', 'used',
        );

        $parts = preg_split('/[^a-z0-9]+/', strtolower((string) $text), -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($parts)) {
            return array();
        }

        $words = array();
        foreach ($parts as $part) {
            if (strlen($part) < 3 || in_array($part, $ignored, true)) {
                continue;
            }
            if (substr($part, -1) === 's' && substr($part, -2) !== 'ss') {
                $part = substr($part, 0, -1);
            }
            $words[$part] = true;
        }

        return array_keys($words);
    }

    private function view_table_exists()
    {
        if (self::$view_table_checked) {
            return true;
        }

        $result = $this->conn->query("SHOW TABLES LIKE 'product_views'");
        if ($result instanceof mysqli_result) {
            $exists = $result->num_rows > 0;
            $result->free();
            if ($exists) {
                self::$view_table_checked = true;
            }
            return $exists;
        }

        return false;
    }

    /**
     * Drop the cached catalogue. Call after products or orders change.
     */
    public function clear_cache()
    {
        @unlink($this->cache_path('catalogue'));
    }

    /* ================= cache ================= */

    private function cache_path($name)
    {
        return rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'ecommercedemo_recommendations_' . $name . '.cache';
    }

    private function cache_read($name)
    {
        $path = $this->cache_path($name);
        if (!is_file($path) || (time() - filemtime($path)) > self::CACHE_TTL) {
            return null;
        }

        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return null;
        }

        $data = unserialize($raw);

        return is_array($data) ? $data : null;
    }

    private function cache_write($name, $data)
    {
        @file_put_contents($this->cache_path($name), serialize($data), LOCK_EX);
    }
}
