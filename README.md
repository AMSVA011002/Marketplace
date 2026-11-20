# Laksela Store — PHP/MySQL/Bootstrap rebuild

cPanel-ready custom PHP storefront based on the public layout and product details of laksela.lk. Built with PHP 8.4+, MySQL/MariaDB, Bootstrap 5.3.3, vanilla JS and custom CSS. This is a clean-room implementation, not a copy of the site's source or its WordPress theme.

## What is included

- Responsive homepage with promo bar, hero, category links, best-deal/popular/best-selling product rows, voucher, featured brands, trust features, footer and mobile nav.
- Shop/search/category/sort views; product details with discounts, Koko 3-instalment display, stock, delivery and related items.
- Session-based cart and wishlist, guest/account checkout, stock reservation/decrement inside a DB transaction, account registration/sign-in, customer order history.
- Admin dashboard for order status updates and product create/edit/delete, plus guarded CSV product import and CLI admin-account creation.
- MySQL schema and seed records including real product names and prices captured from the public shop page at inspection time.
- Bundled Bootstrap 5.3.3 assets, cPanel deployment notes and security-oriented defaults.

## Local setup

Requirements: PHP 8.1+ with `pdo_mysql`, MySQL 8 or compatible MariaDB, Apache with `mod_rewrite` (or PHP built-in server for a quick local preview).

1. Create a database, then import `database.sql` with phpMyAdmin or `mysql < database.sql`.
2. Copy `config.example.php` to `config.php`; set DB name, username and password. Keep it outside `public_html` when deploying. Never publish the example credentials.
3. Serve the `public/` directory as the document root. Example: `php -S 0.0.0.0:8000 -t public`.
4. Visit `/` and verify the seeded catalog. If the DB is not configured, the page shows a setup warning.

## cPanel deployment

1. Create a MySQL database/user in cPanel → MySQL Databases; grant all privileges. Import `database.sql` in phpMyAdmin.
2. Upload the **contents of `public/`** to `public_html/`. Put `config.php` one directory above `public_html` (or use the provided deny rule and restrict filesystem permissions).
3. Set the credentials and verify PHP `pdo_mysql`, `mbstring`, `gd` and `openssl` are enabled. Select the latest PHP version supported by the host (PHP 8.4/8.5 where available).
4. Enable SSL through cPanel and force HTTPS. Replace sample product image URLs with licensed/local images in production.
5. Place a first admin account by running a secure `password_hash()` creation script; delete that script after use. The `/admin` route is currently a scaffold and must not be treated as a production admin console.
6. `php create-admin.php admin@example.com 'a-long-unique-password-14-chars'` creates the first administrator from CLI. Never run it through the public web root.
7. PayHere and Koko are not integrated with live transactions. Their checkout choices currently only record the selected method; no payment is collected or verified. Obtain merchant onboarding credentials and implement/test the gateway's signed initiation + server callback before enabling online payments. COD currently creates an order record.
8. Test order flow, email notifications, backups, privacy policy, refund/delivery terms, and security on the actual hosting account.

## Important status / limitations

- The live source was not downloadable in the build environment; product prices/images are a snapshot from public shop page content and can change. Current seed includes 12 catalog records, not the full 756-product inventory. Use admin tooling/CSV importer before launch (not included yet).
- The site's original WordPress assets/theme are not bundled. Bootstrap layouts are approximate visual recreation, not pixel-perfect reproduction. The hero and promo blocks use CSS artwork rather than original banner imagery.
- PayHere and Koko are not yet live payment integrations. Never mark an online payment as paid until verified by the gateway callback/signature.
- `/admin` is a placeholder; no product/order management or customer registration flow is implemented yet.
- Password login only authenticates existing user records; create users through a secure process. Add rate limiting, password reset and email verification before production.
- Bootstrap is locally bundled. Google Fonts and remote product photos require internet access; download licensed images locally for offline/production use.

## Files

- `public/index.php` — storefront routes and request handling
- `public/assets/css/style.css` — custom responsive style layer
- `public/assets/css/bootstrap.min.css`, `public/assets/js/bootstrap.bundle.min.js` — Bootstrap 5.3.3 distribution
- `database.sql` — schema and sample real-product seed
- `config.example.php` — configuration template
- `docs/01-technology-identification-laksela.lk.md` — technology audit
- `docs/02-rebuild-plan-cpanel-php-bootstrap.md` — initial plan
