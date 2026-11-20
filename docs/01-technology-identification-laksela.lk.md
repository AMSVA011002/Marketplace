# Technology Identification — https://laksela.lk/

**Date inspected:** 2026-11-20
**Site title:** "Laksela.lk | Shop Premium Electronics & Home Appliances Online"
**Method:** Live inspection of homepage, `/shop/`, a product page, `/wp-json/`, `/robots.txt`, `/sitemap_index.xml` + public search. No source code was accessed — all findings below are from public fingerprints (URLs, REST namespaces, asset paths, sitemap structure).

---

## 1. Verdict (one line)

**laksela.lk is a WordPress + WooCommerce electronics store** built with the **Glozin Electro** WooCommerce theme and **Elementor** page builder, running on a **LiteSpeed** server stack with Yoast SEO, Koko BNPL and Sri Lankan payment-gateway plugins. It is **not** a custom PHP/Bootstrap build.

## 2. Evidence table

| Layer | Technology identified | Evidence (public fingerprint) |
|---|---|---|
| CMS | **WordPress** | `/wp-content/uploads/...` image URLs everywhere; `/wp-admin/` in robots.txt; `/wp-json/` REST API live; `page_for_posts`, `page_on_front` in wp-json index |
| E-commerce | **WooCommerce** | `wc/v3`, `wc/v1`, `wc/store/v1`, `wc-admin` namespaces in wp-json; `?add-to-cart=1000593` links; routes `/shop/`, `/product/.../`, `/product-category/.../`, `/my-account/`, `/shopping-cart/`, `/wishlist/`; "There are 756 results in total" on shop |
| Theme | **Glozin — Electro** (WooCommerce electronics theme) | Product page loads icons from `https://wpglozin.com/electro/wp-content/uploads/sites/24/2025/03/...` (free-ship.svg, delivery.svg, star.svg) labelled "Glozin" |
| Page builder | **Elementor** (+ Elementor One / AI / MCP) | `elementor/v1`, `elementor/v1/documents`, `elementor-one/v1`, `elementor-ai/v1`, `elementor-mcp-composer/v1.0.20` namespaces |
| Server / cache | **LiteSpeed Server + LiteSpeed Cache (LSCache)** | `litespeed/v1`, `litespeed/v3` namespaces — only served by LiteSpeed Cache plugin |
| SEO | **Yoast SEO** | `yoast/v1` namespace; `sitemap_index.xml` with `product-sitemap.xml`, `product_cat-sitemap.xml`, `product_brand-sitemap.xml`, `page-sitemap.xml` |
| Frontend JS/CSS | WordPress core **jQuery** + theme CSS/JS (Glozin) + Elementor frontend | Standard WP asset pipeline (`wp-includes`, `wp-content/themes/...`); no React/Vue SPA — classic server-rendered pages |
| Backend language / DB | **PHP + MySQL/MariaDB** (WordPress requirement) | WordPress/WooCommerce only run on PHP + MySQL; exact PHP version is hidden (not exposed publicly) |
| Forms | **Contact Form 7** | `contact-form-7/v1` namespace |
| Email | **Fluent SMTP** | `fluent-smtp` namespace |
| Security/stats | **Jetpack** | `jetpack/v4` namespace |
| Newsletter | **Mailchimp for WordPress (MC4WP)** | `mc4wp/v1` namespace |
| Checkout | **Flexible Checkout Fields** | `flexible-checkout-fields/v1` namespace |
| Order email | **YayMail** | `yaymail/v1` namespace |
| Payments | **PGBF Pro** (payment-gateway fee manager — PayHere-compatible) + **Koko BNPL** | `pgbf-pro/v1` namespace with `gateways`, `fees` endpoints; every product shows "or 3 X Rs... with Koko" + `paykoko.com` logos |
| Brands | **WooCommerce Product Brands** (or similar) | `/brand/samsung/`, `/brands/` routes + `product_brand-sitemap.xml` |
| Jobs | **AWSM Job Openings** | `awsm_job_openings-sitemap.xml` |
| Utilities | **Code Snippets** | `code-snippets/v1` namespace |
| Payments (checkout) | Safe-checkout logos image `payment_logos.jpg` on product page | Sri Lanka gateways (PayHere / bank / COD style) — exact gateway not fingerprinted publicly |

