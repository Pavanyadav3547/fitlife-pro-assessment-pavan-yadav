# FitLife Pro Development Notes

This document highlights the design decisions, core assumptions, and quick verification steps for the custom WordPress and Shopify implementations.

## 1. Design & Accessibility
* **Color Palette**: Premium light theme utilizing HSL Slate, Emerald Green, and Cyan accents (WCAG 2.1 AA compliant, contrast > 4.5:1).
* **Layouts**: Pure responsive CSS Grid and Flexbox (no Bootstrap or heavy frameworks).
* **Accessibility (WCAG AA)**: Keyboard-navigable walker menus, visible focus outlines, skip-to-content targets, and descriptive screen-reader tags (`.screen-reader-text`) for generic links.

## 2. Technical Decisions & Assumptions
* **WooCommerce**: Converted Cart and Checkout to classic shortcodes to preserve PHP hooks. Fixed the notices wrapper grid alignment and added custom checkout billing fields saved to order meta.
* **Database & Security**: Hardened site by blocking XML-RPC and implementing IP-based login rate limiting using `$wpdb->prepare()`.
* **Gutenberg Blocks**: Compiled React/JSX blocks with `@wordpress/scripts`. Rendered dynamic blocks via server-side PHP (`render_callback`).
* **Caching**: Database queries for Trainers and Programs CPTs are cached using the Transients API for 12 hours (cache automatically flushed on post updates).
* **Shopify**: Custom Liquid sections use native filters and mocks for checkout/inventory updates, falling back to mock mode on local hosts.

## 3. Quick Verification Steps
1. **Theme & Plugin**: Switch theme to `FitLife Pro Theme` and activate the `FitLife Core` plugin.
2. **CPT Lists**: Verify custom admin columns for trainers and programs in `wp-admin`.
3. **REST API**: Test routes `/wp-json/fitlife/v1/trainers` and `/wp-json/fitlife/v1/programs`.
4. **Shortcodes**: Add `[fitlife_trainers]` and `[fitlife_programs]` to any page.
5. **Shopify Custom CSS**: Inject `fitwear-custom.css` overrides directly in the Theme Customizer under Custom CSS to force clean square cards.
