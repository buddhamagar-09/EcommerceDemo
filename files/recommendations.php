<?php

/**
 * JSON endpoint for the recommendation strip.
 *
 * GET parameters:
 *   limit              1 to 8, defaults to 4
 *   context_product_id product to find similar products for
 *   exclude_id         product id to leave out, repeatable
 *
 * Only the signed in visitor's own behaviour is used. Guests get product
 * similarity and popularity.
 */

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: private, max-age=60');
header('Vary: Cookie');

include __DIR__ . '/../admin/databaseconnection.php';
require_once __DIR__ . '/../includes/recommendation_service.php';

$user_id = isset($_SESSION['user_id']) ? max(0, (int) $_SESSION['user_id']) : 0;

$service = new RecommendationService($conn, $user_id);

$context_product_id = isset($_GET['context_product_id'])
    ? max(0, (int) $_GET['context_product_id'])
    : 0;

$exclude_ids = array();
if (isset($_GET['exclude_id'])) {
    $exclude_ids = is_array($_GET['exclude_id'])
        ? $_GET['exclude_id']
        : array($_GET['exclude_id']);
}

$items = $service->recommend(array(
    'context_product_id' => $context_product_id,
    'exclude_ids' => $exclude_ids,
    'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 4,
));

$conn->close();

echo json_encode(array(
    'mode' => $user_id > 0 ? 'personalized' : 'popular',
    'count' => count($items),
    'items' => $items,
), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
