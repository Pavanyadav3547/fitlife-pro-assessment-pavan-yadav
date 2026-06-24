<?php
/**
 * Shortcodes for FitLife Core
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'fitlife_trainers', 'fitlife_trainers_shortcode' );
add_shortcode( 'fitlife_programs', 'fitlife_programs_shortcode' );

/**
 * [fitlife_trainers specialty=""] shortcode
 */
function fitlife_trainers_shortcode( $atts ) {
    $args = shortcode_atts( array(
        'specialty' => '',
        'limit'     => -1,
    ), $atts, 'fitlife_trainers' );

    $query_args = array(
        'post_type'      => 'fitlife_trainer',
        'posts_per_page' => intval( $args['limit'] ),
        'post_status'    => 'publish',
    );

    if ( ! empty( $args['specialty'] ) ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'specialty',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $args['specialty'] ),
            ),
        );
    }

    // Cache with transients API (Task 5.1)
    $transient_key = 'fitlife_shortcode_trainers_' . md5( serialize( $query_args ) );
    $query = get_transient( $transient_key );

    if ( false === $query ) {
        $query = new WP_Query( $query_args );
        set_transient( $transient_key, $query, 12 * HOUR_IN_SECONDS );
    }

    ob_start();
    if ( $query->have_posts() ) {
        echo '<div class="grid-layout fitlife-trainers-shortcode">';
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/card', 'trainer' );
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="fitlife-no-posts">' . esc_html__( 'No trainers found.', 'fitlife' ) . '</p>';
    }

    return ob_get_clean();
}

/**
 * [fitlife_programs type="" limit=""] shortcode
 */
function fitlife_programs_shortcode( $atts ) {
    // Read the Programs Per Page settings (Task 2.4 settings API integration)
    $default_limit = get_option( 'fitlife_programs_per_page', 6 );

    $args = shortcode_atts( array(
        'type'  => '',
        'limit' => $default_limit,
    ), $atts, 'fitlife_programs' );

    $query_args = array(
        'post_type'      => 'fitlife_program',
        'posts_per_page' => intval( $args['limit'] ),
        'post_status'    => 'publish',
    );

    if ( ! empty( $args['type'] ) ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'program_type',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $args['type'] ),
            ),
        );
    }

    // Cache with transients API (Task 5.1)
    $transient_key = 'fitlife_shortcode_programs_' . md5( serialize( $query_args ) );
    $query = get_transient( $transient_key );

    if ( false === $query ) {
        $query = new WP_Query( $query_args );
        set_transient( $transient_key, $query, 12 * HOUR_IN_SECONDS );
    }

    ob_start();
    if ( $query->have_posts() ) {
        echo '<div class="grid-layout fitlife-programs-shortcode">';
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/card', 'program' );
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="fitlife-no-posts">' . esc_html__( 'No programs found.', 'fitlife' ) . '</p>';
    }

    return ob_get_clean();
}
