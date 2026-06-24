<?php
/**
 * Custom Meta Boxes for Trainers and Programs CPTs
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Hook to register metaboxes
add_action( 'add_meta_boxes', 'fitlife_add_custom_meta_boxes' );
// Hook to save metabox data
add_action( 'save_post', 'fitlife_save_custom_meta_boxes_data' );

/**
 * Register the meta boxes
 */
function fitlife_add_custom_meta_boxes() {
    // 1. Trainer Profile Meta Box
    add_meta_box(
        'fitlife_trainer_details_metabox',
        __( 'Trainer Profile Details', 'fitlife' ),
        'fitlife_trainer_metabox_html_callback',
        'fitlife_trainer',
        'normal',
        'high'
    );

    // 2. Program Details Meta Box
    add_meta_box(
        'fitlife_program_details_metabox',
        __( 'Program Specifications', 'fitlife' ),
        'fitlife_program_metabox_html_callback',
        'fitlife_program',
        'normal',
        'high'
    );
}

/**
 * Trainer Metabox HTML Callback
 */
function fitlife_trainer_metabox_html_callback( $post ) {
    // Nonce field for security verification
    wp_nonce_field( 'fitlife_trainer_meta_save', 'fitlife_trainer_meta_nonce' );

    // Retrieve existing values
    $certification = get_post_meta( $post->ID, '_fitlife_trainer_certification', true );
    $experience    = get_post_meta( $post->ID, '_fitlife_trainer_experience', true );
    $hourly_rate   = get_post_meta( $post->ID, '_fitlife_trainer_hourly_rate', true );
    $instagram     = get_post_meta( $post->ID, '_fitlife_trainer_instagram', true );
    $youtube       = get_post_meta( $post->ID, '_fitlife_trainer_youtube', true );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="fitlife_trainer_certification"><?php esc_html_e( 'Certification', 'fitlife' ); ?></label></th>
            <td>
                <input type="text" id="fitlife_trainer_certification" name="fitlife_trainer_certification" value="<?php echo esc_attr( $certification ); ?>" class="regular-text" placeholder="e.g. NASM-CPT, CrossFit L2">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="fitlife_trainer_experience"><?php esc_html_e( 'Years of Experience', 'fitlife' ); ?></label></th>
            <td>
                <input type="number" min="0" max="80" id="fitlife_trainer_experience" name="fitlife_trainer_experience" value="<?php echo esc_attr( $experience ); ?>" class="small-text"> <?php esc_html_e( 'years', 'fitlife' ); ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="fitlife_trainer_hourly_rate"><?php esc_html_e( 'Hourly Rate ($)', 'fitlife' ); ?></label></th>
            <td>
                <input type="number" min="0" step="any" id="fitlife_trainer_hourly_rate" name="fitlife_trainer_hourly_rate" value="<?php echo esc_attr( $hourly_rate ); ?>" class="small-text"> <?php esc_html_e( 'USD / hour', 'fitlife' ); ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="fitlife_trainer_instagram"><?php esc_html_e( 'Instagram Profile URL', 'fitlife' ); ?></label></th>
            <td>
                <input type="url" id="fitlife_trainer_instagram" name="fitlife_trainer_instagram" value="<?php echo esc_url( $instagram ); ?>" class="regular-text" placeholder="https://instagram.com/username">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="fitlife_trainer_youtube"><?php esc_html_e( 'YouTube Channel URL', 'fitlife' ); ?></label></th>
            <td>
                <input type="url" id="fitlife_trainer_youtube" name="fitlife_trainer_youtube" value="<?php echo esc_url( $youtube ); ?>" class="regular-text" placeholder="https://youtube.com/c/channelname">
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Program Metabox HTML Callback
 */
function fitlife_program_metabox_html_callback( $post ) {
    // Nonce field for security verification
    wp_nonce_field( 'fitlife_program_meta_save', 'fitlife_program_meta_nonce' );

    // Retrieve existing values
    $duration         = get_post_meta( $post->ID, '_fitlife_program_duration', true );
    $difficulty       = get_post_meta( $post->ID, '_fitlife_program_difficulty', true );
    $equipment        = get_post_meta( $post->ID, '_fitlife_program_equipment', true );
    $max_participants = get_post_meta( $post->ID, '_fitlife_program_max_participants', true );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="fitlife_program_duration"><?php esc_html_e( 'Duration (weeks)', 'fitlife' ); ?></label></th>
            <td>
                <input type="number" min="1" max="52" id="fitlife_program_duration" name="fitlife_program_duration" value="<?php echo esc_attr( $duration ); ?>" class="small-text"> <?php esc_html_e( 'weeks', 'fitlife' ); ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="fitlife_program_difficulty"><?php esc_html_e( 'Difficulty Level', 'fitlife' ); ?></label></th>
            <td>
                <select id="fitlife_program_difficulty" name="fitlife_program_difficulty">
                    <option value="Beginner" <?php selected( $difficulty, 'Beginner' ); ?>><?php esc_html_e( 'Beginner', 'fitlife' ); ?></option>
                    <option value="Intermediate" <?php selected( $difficulty, 'Intermediate' ); ?>><?php esc_html_e( 'Intermediate', 'fitlife' ); ?></option>
                    <option value="Advanced" <?php selected( $difficulty, 'Advanced' ); ?>><?php esc_html_e( 'Advanced', 'fitlife' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="fitlife_program_equipment"><?php esc_html_e( 'Equipment Required', 'fitlife' ); ?></label></th>
            <td>
                <input type="text" id="fitlife_program_equipment" name="fitlife_program_equipment" value="<?php echo esc_attr( $equipment ); ?>" class="regular-text" placeholder="e.g. Kettlebells, Dumbbells, Pullup bar, None">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="fitlife_program_max_participants"><?php esc_html_e( 'Maximum Participants', 'fitlife' ); ?></label></th>
            <td>
                <input type="number" min="1" id="fitlife_program_max_participants" name="fitlife_program_max_participants" value="<?php echo esc_attr( $max_participants ); ?>" class="small-text"> <?php esc_html_e( 'clients (leave blank for unlimited)', 'fitlife' ); ?>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save metabox data with validation and sanitation
 */
function fitlife_save_custom_meta_boxes_data( $post_id ) {
    // 1. Save Trainer Meta Box Data
    if ( isset( $_POST['fitlife_trainer_meta_nonce'] ) ) {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['fitlife_trainer_meta_nonce'], 'fitlife_trainer_meta_save' ) ) {
            return;
        }

        // Check user capability
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Sanitize and save fields
        if ( isset( $_POST['fitlife_trainer_certification'] ) ) {
            update_post_meta( $post_id, '_fitlife_trainer_certification', sanitize_text_field( $_POST['fitlife_trainer_certification'] ) );
        }
        if ( isset( $_POST['fitlife_trainer_experience'] ) ) {
            update_post_meta( $post_id, '_fitlife_trainer_experience', absint( $_POST['fitlife_trainer_experience'] ) );
        }
        if ( isset( $_POST['fitlife_trainer_hourly_rate'] ) ) {
            $rate = floatval( $_POST['fitlife_trainer_hourly_rate'] );
            update_post_meta( $post_id, '_fitlife_trainer_hourly_rate', $rate >= 0 ? $rate : 0 );
        }
        if ( isset( $_POST['fitlife_trainer_instagram'] ) ) {
            update_post_meta( $post_id, '_fitlife_trainer_instagram', esc_url_raw( $_POST['fitlife_trainer_instagram'] ) );
        }
        if ( isset( $_POST['fitlife_trainer_youtube'] ) ) {
            update_post_meta( $post_id, '_fitlife_trainer_youtube', esc_url_raw( $_POST['fitlife_trainer_youtube'] ) );
        }
        
        // Delete transient home cache to refresh page updates
        delete_transient( 'fitlife_home_trainers' );
        delete_transient( 'fitlife_trainers_query_all' );
    }

    // 2. Save Program Meta Box Data
    if ( isset( $_POST['fitlife_program_meta_nonce'] ) ) {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['fitlife_program_meta_nonce'], 'fitlife_program_meta_save' ) ) {
            return;
        }

        // Check user capability
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Sanitize and save fields
        if ( isset( $_POST['fitlife_program_duration'] ) ) {
            update_post_meta( $post_id, '_fitlife_program_duration', absint( $_POST['fitlife_program_duration'] ) );
        }
        if ( isset( $_POST['fitlife_program_difficulty'] ) ) {
            $allowed = array( 'Beginner', 'Intermediate', 'Advanced' );
            $difficulty = sanitize_text_field( $_POST['fitlife_program_difficulty'] );
            if ( in_array( $difficulty, $allowed ) ) {
                update_post_meta( $post_id, '_fitlife_program_difficulty', $difficulty );
            }
        }
        if ( isset( $_POST['fitlife_program_equipment'] ) ) {
            update_post_meta( $post_id, '_fitlife_program_equipment', sanitize_text_field( $_POST['fitlife_program_equipment'] ) );
        }
        if ( isset( $_POST['fitlife_program_max_participants'] ) ) {
            update_post_meta( $post_id, '_fitlife_program_max_participants', absint( $_POST['fitlife_program_max_participants'] ) );
        }
        
        // Delete transient cache
        delete_transient( 'fitlife_home_programs' );
    }
}
