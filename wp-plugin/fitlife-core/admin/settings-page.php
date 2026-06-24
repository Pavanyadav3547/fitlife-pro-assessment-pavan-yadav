<?php
/**
 * FitLife Admin Settings Page
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'fitlife_add_settings_menu' );
add_action( 'admin_init', 'fitlife_settings_init' );

/**
 * Add settings page under Settings > FitLife Settings
 */
function fitlife_add_settings_menu() {
    add_options_page(
        __( 'FitLife Settings', 'fitlife' ),
        __( 'FitLife Settings', 'fitlife' ),
        'manage_options',
        'fitlife-settings',
        'fitlife_settings_page_html'
    );
}

/**
 * Settings initialization
 */
function fitlife_settings_init() {
    // Register Settings
    register_setting( 'fitlife_settings_group', 'fitlife_brand_color', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'default'           => '#10b981',
    ) );
    register_setting( 'fitlife_settings_group', 'fitlife_contact_email', array(
        'sanitize_callback' => 'sanitize_email',
        'default'           => 'contact@fitlifepro.com',
    ) );
    register_setting( 'fitlife_settings_group', 'fitlife_programs_per_page', array(
        'sanitize_callback' => 'absint',
        'default'           => 6,
    ) );
    register_setting( 'fitlife_settings_group', 'fitlife_enable_reviews', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'yes',
    ) );

    // Add Section
    add_settings_section(
        'fitlife_general_section',
        __( 'General Configurations', 'fitlife' ),
        'fitlife_general_section_callback',
        'fitlife-settings'
    );

    // Add Fields
    add_settings_field(
        'fitlife_brand_color_field',
        __( 'Brand Accent Color', 'fitlife' ),
        'fitlife_brand_color_callback',
        'fitlife-settings',
        'fitlife_general_section'
    );

    add_settings_field(
        'fitlife_contact_email_field',
        __( 'Contact Email Address', 'fitlife' ),
        'fitlife_contact_email_callback',
        'fitlife-settings',
        'fitlife_general_section'
    );

    add_settings_field(
        'fitlife_programs_per_page_field',
        __( 'Programs Per Page', 'fitlife' ),
        'fitlife_programs_per_page_callback',
        'fitlife-settings',
        'fitlife_general_section'
    );

    add_settings_field(
        'fitlife_enable_reviews_field',
        __( 'Enable Trainer & Program Reviews', 'fitlife' ),
        'fitlife_enable_reviews_callback',
        'fitlife-settings',
        'fitlife_general_section'
    );
}

/**
 * Section Callback
 */
function fitlife_general_section_callback() {
    echo '<p>' . esc_html__( 'Configure global brand preferences and configuration defaults for the FitLife Pro website.', 'fitlife' ) . '</p>';
}

/**
 * Brand Color field Callback
 */
function fitlife_brand_color_callback() {
    $color = get_option( 'fitlife_brand_color', '#10b981' );
    echo '<input type="color" id="fitlife_brand_color" name="fitlife_brand_color" value="' . esc_attr( $color ) . '">';
    echo '<p class="description">' . esc_html__( 'Select the theme accent color used for buttons, badges, links, and highlights.', 'fitlife' ) . '</p>';
}

/**
 * Contact Email field Callback
 */
function fitlife_contact_email_callback() {
    $email = get_option( 'fitlife_contact_email', 'contact@fitlifepro.com' );
    echo '<input type="email" id="fitlife_contact_email" name="fitlife_contact_email" value="' . esc_attr( $email ) . '" class="regular-text">';
    echo '<p class="description">' . esc_html__( 'Used for trainer coordinator mailto links and site inquiries.', 'fitlife' ) . '</p>';
}

/**
 * Programs Per Page field Callback
 */
function fitlife_programs_per_page_callback() {
    $count = get_option( 'fitlife_programs_per_page', 6 );
    echo '<input type="number" min="1" max="100" id="fitlife_programs_per_page" name="fitlife_programs_per_page" value="' . esc_attr( $count ) . '" class="small-text">';
    echo '<p class="description">' . esc_html__( 'Default number of Fitness Programs shown in shortcode grids.', 'fitlife' ) . '</p>';
}

/**
 * Reviews Toggle field Callback
 */
function fitlife_enable_reviews_callback() {
    $enabled = get_option( 'fitlife_enable_reviews', 'yes' );
    ?>
    <label for="fitlife_enable_reviews">
        <input type="checkbox" id="fitlife_enable_reviews" name="fitlife_enable_reviews" value="yes" <?php checked( $enabled, 'yes' ); ?>>
        <?php esc_html_e( 'Yes, enable reviews and rating options', 'fitlife' ); ?>
    </label>
    <?php
}

/**
 * Settings Page HTML Rendering
 */
function fitlife_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'fitlife_settings_group' );
            do_settings_sections( 'fitlife-settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
