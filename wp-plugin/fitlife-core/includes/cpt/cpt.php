<?php
/**
 * Register Custom Post Types & Taxonomies for FitLife
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Hook registration functions
add_action( 'init', 'fitlife_register_cpts' );
add_action( 'init', 'fitlife_register_taxonomies' );

/**
 * Register CPTs
 */
function fitlife_register_cpts() {
    // 1. fitlife_trainer (Trainer Profiles)
    register_post_type( 'fitlife_trainer', array(
        'labels'             => array(
            'name'               => _x( 'Trainers', 'post type general name', 'fitlife' ),
            'singular_name'      => _x( 'Trainer', 'post type singular name', 'fitlife' ),
            'menu_name'          => _x( 'Trainers', 'admin menu', 'fitlife' ),
            'name_admin_bar'     => _x( 'Trainer', 'add new on admin bar', 'fitlife' ),
            'add_new'            => _x( 'Add New', 'trainer', 'fitlife' ),
            'add_new_item'       => __( 'Add New Trainer', 'fitlife' ),
            'new_item'           => __( 'New Trainer', 'fitlife' ),
            'edit_item'          => __( 'Edit Trainer', 'fitlife' ),
            'view_item'          => __( 'View Trainer', 'fitlife' ),
            'all_items'          => __( 'All Trainers', 'fitlife' ),
            'search_items'       => __( 'Search Trainers', 'fitlife' ),
            'parent_item_colon'  => __( 'Parent Trainers:', 'fitlife' ),
            'not_found'          => __( 'No trainers found.', 'fitlife' ),
            'not_found_in_trash' => __( 'No trainers found in Trash.', 'fitlife' ),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'trainers' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-businessman',
        'show_in_rest'       => true, // Required for REST API (Task 2.3) and Gutenberg Block editor (Task 3.2)
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    ) );

    // 2. fitlife_program (Fitness Programs)
    register_post_type( 'fitlife_program', array(
        'labels'             => array(
            'name'               => _x( 'Programs', 'post type general name', 'fitlife' ),
            'singular_name'      => _x( 'Program', 'post type singular name', 'fitlife' ),
            'menu_name'          => _x( 'Programs', 'admin menu', 'fitlife' ),
            'name_admin_bar'     => _x( 'Program', 'add new on admin bar', 'fitlife' ),
            'add_new'            => _x( 'Add New', 'program', 'fitlife' ),
            'add_new_item'       => __( 'Add New Program', 'fitlife' ),
            'new_item'           => __( 'New Program', 'fitlife' ),
            'edit_item'          => __( 'Edit Program', 'fitlife' ),
            'view_item'          => __( 'View Program', 'fitlife' ),
            'all_items'          => __( 'All Programs', 'fitlife' ),
            'search_items'       => __( 'Search Programs', 'fitlife' ),
            'parent_item_colon'  => __( 'Parent Programs:', 'fitlife' ),
            'not_found'          => __( 'No programs found.', 'fitlife' ),
            'not_found_in_trash' => __( 'No programs found in Trash.', 'fitlife' ),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'programs' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'show_in_rest'       => true, // Required for REST API (Task 2.3) and Gutenberg Block editor (Task 3.1)
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    ) );
}

/**
 * Register Taxonomies
 */
function fitlife_register_taxonomies() {
    // Specialty for Trainers CPT
    register_taxonomy( 'specialty', array( 'fitlife_trainer' ), array(
        'labels'            => array(
            'name'              => _x( 'Specialties', 'taxonomy general name', 'fitlife' ),
            'singular_name'     => _x( 'Specialty', 'taxonomy singular name', 'fitlife' ),
            'search_items'      => __( 'Search Specialties', 'fitlife' ),
            'all_items'         => __( 'All Specialties', 'fitlife' ),
            'parent_item'       => __( 'Parent Specialty', 'fitlife' ),
            'parent_item_colon' => __( 'Parent Specialty:', 'fitlife' ),
            'edit_item'         => __( 'Edit Specialty', 'fitlife' ),
            'update_item'       => __( 'Update Specialty', 'fitlife' ),
            'add_new_item'      => __( 'Add New Specialty', 'fitlife' ),
            'new_item_name'     => __( 'New Specialty Name', 'fitlife' ),
            'menu_name'         => __( 'Specialties', 'fitlife' ),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'specialty' ),
        'show_in_rest'      => true, // Required for REST API
    ) );

    // Program Type for Programs CPT
    register_taxonomy( 'program_type', array( 'fitlife_program' ), array(
        'labels'            => array(
            'name'              => _x( 'Program Types', 'taxonomy general name', 'fitlife' ),
            'singular_name'     => _x( 'Program Type', 'taxonomy singular name', 'fitlife' ),
            'search_items'      => __( 'Search Program Types', 'fitlife' ),
            'all_items'         => __( 'All Program Types', 'fitlife' ),
            'parent_item'       => __( 'Parent Program Type', 'fitlife' ),
            'parent_item_colon' => __( 'Parent Program Type:', 'fitlife' ),
            'edit_item'         => __( 'Edit Program Type', 'fitlife' ),
            'update_item'       => __( 'Update Program Type', 'fitlife' ),
            'add_new_item'      => __( 'Add New Program Type', 'fitlife' ),
            'new_item_name'     => __( 'New Program Type Name', 'fitlife' ),
            'menu_name'         => __( 'Program Types', 'fitlife' ),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'program-type' ),
        'show_in_rest'      => true, // Required for REST API
    ) );
}

/**
 * Task 2.1 - Add Custom Columns in Admin List View
 */

// Custom columns for Trainers list
add_filter( 'manage_fitlife_trainer_posts_columns', 'fitlife_set_trainer_columns' );
add_action( 'manage_fitlife_trainer_posts_custom_column', 'fitlife_fill_trainer_columns', 10, 2 );

function fitlife_set_trainer_columns( $columns ) {
    // Add columns after title
    $new_columns = array();
    foreach ( $columns as $key => $title ) {
        $new_columns[$key] = $title;
        if ( 'title' === $key ) {
            $new_columns['specialty_tax'] = __( 'Specialties', 'fitlife' );
            $new_columns['certification'] = __( 'Certification', 'fitlife' );
            $new_columns['experience']    = __( 'Experience (Yrs)', 'fitlife' );
            $new_columns['hourly_rate']   = __( 'Hourly Rate', 'fitlife' );
        }
    }
    return $new_columns;
}

function fitlife_fill_trainer_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'specialty_tax':
            $terms = get_the_term_list( $post_id, 'specialty', '', ', ', '' );
            if ( is_string( $terms ) ) {
                echo wp_kses_post( $terms );
            } else {
                echo '<span class="na">&ndash;</span>';
            }
            break;
        case 'certification':
            $cert = get_post_meta( $post_id, '_fitlife_trainer_certification', true );
            echo esc_html( $cert ? $cert : '&ndash;' );
            break;
        case 'experience':
            $exp = get_post_meta( $post_id, '_fitlife_trainer_experience', true );
            echo esc_html( $exp ? $exp . ' yrs' : '&ndash;' );
            break;
        case 'hourly_rate':
            $rate = get_post_meta( $post_id, '_fitlife_trainer_hourly_rate', true );
            echo esc_html( $rate ? '$' . $rate . '/hr' : '&ndash;' );
            break;
    }
}

