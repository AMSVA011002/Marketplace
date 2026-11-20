# Rebuild Plan — Laksela-style store on cPanel (PHP + MySQL + Bootstrap)

**Status:** PROPOSAL — awaiting your decision (see identification doc `01-technology-identification-laksela.lk.md`).
**Target:** Exact layout/content parity with https://laksela.lk/ , hosted on a single cPanel account with MySQL on the same hosting.

## 1. Proposed stack (latest stable, vulnerability-free at build time)

| Layer | Choice | Why |
|---|---|---|
| PHP | **8.4.x** (fallback 8.3.x if cPanel only offers that; 8.5.x only if host already supports it) | 8.4 is in active support until Dec 2026, security fixes to Dec 2028; 8.5 (Nov 2025) is newest but many cPanel hosts lag — pick what the host offers, never EOL 8.1/8.2 |
| MySQL | **MySQL 8.0 / 8.4 LTS** (or MariaDB 11.x if that's what cPanel provides) | Same-host database via cPanel → MySQL Databases; `utf8mb4` |
| Frontend | **Bootstrap 5.3.3** (latest stable 5.x) + vanilla JS (ES2023) + custom CSS | No build step, no npm — drop-in files for cPanel; Bootstrap replaces Glozin's CSS framework |
| Server | Apache + mod_rewrite (cPanel standard) | `.htaccess` routing, no Node/nginx needed |
| Security | PDO prepared statements, `password_hash`, CSRF tokens, `htmlspecialchars` everywhere, HTTPS (Let's Encrypt via cPanel), `config.php` outside `public_html` or denied by `.htaccess` | "Vulnerability-free" baseline: no SQL injection, no XSS, no plaintext passwords |

> Note: the original site uses **no Bootstrap** (Glozin theme CSS). Using Bootstrap is your requested change — layout will be pixel-matched with Bootstrap grid/utilities + custom CSS.

## 2. Architecture (no framework — plain PHP, cPanel-friendly)

```
public_html/
  index.php            → homepage (hero slider, categories, deals, popular, brands, promos, trust row)
  shop.php             → catalog: filters, sort, grid/list, 2/3/4-col switch (756 products, paginated)
  product.php?id=…     → product detail (gallery, tabs, related, Koko line, live-viewers stub)
  category.php?slug=…  → category listing
  brand.php?slug=…     → brand listing
  cart.php / checkout.php / wishlist.php / account.php / contact.php / special-offers.php
  admin/               → product/category/order/user CRUD (session + CSRF protected)
  assets/css|js|img/   → Bootstrap 5.3.3 + custom style.css + app.js
  uploads/products/    → product images (WebP)
config.php             → DB credentials (NOT in public_html if possible; else .htaccess deny)
.htaccess              → pretty URLs, HTTPS redirect, security headers
database.sql           → full schema + seed data
```

Routing: single front-controller style with `?page=` fallback + `.htaccess` rewrites for pretty URLs (`/product/slug/`).

## 3. Database schema (core tables)

`categories` (id, parent_id, name, slug, image, sort) · `brands` (id, name, slug, logo) · `products` (id, sku, name, slug, brand_id, category_id, price, old_price, stock, short_desc, description, specs JSON, image, gallery JSON, sold_count, rating, featured, best_selling, created_at) · `product_categories` (pivot) · `users` (id, name, email, password_hash, role, phone, address) · `orders` (id, user_id, total, status, payment_method, gateway_ref, created_at) · `order_items` · `cart_items` (session/user) · `wishlist` · `coupons` (code, discount_pct) · `banners`/`pages` (hero + promo content, editable from admin) · `settings` (store phone, WhatsApp, email, Koko toggle).

## 4. Feature parity checklist (from inspection)

- [ ] Promo top bar, hero slider (6 slides), special-offers strip
- [ ] Category grid with product counts (8 top categories + sub-categories)
- [ ] Today's Best Deals / Popular Picks / Best Selling carousels (discount badges, Quick View modal, Add to cart)
- [ ] Big Promotion voucher banner with copy-code (CODE6789)
- [ ] Promo banners: washers, refrigerators, gas cooker, laptop, TV 40% off
- [ ] Featured Brands row (8 brands) + Premium Home Electronics banner
- [ ] Product page: gallery/lightbox, stock, sold-in-7h, viewers-now, Koko 3× line, tabs, related products
- [ ] Shop: filters, sort (6 modes), grid/list + column switch, pagination
- [ ] Cart drawer + order note + coupon, wishlist, account, checkout (COD + card gateway placeholder + Koko BNPL display)
- [ ] Footer: Secure Delivery / Easy Returns / Support Online + Help Center (076 123 7770, WhatsApp, online@laksela.lk) + mobile bottom nav
- [ ] Admin panel: products, categories, brands, orders, coupons, banners, settings
- [ ] SEO: meta tags, sitemap.xml, robots.txt, pretty URLs

## 5. cPanel deployment plan

1. cPanel → **Select PHP Version**: 8.4 (enable `pdo_mysql`, `mbstring`, `gd`, `curl`, `zip`).
2. cPanel → **MySQL Databases**: create DB + user, import `database.sql` via phpMyAdmin.
3. Upload project to `public_html/` (File Manager / FTP); put `config.php` credentials in; `chmod 750 uploads/`.
4. cPanel → **Security → SSL/TLS → Let's Encrypt**: issue cert; `.htaccess` forces HTTPS.
5. cPanel → **Domains**: point domain; test `/`, `/shop`, `/product/...`, admin login.
6. Backups: cPanel Backup Wizard (files + DB) weekly; keep PHP/MySQL updated.

## 6. Decisions needed from you before build

1. **Stack confirmation:** custom PHP + Bootstrap rebuild (as above) vs. WordPress + WooCommerce clone (faster parity, but WP security burden) vs. layout-only demo first?
2. **Scope:** full e-commerce (cart/checkout/admin/payments) in phase 1, or homepage + shop + product pages first?
3. **Data:** seed with the real product names/prices seen on laksela.lk (sample ~30–50 products) or empty DB + CSV importer?
4. **Payments:** which gateways must work day 1 — COD only, PayHere, Koko BNPL, bank transfer?
5. **Images:** reuse laksela.lk image URLs as placeholders (hotlink) or generate placeholder product images locally?
