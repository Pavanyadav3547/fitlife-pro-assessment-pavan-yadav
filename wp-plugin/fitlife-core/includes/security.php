<?php
/**
 * Security Hardening for FitLife Pro
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Disable XML-RPC filter (Task 5.2)
add_filter( 'xmlrpc_enabled', '__return_false' );

// Database initialization for custom login rate limits
add_action( 'init', 'fitlife_create_login_attempts_table' );
function fitlife_create_login_attempts_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'fitlife_login_attempts';
    
    // Check if table exists
    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ip_address varchar(45) NOT NULL,
            attempted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            KEY ip_address (ip_address),
            KEY attempted_at (attempted_at)
        ) $charset_collate;";
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }
}

/**
 * Handle Failed Login attempts (Task 5.2)
 */
add_action( 'wp_login_failed', 'fitlife_log_failed_login' );
function fitlife_log_failed_login( $username ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'fitlife_login_attempts';
    
    $ip = fitlife_get_client_ip();
    
    // Insert attempt using prepared statement
    $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO $table_name (ip_address, attempted_at) VALUES (%s, %s)",
            $ip,
            current_time( 'mysql' )
        )
    );
}

/**
 * Rate limiting logic on the login page (Task 5.2)
 */
add_filter( 'authenticate', 'fitlife_check_login_rate_limit', 30, 3 );
function fitlife_check_login_rate_limit( $user, $username, $password ) {
    // If username is empty, let default validation handle it
    if ( empty( $username ) ) {
        return $user;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'fitlife_login_attempts';
    $ip = fitlife_get_client_ip();

    // Query attempts in the last 15 minutes using prepared statement
    $time_limit = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - ( 15 * MINUTE_IN_SECONDS ) );
    
    $failed_attempts = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(id) FROM $table_name WHERE ip_address = %s AND attempted_at > %s",
            $ip,
            $time_limit
        )
    );

    if ( intval( $failed_attempts ) >= 5 ) {
        return new WP_Error(
            'too_many_attempts',
            sprintf(
                __( '<strong>Error:</strong> Too many failed login attempts from this IP. Please try again in %d minutes.', 'fitlife' ),
                15
            )
        );
    }

    return $user;
}

/**
 * Helper to get clean client IP
 */
function fitlife_get_client_ip() {
    $ip = '0.0.0.0';
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        // Handle comma-separated list of IPs
        $parts = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
        $ip = trim( $parts[0] );
    } elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '0.0.0.0';
}

/**
 * Toggle reviews (comments) for Trainers and Programs CPTs based on Admin Settings (Task 2.4)
 */
add_filter( 'comments_open', 'fitlife_toggle_cpt_reviews', 10, 2 );
function fitlife_toggle_cpt_reviews( $open, $post_id ) {
    $post_type = get_post_type( $post_id );
    if ( 'fitlife_trainer' === $post_type || 'fitlife_program' === $post_type ) {
        $enabled = get_option( 'fitlife_enable_reviews', 'yes' );
        return ( 'yes' === $enabled );
    }
    return $open;
}
