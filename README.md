# FitLife Pro WordPress, WooCommerce & Shopify Developer Assessment

This repository contains the complete custom development for the FitLife Pro WordPress theme, core plugin, custom Gutenberg blocks, and WooCommerce customizations.

## Directory Structure

```
fitlife-pro-assessment-pavan/
|-- wp-theme/                  # Custom WordPress Theme
|   `-- fitlife-pro/           # Custom Theme Folder
|       |-- style.css          # Theme Stylesheet (Responsive Grid/Flexbox)
|       |-- functions.php      # Theme functions, Walker, Enqueues, Supports
|       |-- header.php         # Page Header & Accessible Menus
|       |-- footer.php         # Page Footer
|       |-- sidebar.php        # Default Sidebar Widgets layout
|       |-- front-page.php     # High-End Homepage Layout
|       |-- single.php         # Detail View for Posts and CPTs (with Meta render)
|       |-- archive.php        # Optimized Archive listing
|       |-- 404.php            # Beautiful Error page
|       |-- search.php         # Accessible Search layout
|       |-- page-trainers.php  # CPT directory with Specialty tax filters
|       |-- assets/            # Theme assets
|       |   `-- js/
|       |       `-- navigation.js # Keyboard-Accessible Dropdown Navigation
|       `-- woocommerce/       # WooCommerce template overrides
|           |-- single-product.php
|           `-- emails/
|               `-- customer-completed-order.php
|
|-- wp-plugin/                 # Custom WordPress Plugin
|   `-- fitlife-core/          # Core Plugin Folder
|       |-- fitlife-core.php   # Main Plugin Bootstrap
|       |-- admin/
|       |   `-- settings-page.php # Brand settings under Settings > FitLife Settings
|       |-- includes/
|       |   |-- cpt.php        # Custom CPTs and Taxonomies (Trainers & Programs)
|       |   |-- meta-boxes.php # Secure Custom Metaboxes (Nonces & Sanitation)
|       |   |-- rest-api.php   # Filters & JWT/Capability Authenticated Endpoints
|       |   |-- shortcodes.php # Output buffered [fitlife_trainers] grids
|       |   |-- blocks.php     # Gutenberg block registrations & patterns
|       |   |-- woocommerce.php# WC Custom Bundle, fields, checkout & emails
|       |   `-- security.php   # Security Hardening, login rate limiter
|       `-- assets/
|           `-- js/
|               `-- editor-variations.js # Gutenberg Column Variation script
|
|-- gutenberg-blocks/          # Gutenberg Custom Block Source & Configs
|   `-- fitlife-blocks/
|       |-- package.json       # Build configuration scripts
|       |-- block.json         # Global block list
|       |-- src/
|       |   |-- program-highlight/ # Custom React block (RichText, MediaUpload)
|       |   `-- trainer-spotlight/ # Dynamic PHP block (Editor selector)
|       `-- build/             # Webpack Compiled Block Assets
|
|-- shopify/                   # Custom Shopify Sections & Assets
|   |-- sections/
|   |   |-- fitwear-hero-banner.liquid
|   |   `-- featured-collection-grid.liquid
|   |-- snippets/
|   |   |-- size-guide-modal.liquid
|   |   `-- ajax-cart-drawer.liquid
|   |-- assets/
|   |   |-- fitwear-custom.css
|   |   `-- fitwear-custom.js
|   `-- api-scripts/
|       `-- shopify-api.js     # GraphQL queries & mutations script
|
|-- screenshots/               # Required Screenshots
|   |-- lighthouse-score.png
|   |-- admin-cpt-view.png
|   |-- frontend-homepage.png
|   |-- gutenberg-editor.png
|   |-- woocommerce-checkout.png
|   `-- shopify-storefront.png
|
|-- README.md                  # This file
`-- NOTES.md                   # Candidate assumptions & design decisions
```

---

## Technical Stack & Environment

* **WordPress Version**: `7.0-alpha` (Local environment WordPress Core)
* **PHP Version**: `8.2.12`
* **MySQL/MariaDB Version**: `8.0.30`

---

## Local Setup & Installation Instructions

Follow these instructions to run this project in your local development environment:

### Prerequisites
* Install a local server environment (e.g., **Laragon**, **LocalWP**, or **XAMPP**).
* Install **PHP 8.2** or higher and **MySQL 8.0** or higher.
* Ensure you have **Node.js** (v18+) and **npm** installed if compiling blocks.

### Step 1: WordPress Setup
1. Create a database named `fitlife-pro` in your MySQL server.
2. Download and extract WordPress core files into your local host directory (e.g., `C:/laragon/www/fitlife-pro`).
3. Set up the `wp-config.php` file using your database credentials.
4. Complete the WordPress installation through your web browser.

### Step 2: Clone & Link Project Files
1. Clone or copy the assessment files (`fitlife-pro-assessment-pavan`) into your WordPress root directory.
2. Link the custom theme and plugin folders using directory junctions (or symlinks) from the root workspace:
   * **Theme Junction**: Link `wp-content/themes/fitlife-pro` pointing to `fitlife-pro-assessment-pavan/wp-theme/fitlife-pro`
   * **Plugin Junction**: Link `wp-content/plugins/fitlife-core` pointing to `fitlife-pro-assessment-pavan/wp-plugin/fitlife-core`
   *(On Windows PowerShell: `cmd /c mklink /J wp-content\themes\fitlife-pro fitlife-pro-assessment-pavan\wp-theme\fitlife-pro` and `cmd /c mklink /J wp-content\plugins\fitlife-core fitlife-pro-assessment-pavan\wp-plugin\fitlife-core`)*

