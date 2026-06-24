<?php
/**
 * Plugin Name: FitLife Core Plugin
 * Plugin URI: https://fitlifepro.example.com/plugin
 * Description: Core functionalities for FitLife Pro website (CPTs, Taxonomies, Meta Boxes, Shortcodes, API, Admin Settings).
 * Version: 1.0.0
 * Author: Pavan Yadav
 * Author URI: https://fitlifepro.example.com
 * Text Domain: fitlife
 * License: GPL2
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define Constants
define( 'FITLIFE_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'FITLIFE_CORE_URL', plugin_dir_url( __FILE__ ) );

// Load CPTs & Taxonomies
require_once FITLIFE_CORE_PATH . 'includes/cpt/cpt.php';

// Load Meta Boxes
require_once FITLIFE_CORE_PATH . 'includes/meta-boxes/meta-boxes.php';

// Load REST API
require_once FITLIFE_CORE_PATH . 'includes/rest-api/rest-api.php';

// Load Shortcodes
require_once FITLIFE_CORE_PATH . 'includes/shortcodes/shortcodes.php';

// Load Settings Page
require_once FITLIFE_CORE_PATH . 'admin/settings-page.php';

// Load Gutenberg Blocks Integration
require_once FITLIFE_CORE_PATH . 'includes/blocks.php';

// Load WooCommerce Integration
require_once FITLIFE_CORE_PATH . 'includes/woocommerce.php';

// Load Security Hardening
require_once FITLIFE_CORE_PATH . 'includes/security.php';

/**
 * Flush rewrite rules on activation
 */
function fitlife_core_activate() {
    // Register CPTs and Taxonomies first so rewrite rules work
    fitlife_register_cpts();
    fitlife_register_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'fitlife_core_activate' );

/**
 * Flush rewrite rules on deactivation
 */
function fitlife_core_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'fitlife_core_deactivate' );
