<?php
/**
 * REST API Extensions for FitLife Core
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'rest_api_init', 'fitlife_register_rest_routes' );

/**
 * Register REST API Routes
 */
function fitlife_register_rest_routes() {
    $namespace = 'fitlife/v1';

    // GET /wp-json/fitlife/v1/trainers
    register_rest_route( $namespace, '/trainers', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'fitlife_rest_get_trainers',
        'permission_callback' => '__return_true', // Publicly readable
    ) );

    // GET /wp-json/fitlife/v1/programs
    register_rest_route( $namespace, '/programs', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'fitlife_rest_get_programs',
        'permission_callback' => '__return_true', // Publicly readable
    ) );

    // POST /wp-json/fitlife/v1/programs (Create Program)
    register_rest_route( $namespace, '/programs', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'fitlife_rest_create_program',
        'permission_callback' => 'fitlife_rest_create_program_permission', // Authenticated (edit_posts capability)
    ) );
}

/**
 * GET trainers callback
 */
function fitlife_rest_get_trainers( $request ) {
    $specialty = $request->get_param( 'specialty' );

    $args = array(
        'post_type'      => 'fitlife_trainer',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );

    if ( ! empty( $specialty ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'specialty',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $specialty ),
            ),
        );
    }

    $query = new WP_Query( $args );
    $trainers = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();

            // Fetch terms
            $terms = get_the_terms( $id, 'specialty' );
            $specialty_names = array();
            if ( $terms && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $specialty_names[] = $term->name;
                }
            }

            // Fetch photo
            $photo_url = get_the_post_thumbnail_url( $id, 'medium' );

            $trainers[] = array(
                'id'            => $id,
                'name'          => get_the_title(),
                'specialty'     => implode( ', ', $specialty_names ),
                'certification' => get_post_meta( $id, '_fitlife_trainer_certification', true ),
                'experience'    => get_post_meta( $id, '_fitlife_trainer_experience', true ),
                'hourly_rate'   => get_post_meta( $id, '_fitlife_trainer_hourly_rate', true ),
                'instagram'     => get_post_meta( $id, '_fitlife_trainer_instagram', true ),
                'youtube'       => get_post_meta( $id, '_fitlife_trainer_youtube', true ),
                'photo_url'     => $photo_url ? $photo_url : '',
                'link'          => get_permalink(),
            );
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response( $trainers, 200 );
}

/**
 * GET programs callback
 */
function fitlife_rest_get_programs( $request ) {
    $type       = $request->get_param( 'type' );
    $difficulty = $request->get_param( 'difficulty' );

    $args = array(
        'post_type'      => 'fitlife_program',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );

    $tax_query = array();
    if ( ! empty( $type ) ) {
        $tax_query[] = array(
            'taxonomy' => 'program_type',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $type ),
        );
    }
    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $meta_query = array();
    if ( ! empty( $difficulty ) ) {
        $meta_query[] = array(
            'key'     => '_fitlife_program_difficulty',
            'value'   => sanitize_text_field( $difficulty ),
            'compare' => '=',
        );
    }
    if ( ! empty( $meta_query ) ) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query( $args );
    $programs = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();

            // Fetch terms
            $terms = get_the_terms( $id, 'program_type' );
            $type_names = array();
            if ( $terms && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $type_names[] = $term->name;
                }
            }

            $programs[] = array(
                'id'               => $id,
                'title'            => get_the_title(),
                'description'      => get_the_content(),
                'type'             => implode( ', ', $type_names ),
                'duration_weeks'   => get_post_meta( $id, '_fitlife_program_duration', true ),
                'difficulty_level' => get_post_meta( $id, '_fitlife_program_difficulty', true ),
                'equipment'        => get_post_meta( $id, '_fitlife_program_equipment', true ),
                'max_participants' => get_post_meta( $id, '_fitlife_program_max_participants', true ),
                'photo_url'        => get_the_post_thumbnail_url( $id, 'medium' ) ? get_the_post_thumbnail_url( $id, 'medium' ) : '',
                'link'             => get_permalink(),
            );
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response( $programs, 200 );
}

/**
 * POST create program callback permission check
 */
function fitlife_rest_create_program_permission() {
    // Only users with edit_posts capability can access this endpoint
    return current_user_can( 'edit_posts' );
}

/**
 * POST create program callback (Task 2.3)
 */
function fitlife_rest_create_program( $request ) {
    $params = $request->get_params();

    // Check required fields
    if ( empty( $params['title'] ) ) {
        return new WP_Error( 'missing_title', __( 'Program title is required', 'fitlife' ), array( 'status' => 400 ) );
    }

    // Prepare and insert post
    $post_data = array(
        'post_title'   => sanitize_text_field( $params['title'] ),
        'post_content' => ! empty( $params['description'] ) ? wp_kses_post( $params['description'] ) : '',
        'post_status'  => 'publish',
        'post_type'    => 'fitlife_program',
    );

    $post_id = wp_insert_post( $post_data );

    if ( is_wp_error( $post_id ) ) {
        return new WP_Error( 'db_error', __( 'Could not create program', 'fitlife' ), array( 'status' => 500 ) );
    }

    // Save meta fields
    if ( isset( $params['duration_weeks'] ) ) {
        update_post_meta( $post_id, '_fitlife_program_duration', absint( $params['duration_weeks'] ) );
    }
    if ( isset( $params['difficulty_level'] ) ) {
        update_post_meta( $post_id, '_fitlife_program_difficulty', sanitize_text_field( $params['difficulty_level'] ) );
    }
    if ( isset( $params['equipment'] ) ) {
        update_post_meta( $post_id, '_fitlife_program_equipment', sanitize_text_field( $params['equipment'] ) );
    }
    if ( isset( $params['max_participants'] ) ) {
        update_post_meta( $post_id, '_fitlife_program_max_participants', absint( $params['max_participants'] ) );
    }

    // Set Program Type taxonomy if slug is passed
    if ( ! empty( $params['type'] ) ) {
        $term = get_term_by( 'slug', sanitize_text_field( $params['type'] ), 'program_type' );
        if ( $term ) {
            wp_set_object_terms( $post_id, $term->term_id, 'program_type' );
        }
    }

    // Clear Home Cache
    delete_transient( 'fitlife_home_programs' );

    return new WP_REST_Response( array(
        'id'      => $post_id,
        'message' => __( 'Program created successfully', 'fitlife' ),
        'link'    => get_permalink( $post_id ),
    ), 201 );
}