### Step 3: Activate Theme, Plugin & WooCommerce
1. Log in to your WordPress Admin dashboard.
2. Go to **Plugins > Installed Plugins** and activate the **WooCommerce** plugin.
3. Activate the **FitLife Core** plugin.
4. Go to **Appearance > Themes** and activate the **FitLife Pro** theme.
5. In the WordPress settings panel under **Settings > Permalinks**, select **Post name** and save changes to flush rewriting rules.

### Step 4: Gutenberg Block Compilation (Optional)
If you wish to modify Gutenberg React/JSX block sources:
1. Navigate to the block folder:
   ```bash
   cd fitlife-pro-assessment-pavan/gutenberg-blocks/fitlife-blocks
   ```
2. Install node dependencies:
   ```bash
   npm install
   ```
3. Compile production blocks:
   ```bash
   npm run build
   ```

---

## Third-Party Libraries & Plugins

The following external libraries and dependencies were integrated to meet styling and functionality requirements:

1. **WooCommerce (WordPress Plugin)**:
   * **Why**: Serves as the ecommerce foundation. Custom product types (`fitness_bundle`), checkout goal fields, and my account modifications extend WooCommerce hooks and templates.
2. **FontAwesome (CDN Stylesheet)**:
   * **Why**: Enqueued from CDN to provide clean vector icons for navigation menus (dropdown chevrons) and training program details (calendar/badge icons).
3. **Google Fonts (Outfit & Inter)**:
   * **Why**: Selected to replace default system browser fonts, providing premium modern typography.
4. **@wordpress/scripts (Build Tooling)**:
   * **Why**: Used as the standard webpack build configuration for compiling modern ESNext React JSX block code into browser-safe ES5 code.

---

## Staging Site Credentials

To verify the completed storefront, administrative controls, and checkout flows on the staging site:

* **Staging Site URL**: `https://honeydew-pelican-869842.hostingersite.com/` (Hostinger environment)
* **Administrator Username**: `FitLife Pro`
* **Administrator Password**: `@FitLife_Pro123`

*(Note: If testing in your own local Laragon environment, use the local administrator credentials you set up during Step 1).*

---

# Shopify Store Details

Store URL:
https://fitwear-dev-gifmseo4.myshopify.com

Collaborator Access:
Collaborator invitation has been sent to [assessments@fitlifepro.com](mailto:assessments@fitlifepro.com) with access to review the FitWear Shopify storefront implementation.

---

## Key Feature Integrations

* **Accessible Navigation Menu**: Built on custom `Walker_Nav_Menu` using `FitLife_Walker_Nav_Menu` inside `functions.php` and matching JavaScript key handlers in `navigation.js`. Compliant with WCAG 2.1 AA keyboard tab traversal, Escape close triggers, and aria-expanded state tags.
* **REST API Extensions**: GET endpoints registered at `/wp-json/fitlife/v1/trainers` and `/wp-json/fitlife/v1/programs` to support filtering by taxonomy terms and difficulty levels. POST program creation requires `edit_posts` capabilities (secured).
* **WooCommerce Bundle**: Custom product type `fitness_bundle` registered, supporting additional fields (Calorie count, Protein per serving, Allergens) which automatically append on single product page summary hooks.
* **Transients Caching**: Database queries for lists of trainers and fitness programs are cached using the Transients API for 12 hours. The cache is automatically cleared when new posts are created or modified.
* **Security Hardening**: Disabled XML-RPC both via WP filter and `.htaccess`. Implemented custom login rate limiting: blocks IP address for 15 minutes after 5 failed login attempts within a 15-minute window.

---

## Known Limitations & Incomplete Tasks

1. **Shopify API Environment Constraints**:
   * *Limitation*: The storefront AJAX Cart Drawer scripts fetch `/cart.js`, `/cart/add.js`, and `/cart/change.js`. These are native endpoints of live Shopify environments. When testing locally or on standard local servers outside of a live Shopify developer store, these requests fail.
   * *Resolution*: A robust fallback mock database mode is integrated directly into the `fitwear-custom.js` scripts. When the Shopify endpoints are unavailable, the cart drawer automatically switches to mock state updates, allowing full preview of cart counters, the $100 free shipping meter, line items, and upsells in any web browser.
2. **IP-Based Login Rate Limiter**:
   * *Limitation*: The login brute force protection tracks failed login counts based on client IP. In multi-user setups sharing a single public gateway or office proxy, a single user triggering a lockout could temporarily block other users sharing that IP address.
3. **GraphQL Admin Script Keys**:
   * *Limitation*: The `shopify-api.js` script expects `SHOPIFY_SHOP_URL` and `SHOPIFY_ACCESS_TOKEN` environment variables. Without them, it defaults to mock mode. Storing credentials directly in the codebase is avoided for security compliance.
