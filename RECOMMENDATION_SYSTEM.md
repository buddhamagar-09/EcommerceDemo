# Product Recommendation System — Summary

Automated product recommendations added to EcommerceDemo. Recommends products from
product similarity plus the signed-in shopper's own views, cart and purchase
history, with a popularity fallback for guests and new accounts.

---

## 1. Files created

| File | Purpose |
| --- | --- |
| `includes/recommendation_service.php` | `RecommendationService` — all recommendation logic lives here, not in the pages |
| `files/components/recommendations.php` | Reusable strip partial. Reuses the existing `.card` markup from `index.php` / `products.php` |
| `files/assets/recommendations.css` | Styles for the strip, using the same colours, radii, shadows and breakpoints as the existing design |
| `files/recommendations.php` | JSON endpoint: `limit`, `context_product_id`, `exclude_id` |
| `database/product_views.sql` | The one new table (see §3) |
| `tests/recommendation_test.php` | 35 automated checks, self-cleaning |

## 2. Files modified

Three pages, **+42 lines total, nothing removed or rewritten**:

| File | Change |
| --- | --- |
| `files/index.php` | +13 — `require_once`, service call, CSS link, partial include |
| `files/product_details.php` | +16 — same, plus `record_view()` on load |
| `files/cart.php` | +13 — same, with cart contents excluded |

`admin/`, checkout, success and failure pages were **not** touched.

> **Note on a reverted earlier attempt.** The working tree contained an uncommitted
> 890-line recommendation engine that rewrote login (`admin/userredirect.php`),
> cart updates (`admin/update_cart.php`), `success.php` and `cod_success.php`, and
> left `files/product_details.php` broken (it deleted `$row = mysqli_fetch_assoc(...)`
> but left ~10 template references to `$row`, so the page rendered a blank
> product). All of that was reverted. Those five files are now byte-identical to
> `HEAD`, and the product details page renders correctly again.

## 3. Database changes

**One table added — `product_views`:**

```
id         INT(11) AUTO_INCREMENT PRIMARY KEY
user_id    INT(11) NOT NULL
product_id INT(11) NOT NULL
created_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP
INDEX (user_id, created_at)
INDEX (product_id, created_at)
```

Views were the **only** behaviour signal not already stored, so nothing is
duplicated:

| Signal | Stored in | Read from |
| --- | --- | --- |
| Views | `product_views` *(new)* | direct |
| Cart | `cart` *(existing)* | direct |
| Purchases | `orders` + `order_items` *(existing)* | direct |
| Popularity | `order_items` *(existing)* | direct |

`cart`, `orders`, `order_items`, `users` and `products` are unmodified.

**Graceful degradation:** if the table is never created, the view lookups no-op
and the rest of the system keeps working. Nothing breaks.

## 4. How the score works

Fully additive. One constant per rule, declared at the top of the class:

| Signal | Points | Cap |
| --- | :---: | :---: |
| Name word shared with something the shopper **bought** | +4 | 8 |
| Name word shared with something the shopper **viewed** | +3 | 6 |
| **Family** word in both names (`jean`, `shirt`) | +3 | 6 |
| Name word shared with something in the **cart** | +2 | 4 |
| One-off **tag** word in both names (`midnight`) | +1 | — |
| Price within 20% of the product being viewed | +1 | — |
| **Popular** (scaled, best seller = 1.0) | +1 | — |

**Family vs. tag.** `products` has no category, brand or tag column, so the
product family is derived from the product name: a word used by **3 or more**
product names is a *family* word (the stand-in for "same category"); anything
rarer is a one-off *descriptor*. That single rule is why viewing
"Black Slim Fit Denim Jeans" returns the other three jeans ahead of the
sunglasses that happen to share the colour word "black".

**Ties** break on stronger similarity, then popularity, then newest.

Each result also carries the winning bucket as a human label — "Similar to
Midnight Curve Skinny Jeans", "Based on your previous orders", "Popular right now".

## 5. How recommendations are generated

1. Load the catalogue once — available products only (`quantity > 0`), each name
   reduced to words (lowercased, stop words dropped, simple plurals folded so
   "Jeans" and "Jean" match).
2. Read the shopper's own signals: purchases, views, cart.
3. Score every candidate in memory, then sort and take the top 4.
4. Strip is rendered by the shared partial.

**Guarantees enforced structurally, not by convention:**

- The product being viewed is excluded by the service itself, whether or not the
  caller remembers to pass it.
