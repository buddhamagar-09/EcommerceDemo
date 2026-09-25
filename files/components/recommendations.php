<?php

/**
 * Renders a recommendation strip.
 *
 * Expects these variables from the including page:
 *   $recommendations      array of products from RecommendationService
 *   $recommendation_mode  context | personalized | popular
 *   $recommendation_title optional heading override
 *
 * The markup mirrors the existing .card markup used by index.php and
 * products.php so the recommendations match the rest of the storefront.
 */

$recommendations = isset($recommendations) && is_array($recommendations) ? $recommendations : array();
$recommendation_mode = isset($recommendation_mode) ? (string) $recommendation_mode : 'popular';

if ($recommendations === array()) {
    return;
}

if (isset($recommendation_title)) {
    $heading = (string) $recommendation_title;
} elseif ($recommendation_mode === 'context') {
    $heading = 'You May Also Like';
} elseif ($recommendation_mode === 'personalized') {
    $heading = 'Recommended For You';
} else {
    $heading = 'Popular Products';
}
?>
<section class="recommendations">
    <h2 class="recommendations-heading"><?php echo htmlspecialchars($heading, ENT_QUOTES, 'UTF-8'); ?></h2>

    <div class="recommendation-grid">
        <?php foreach ($recommendations as $recommendation):
            $rec_id = (int) $recommendation['id'];
            $rec_name = (string) $recommendation['name'];
            $rec_price = (float) $recommendation['price'];
            $rec_quantity = (int) $recommendation['quantity'];
            $rec_reason = (string) $recommendation['reason'];
        ?>
            <div class="card">
                <a href="product_details.php?id=<?php echo $rec_id; ?>&amp;source=recommendation">
                    <img src="../photos/<?php echo htmlspecialchars((string) $recommendation['image'], ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?php echo htmlspecialchars($rec_name, ENT_QUOTES, 'UTF-8'); ?>">
                </a>
                <h3>
                    <a href="product_details.php?id=<?php echo $rec_id; ?>&amp;source=recommendation">
                        <?php echo htmlspecialchars($rec_name, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h3>
                <p class="recommendation-reason"><?php echo htmlspecialchars($rec_reason, ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="price">Rs.<?php echo htmlspecialchars((string) $rec_price, ENT_QUOTES, 'UTF-8'); ?></div>

                <div class="card-actions">
                    <form action="cart.php" method="post">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $rec_id; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="card-btn add-cart-btn" <?php echo $rec_quantity <= 0 ? 'disabled' : ''; ?>>Add To Cart</button>
                    </form>
                    <a href="product_details.php?id=<?php echo $rec_id; ?>&amp;source=recommendation">
                        <button type="button" class="card-btn details-btn">View Details</button>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
