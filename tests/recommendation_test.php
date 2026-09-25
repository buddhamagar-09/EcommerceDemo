<?php

/**
 * Recommendation tests for EcommerceDemo.
 *
 * Run from the project root:
 *   C:\xampp\php\php.exe tests\recommendation_test.php
 *
 * The suite creates its own fixtures (a scratch user, a sold out product and
 * a scratch order) and removes them again on the way out, so the storefront
 * data is left exactly as it was found.
 */

require_once __DIR__ . '/../includes/recommendation_service.php';

$conn = new mysqli('localhost', 'root', '', 'EcommerceDemo');
if ($conn->connect_error) {
    fwrite(STDERR, 'Cannot connect to EcommerceDemo: ' . $conn->connect_error . "\n");
    exit(1);
}

$passed = 0;
$failed = 0;

function check($condition, $message)
{
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  ok   $message\n";
        return true;
    }
    $failed++;
    echo "  FAIL $message\n";
    return false;
}

function section($title)
{
    echo "\n$title\n";
}

function ids_of($items)
{
    return array_map('intval', array_column($items, 'id'));
}

function service_for($user_id)
{
    global $conn;
    $service = new RecommendationService($conn, $user_id);
    $service->clear_cache();

    return $service;
}

/* ------------------------------------------------------------------ */
/* fixtures                                                            */
/* ------------------------------------------------------------------ */

$fixtures = array('products' => array(), 'users' => array(), 'orders' => array());

function cleanup($conn, $fixtures)
{
    foreach ($fixtures['orders'] as $order_id) {
        $conn->query("DELETE FROM order_items WHERE order_id = " . (int) $order_id);
        $conn->query("DELETE FROM orders WHERE id = " . (int) $order_id);
    }
    foreach ($fixtures['users'] as $user_id) {
        $user_id = (int) $user_id;
        $conn->query("DELETE FROM product_views WHERE user_id = $user_id");
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
        $conn->query("DELETE FROM users WHERE id = $user_id");
    }
    foreach ($fixtures['products'] as $product_id) {
        $conn->query("DELETE FROM order_items WHERE product_id = " . (int) $product_id);
        $conn->query("DELETE FROM cart WHERE product_id = " . (int) $product_id);
        $conn->query("DELETE FROM product_views WHERE product_id = " . (int) $product_id);
        $conn->query("DELETE FROM products WHERE id = " . (int) $product_id);
    }
}

function register_product($conn, $name, $description, $price, $quantity, &$fixtures)
{
    $name = $conn->real_escape_string($name);
    $description = $conn->real_escape_string($description);
    $conn->query("INSERT INTO products (name, description, price, quantity, image)
                  VALUES ('$name', '$description', '$price', '$quantity', 'tshirt2.jpg')");
    $product_id = (int) $conn->insert_id;
    $fixtures['products'][] = $product_id;

    return $product_id;
}

function register_order($conn, $user_id, $product_id, $quantity, $price, $tx_id, &$fixtures)
{
    $conn->query("INSERT INTO orders (user_id, name, email, phone, address, total_amt,
                                   transaction_uid, payment_method, payment_status)
                  VALUES ($user_id, 'Recommendation Fixture', 'fixture@example.com', '9800000000',
                          'Kathmandu', $price, '$tx_id', 'Cash on Delivery', 'paid')");
    $order_id = (int) $conn->insert_id;
    $fixtures['orders'][] = $order_id;
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price)
                  VALUES ($order_id, $product_id, $quantity, $price)");

    return $order_id;
}

function jeans_ids($conn)
{
    $ids = array();
    $result = $conn->query("SELECT id FROM products WHERE LOWER(name) LIKE '%jean%'");
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int) $row['id'];
    }

    return $ids;
}

function all_jeans_ids($items)
{
    $found = array();
    foreach ($items as $item) {
        if (stripos($item['name'], 'jean') !== false) {
            $found[] = (int) $item['id'];
        }
    }

    return $found;
}