// Custom columns for Programs list
add_filter( 'manage_fitlife_program_posts_columns', 'fitlife_set_program_columns' );
add_action( 'manage_fitlife_program_posts_custom_column', 'fitlife_fill_program_columns', 10, 2 );

function fitlife_set_program_columns( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $title ) {
        $new_columns[$key] = $title;
        if ( 'title' === $key ) {
            $new_columns['program_type_tax'] = __( 'Program Types', 'fitlife' );
            $new_columns['duration']         = __( 'Duration', 'fitlife' );
            $new_columns['difficulty']       = __( 'Difficulty', 'fitlife' );
            $new_columns['max_participants'] = __( 'Max Clients', 'fitlife' );
        }
    }
    return $new_columns;
}

function fitlife_fill_program_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'program_type_tax':
            $terms = get_the_term_list( $post_id, 'program_type', '', ', ', '' );
            if ( is_string( $terms ) ) {
                echo wp_kses_post( $terms );
            } else {
                echo '<span class="na">&ndash;</span>';
            }
            break;
        case 'duration':
            $dur = get_post_meta( $post_id, '_fitlife_program_duration', true );
            echo esc_html( $dur ? $dur . ' weeks' : '&ndash;' );
            break;
        case 'difficulty':
            $diff = get_post_meta( $post_id, '_fitlife_program_difficulty', true );
            echo esc_html( $diff ? $diff : '&ndash;' );
            break;
        case 'max_participants':
            $max = get_post_meta( $post_id, '_fitlife_program_max_participants', true );
            echo esc_html( $max ? $max : '&ndash;' );
            break;
    }
}