- Only `quantity > 0` products are ever candidates.
- Purchased products are never re-suggested.
- The cart page hides what is already in the cart.
- No duplicates — each product is scored once, from a keyed map.
- Guests always get a full section; unscored products fill remaining slots with a
  neutral label rather than leaving a half-empty grid.

**Guests vs. members**

| | Guests | Signed in |
| --- | --- | --- |
| Product similarity | yes | yes |
| Popularity fallback | yes | yes |
| View / cart / purchase history | no | yes (own only) |

**Security:** only `$_SESSION['user_id']` is ever read. No other shopper's data is
touched, and the response contains no personal information.

**Performance:** 5 queries per request, constant regardless of catalogue size —
products are scored in memory, so there is no N+1. The catalogue and the
popularity ranking are disk-cached for 300 seconds; only the per-user signal
queries run every time.

## 6. Commands

```powershell
# 1. Apply the table (once)
Get-Content database\product_views.sql -Raw | C:\xampp\mysql\bin\mysql.exe -u root EcommerceDemo

# 2. Run the test suite
C:\xampp\php\php.exe tests\recommendation_test.php

# 3. Clear the cache after editing products or orders
Remove-Item "$env:TEMP\ecommercedemo_recommendations_catalogue.cache"
```

No composer, npm or build step — this is plain procedural PHP. The cache file
lives in the system temp directory, outside the web root.

**Where it appears**

| Page | Heading | Logic |
| --- | --- | --- |
| Product details | "You May Also Like" | Similarity to the viewed product + personalisation. Records a view for members. |
| Home | "Recommended For You" (members) / "Popular Products" (guests) | Personalisation, falling back to popularity |
| Cart | "You May Also Like" | Personalisation, minus anything already in the cart |

## 7. Testing

`tests/recommendation_test.php` — **35 checks, all passing.** Creates its own
fixtures (scratch user, sold-out product, scratch orders) and removes them on the
way out, so storefront data is left exactly as found. Covers:

guest recommendations · product similarity · current product excluded (with *and*
without the caller passing it) · out-of-stock excluded · no duplicates ·
popularity fallback ordering · new user with no history · purchase history
(already-bought excluded, category surfaced) · cart behaviour · view recording
and 30-minute dedupe · view history feeding the ranking · limit clamping ·
unknown product ids · sort order · availability.

Verified live over HTTP: guest home, member home, product details, cart, the JSON
endpoint, and every other page in the project (no regressions, no new PHP
warnings). All files pass `php -l`.

## 8. Limitations and future improvements

**Judgement calls worth reviewing**

1. **Descriptions are deliberately not matched.** Every description in this
   catalogue is marketing copy built from the same handful of words ("style",
   "casual", "everyday"). Matching them ranked a pair of jeans above the other
   pair of sunglasses and diluted the user-behaviour signals too. Matching
   product names only is simpler *and* measurably better here. If descriptions
   are ever rewritten to be specific, this is worth revisiting.
2. **Recommendation cards show the reason, not the description.** The original
   cards print a description, but four cards' worth of long marketing text looked
   worse than a short label. The field is still in the response — swap it back in
   `files/components/recommendations.php` if preferred.
3. **No wishlist signal.** The project has no wishlist table, so wishlist
   behaviour is not used. Adding one later means one entry in
   `load_user_signals()`, mirroring `cart`.

**Known constraints**

- `products` has no `active` flag, so "available" means `quantity > 0`.
- Family detection needs 3+ products sharing a word, so a category with only one
  or two products scores as a tag rather than a family. Fine at 12 products;
  revisit as the catalogue grows.
- Purchased products are never re-suggested, per the original requirement.
  Replenishment reminders would need an explicit opt-in.
- Popularity falls back to view counts only when there is *no* order data at all.

**Pre-existing issues found, left untouched on purpose**

- `admin/update_cart.php:3-5` redirects to login but has no `exit()`, so line 7
  reads `$_SESSION['user_id']` with no session — it warns and keeps executing.
  A one-line fix; not changed because it is outside this task's scope.
- Passwords are stored and compared in plaintext throughout the auth layer.
  Unrelated to recommendations, but worth knowing.

**Possible next steps**

- Add real `category` / `brand` columns and backfill them, which would make the
  scoring direct instead of inferred.
- Wire the JSON endpoint into a lazy-loaded "more recommendations" strip.
- Invalidate the cache on product save (`admin/addproduct.php`,
  `admin/editproduct.php`) rather than waiting out the 300s TTL.
