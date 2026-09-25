-- Product view history for the EcommerceDemo recommendation system.
--
-- Only views are stored here. Cart behaviour already lives in `cart` and
-- purchase history already lives in `orders` + `order_items`, so those are
-- read directly instead of being duplicated.
--
-- Run once against the existing EcommerceDemo database:
--   mysql -u root EcommerceDemo < database/product_views.sql
--
-- The storefront works without this table. RecommendationService simply skips
-- the view signal until the table exists.

CREATE TABLE IF NOT EXISTS product_views (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_product_views_user (user_id, created_at),
    KEY idx_product_views_product (product_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Optional periodic maintenance (run manually or from cron):
-- DELETE FROM product_views
-- WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