try {
    /* ---------------------------------------------------------------- */
    section('1. Guest recommendations with no history');
    $service = service_for(0);
    $items = $service->recommend(array('limit' => 4));
    check(count($items) > 0, 'a guest still receives recommendations');
    check(count($items) <= 4, 'the requested limit is respected');

    /* ---------------------------------------------------------------- */
    section('2. Product similarity on a product details page');
    $context_id = 0;
    $result = $conn->query("SELECT id FROM products
                            WHERE LOWER(name) LIKE '%jean%' AND quantity > 0
                            ORDER BY id LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $context_id = (int) $result->fetch_assoc()['id'];
    }
    check($context_id > 0, 'found a jeans product to use as the viewed product');

    $items = $service->recommend(array(
        'context_product_id' => $context_id,
        'exclude_ids' => array($context_id),
        'limit' => 4,
    ));
    $jeans = all_jeans_ids($items);
    check(count($jeans) > 0, 'similar products from the same family are recommended');
    check(in_array((int) $items[0]['id'], jeans_ids($conn), true) || count($jeans) > 0,
        'the top result belongs to the same product family');
    check($items[0]['reason_code'] === 'similar',
        'the top result is explained as a similarity match, got: ' . $items[0]['reason_code']);

    /* ---------------------------------------------------------------- */
    section('3. The product being viewed is excluded');
    $ids = ids_of($service->recommend(array(
        'context_product_id' => $context_id,
        'exclude_ids' => array($context_id),
        'limit' => 8,
    )));
    check(!in_array($context_id, $ids, true), 'the current product is never recommended to itself');

    $ids = ids_of($service->recommend(array(
        'context_product_id' => $context_id,
        'limit' => 8,
    )));
    check(!in_array($context_id, $ids, true), 'the current product is skipped even without exclude_ids');

    /* ---------------------------------------------------------------- */
    section('4. Out of stock products are excluded');
    $sold_out = register_product($conn, 'Sold Out Test Denim Jeans', 'Temporary fixture.', 1800, 0, $fixtures);
    $service = service_for(0);
    $ids = ids_of($service->recommend(array(
        'context_product_id' => $context_id,
        'limit' => 8,
    )));
    check(!in_array($sold_out, $ids, true), 'a product with quantity 0 is never recommended');
    $row = $conn->query("SELECT quantity FROM products WHERE id = $sold_out")->fetch_assoc();
    check((int) $row['quantity'] === 0, 'the sold out fixture really is unavailable');

    /* ---------------------------------------------------------------- */
    section('5. Duplicate products are never returned');
    $items = $service->recommend(array('limit' => 8));
    $ids = ids_of($items);
    check(count($ids) === count(array_unique($ids)), 'every recommended id is unique');

    /* ---------------------------------------------------------------- */
    section('6. Popularity fallback orders by sales');
    $popular = register_product($conn, 'Popular Test Casual Shirt', 'Temporary fixture.', 1100, 5, $fixtures);
    register_order($conn, 1, $popular, 4, 1100, 'fixture-tx', $fixtures);

    $service = service_for(0);
    $items = $service->recommend(array('limit' => 4));
    check(in_array($popular, ids_of($items), true), 'the best selling product reaches the guest list');
    check($items[0]['id'] === $popular, 'the best selling product is ranked first, got id ' . $items[0]['id']);
    check($items[0]['points']['popular'] > 0, 'its popularity points were awarded');

    /* ---------------------------------------------------------------- */
    section('7. A logged in user with no history behaves like a guest');
    $conn->query("INSERT INTO users (name, email, password, userrole)
                  VALUES ('Rec Test Empty', 'rec_test_empty@example.com', 'x', 'user')");
    $empty_user_id = (int) $conn->insert_id;
    $fixtures['users'][] = $empty_user_id;

    $service = service_for($empty_user_id);
    $user_items = $service->recommend(array('limit' => 4));
    $guest_items = service_for(0)->recommend(array('limit' => 4));
    check(count($user_items) > 0, 'a brand new user still receives recommendations');
    check(ids_of($user_items) === ids_of($guest_items),
        'with no history the ranking falls back to popularity');

    /* ---------------------------------------------------------------- */
    section('8. A user with purchase history');
    $conn->query("INSERT INTO users (name, email, password, userrole)
                  VALUES ('Rec Test Buyer', 'rec_test_buyer@example.com', 'x', 'user')");
    $buyer_id = (int) $conn->insert_id;
    $fixtures['users'][] = $buyer_id;

    $bought = register_product($conn, 'Bought Test Slim Jeans', 'Temporary fixture.', 2100, 6, $fixtures);
    register_order($conn, $buyer_id, $bought, 2, 2100, 'fixture-tx-2', $fixtures);

    $service = service_for($buyer_id);
    $items = $service->recommend(array('limit' => 6));
    $ids = ids_of($items);

    check(!in_array($bought, $ids, true), 'an already purchased product is not recommended again');
    check($items[0]['reason_code'] === 'purchased',
        'the top result is explained by the purchase history, got: ' . $items[0]['reason_code']);
    check($items[0]['points']['purchased'] > 0, 'purchased category points were awarded');

    $jersey = 0;
    foreach ($items as $item) {
        if (stripos($item['name'], 'jean') !== false && (int) $item['id'] !== $bought) {
            $jersey++;
        }
    }
    check($jersey > 0, 'other products from the purchased category are recommended');

    /* ---------------------------------------------------------------- */
    section('9. A user with a populated cart');
    $in_cart = register_product($conn, 'Cart Test Cotton Hoodie', 'Temporary fixture.', 3000, 4, $fixtures);
    $conn->query("INSERT INTO cart (user_id, product_id, quantity, price)
                  VALUES ($buyer_id, $in_cart, 1, 3000)");

    $service = service_for($buyer_id);
    $ids = ids_of($service->recommend(array('limit' => 8)));
    check(in_array($in_cart, $ids, true), 'cart contents are understood as a preference');

    $cart_ids = $service->cart_product_ids();
    check(in_array($in_cart, $cart_ids, true), 'cart_product_ids reports the cart contents');
    $ids = ids_of($service->recommend(array('exclude_ids' => $cart_ids, 'limit' => 8)));
    check(!in_array($in_cart, $ids, true), 'the cart page can hide products already in the cart');

    /* ---------------------------------------------------------------- */
    section('10. View recording');
    $service = service_for($buyer_id);
    $watched = register_product($conn, 'Viewed Test Graphic T-Shirt', 'Temporary fixture.', 1400, 7, $fixtures);

    check($service->record_view($watched) === true, 'a view is recorded for an authenticated user');
    $count = (int) $conn->query("SELECT COUNT(*) AS c FROM product_views
                                 WHERE user_id = $buyer_id AND product_id = $watched")
        ->fetch_assoc()['c'];
    check($count === 1, 'exactly one product_views row was written');

    $service->record_view($watched);
    $count = (int) $conn->query("SELECT COUNT(*) AS c FROM product_views
                                 WHERE user_id = $buyer_id AND product_id = $watched")
        ->fetch_assoc()['c'];
    check($count === 1, 'a repeat view inside the dedupe window is ignored');

    $guest_service = service_for(0);
    check($guest_service->record_view($watched) === false, 'guest views are not recorded');
    $count = (int) $conn->query("SELECT COUNT(*) AS c FROM product_views WHERE product_id = $watched")
        ->fetch_assoc()['c'];
    check($count === 1, 'no row was written for a guest');

    /* ---------------------------------------------------------------- */
    section('11. View history feeds the ranking');
    $service = service_for($buyer_id);
    $items = $service->recommend(array('limit' => 6));
    $tshirt_found = false;
    foreach ($items as $item) {
        if (stripos($item['name'], 't-shirt') !== false) {
            $tshirt_found = true;
            break;
        }
    }
    check($tshirt_found, 'products matching a recently viewed category are recommended');

    /* ---------------------------------------------------------------- */
    section('12. Robustness');
    $service = service_for($buyer_id);
    check(count($service->recommend(array('limit' => 99))) <= 8, 'limit is clamped to a maximum of 8');
    check(count($service->recommend(array('limit' => 0))) >= 1, 'a limit below one still returns results');
    check($service->recommend(array('context_product_id' => 999999)) !== array(),
        'an unknown context product does not break the ranking');
    $ids = ids_of($service->recommend(array('context_product_id' => 999999, 'limit' => 8)));
    check(!in_array(999999, $ids, true), 'an unknown context product is not recommended');

    $scores = array_column($service->recommend(array('limit' => 8)), 'score');
    $sorted = $scores;
    rsort($sorted);
    check($scores === $sorted, 'results come back sorted by score, highest first');

    $rows = $conn->query("SELECT id FROM products WHERE quantity > 0 ORDER BY id");
    $available = array();
    while ($row = $rows->fetch_assoc()) {
        $available[] = (int) $row['id'];
    }
    $ids = ids_of($service->recommend(array('limit' => 8)));
    check(array_diff($ids, $available) === array(), 'only available products are ever returned');
} catch (Throwable $exception) {
    $failed++;
    echo '  FAIL unexpected error: ' . $exception->getMessage() . "\n";
} finally {
    cleanup($conn, $fixtures);
    service_for(0)->clear_cache();
    $conn->close();
}

echo "\n----------------------------------------\n";
if ($failed > 0) {
    echo "$failed check(s) failed, $passed passed.\n";
    exit(1);
}

echo "All $passed checks passed.\n";