## 3. What the store does (functional inventory)

- **Catalog:** ~756 products across categories — Television & Audio (78), Kitchen Appliances (335), Home Appliances (191), Mobile & Laptop (29), Personal Care (31), Lifestyle (44), Air Conditioner & Coolers (46), Furniture (0). Sub-categories e.g. `kitchen-appliances/food-preparation/air-fryer`, `.../mixer-grinder`, `.../refrigerators-freezers/refrigerator`, `.../cooker-burners/gas-cooker`, `home-appliances/washers-dryers`, `mobile-laptop/laptop`, `television-audio/television`.
- **Product page:** gallery + lightbox, discount badge (-10%…-50%), stock ("2 in stock" / "Out of stock"), brand link, "8 sold in last 7 hours", live viewers ("5 people are viewing this right now"), old/new price in Rs., Koko 3× instalment line, short spec line, quantity + Add to cart, delivery estimate (1–5 days), free returns, SKU, safe-checkout logos, Description / Additional information tabs (Weight, Dimensions, Model, Color), trust badges, Related products carousel.
- **Homepage sections (in order):** top promo bar ("Special Offers Upto 50%. Shop Sale") → hero slider (Cool Comfort / Grind with Ease / Run Your Way Every Day / Crispy Moments / Bring Stories to Life / Cook Control and Breathe with Ease) → special-offers image strip → Product Categories grid (with counts) → Today's Best Deals carousel → Big Promotion voucher banner (CODE6789) → Popular Picks → promo banners (washing machines, refrigerators, gas cooker, laptop deals, TV up to 40% off) → Best Selling → Featured Brands (Innovex, Samsung, Singer, Hisense, Abans, Mitshu, Telesonic, Midea) → Premium Home Electronics banner + 3 featured products → trust row (Secure Delivery / Easy Returns / Support Online) → Help Center footer block (Call 076 123 7770, WhatsApp, online@laksela.lk) → mobile bottom nav (Home / Account / Shop / Wishlist / Cart) + cart drawer + coupon popup.
- **Shop page:** filter sidebar, 756 results, grid/list + 2/3/4-column view switch, sorting (default, popularity, rating, latest, price low→high / high→low, date), per-card discount badge, Quick View, Add to cart, Koko line, short spec.
- **Commerce extras:** wishlist, cart drawer with order note + coupon, Koko BNPL, voucher codes, WhatsApp chat (`wa.me/94761237770`), phone `076 123 7770`, email `online@laksela.lk`.

## 4. What is NOT used

- No Shopify / Magento / custom Laravel / Node — URL structure (`/product/`, `?add-to-cart=`, `/wp-json/`) is unmistakably WordPress + WooCommerce.
- No Bootstrap fingerprint was found — the Glozin theme uses its own CSS framework, not Bootstrap. (Your rebuild brief asks for Bootstrap, which is a deliberate change from the original.)
- No public version numbers are exposed for WordPress/PHP/theme — versions cannot be confirmed without admin access.

## 5. Implication for the rebuild

Rebuilding "exact layout" on **custom PHP + MySQL + Bootstrap (cPanel)** means re-implementing the Glozin Electro look-and-feel and WooCommerce behaviour (catalog, cart, checkout, wishlist, Koko-style instalments, brands, filters, quick view) from scratch — it will **not** be a WordPress clone, and WordPress plugins (Elementor, Yoast, Koko, PayHere) have no drop-in equivalents; each must be re-built or integrated via API. See `docs/02-rebuild-plan-cpanel-php-bootstrap.md`.
