# Candidate Notes & Implementation Assumptions

## Design & Aesthetics Decisions

- **Color Palette & Dark Mode**: Chosen slate backgrounds (`#0f172a`, `#1e293b`) paired with emerald green (`#10b981`) and cyan (`#06b6d4`) accents. These colors guarantee a high contrast ratio (well above the WCAG 2.1 AA requirement of 4.5:1 for normal text) when rendering on dark backgrounds.
- **Glassmorphic Layouts**: Utilized CSS variables, thin transparent borders, and `backdrop-filter: blur(12px)` to achieve premium modern dashboard visuals for the cards, header, and WooCommerce checkout boxes.
- **Grid Layouts**: Pure CSS Grid and Flexbox were utilized for all page column frameworks (no Bootstrap or heavyweight layout framework enqueued) to keep load time minimal and maintain responsive, mobile-first compatibility.

## Developer Assumptions

1. **WooCommerce Integration**: It is assumed WooCommerce is installed and active in the local database. The "Fitness Bundle" product type registered hooks directly into WooCommerce default templates.
2. **Gutenberg compilation**: We assume Gutenberg editor assets are loaded using `block.json`. The custom block utilizes `@wordpress/scripts` to compile JSX into standard ES5 JS and outputs a separate `style-index.css` file for page rendering.
3. **Login Page Rate Limiter**: The login rate limiter is IP-based. In case of multiple users sharing the same local proxy or office IP, they might get blocked concurrently; however, for local site verification and security hardening purposes, IP-based blocking represents the standard way to prevent brute force attacks.
4. **Direct DB Queries**: To demonstrate `$wpdb->prepare()` utilization, a custom table (`wp_fitlife_login_attempts`) was registered upon `init` to record unsuccessful login attempts. All inserts and queries on this table utilize `$wpdb->prepare()` securely.
5. **XML-RPC**: Disabling XML-RPC block via `.htaccess` assumes an Apache web server setup (standard for Laragon environments). For Nginx settings, Nginx location blocks would need to be updated.

## Verification Checklist

- **Theme Switched**: Switch Theme to `FitLife Pro Theme` in WordPress dashboard.
- **Plugin Active**: Activate `FitLife Core Plugin` in WordPress dashboard.
- **Custom Columns**: Check Trainer list view (`/wp-admin/edit.php?post_type=fitlife_trainer`) and Program list view (`/wp-admin/edit.php?post_type=fitlife_program`) to see extra columns populated.
- **REST API check**: Invoke GET requests at `/wp-json/fitlife/v1/trainers` and `/wp-json/fitlife/v1/programs` to check data structure return values.
- **Shortcode test**: Test `[fitlife_trainers]` and `[fitlife_programs]` inside standard Gutenberg pages.
- **Gutenberg Editor**: Add "Program Highlight" block or "Trainer Spotlight" block inside post editors.
