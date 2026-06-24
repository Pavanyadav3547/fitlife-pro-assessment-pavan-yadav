<?php
/**
 * Gutenberg Blocks Integration for FitLife Core
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'fitlife_register_gutenberg_blocks' );

/**
 * Register Gutenberg Blocks & Patterns
 */
function fitlife_register_gutenberg_blocks() {
    // 1. Program Highlight (Static Block - Task 3.1)
    $ph_path = FITLIFE_CORE_PATH . 'build/program-highlight';
    if ( file_exists( $ph_path . '/block.json' ) ) {
        register_block_type( $ph_path );
    }

    // 2. Trainer Spotlight (Dynamic Block - Task 3.2)
    $ts_path = FITLIFE_CORE_PATH . 'build/trainer-spotlight';
    if ( file_exists( $ts_path . '/block.json' ) ) {
        register_block_type( $ts_path, array(
            'render_callback' => 'fitlife_render_trainer_spotlight_block',
        ) );
    }

    // Register Block Patterns (Task 3.3)
    if ( function_exists( 'register_block_pattern' ) ) {
        // Pattern 1: Hero Banner
        register_block_pattern(
            'fitlife/hero-banner',
            array(
                'title'       => __( 'FitLife Hero Banner', 'fitlife' ),
                'description' => _x( 'A premium hero banner with background gradient, title, details, and buttons.', 'Block pattern description', 'fitlife' ),
                'categories'  => array( 'header', 'columns' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"color":{"background":"#0f172a"}},"layout":{"type":"constrained"}} -->
                                  <div class="wp-block-group alignfull has-background" style="background-color:#0f172a; padding: 80px 20px;">
                                      <!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
                                      <div class="wp-block-columns alignwide are-vertically-aligned-center">
                                          <!-- wp:column {"width":"50%"} -->
                                          <div class="wp-block-column" style="flex-basis:50%">
                                              <!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"3.5rem","fontWeight":"800"}}} -->
                                              <h1 class="wp-block-heading" style="font-size:3.5rem;font-weight:800;line-height:1.2;color:#ffffff;margin-bottom:20px;">Transform Your Body & Mind</h1>
                                              <!-- /wp:heading -->
                                              <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.15rem"}},"textColor":"slate-400"} -->
                                              <p class="has-slate-400-color" style="font-size:1.15rem;color:#94a3b8;margin-bottom:30px;">Access custom training plans, professional diets, and elite training coordinators. Train at home or at the gym.</p>
                                              <!-- /wp:paragraph -->
                                              <!-- wp:buttons -->
                                              <div class="wp-block-buttons">
                                                  <!-- wp:button {"style":{"border":{"radius":"4px"}},"backgroundColor":"emerald-500"} -->
                                                  <div class="wp-block-button"><a class="wp-block-button__link has-background has-emerald-500-background-color wp-element-button" style="border-radius:4px;font-weight:bold;padding:12px 30px;" href="/trainers/">Find a Trainer</a></div>
                                                  <!-- /wp:button -->
                                              </div>
                                              <!-- /wp:buttons -->
                                          </div>
                                          <!-- /wp:column -->
                                          <!-- wp:column {"width":"50%"} -->
                                          <div class="wp-block-column" style="flex-basis:50%">
                                              <!-- wp:image -->
                                              <figure class="wp-block-image"><img src="" alt="Hero Visual"/></figure>
                                              <!-- /wp:image -->
                                          </div>
                                          <!-- /wp:column -->
                                      </div>
                                      <!-- /wp:columns -->
                                  </div>
                                  <!-- /wp:group -->',
            )
        );

        // Pattern 2: Trainer Grid Pattern
        register_block_pattern(
            'fitlife/trainer-grid-pattern',
            array(
                'title'       => __( 'FitLife Trainer Grid Section', 'fitlife' ),
                'description' => _x( 'Trainer grid overview with details.', 'Block pattern description', 'fitlife' ),
                'categories'  => array( 'query', 'columns' ),
                'content'     => '<!-- wp:group {"layout":{"type":"constrained"}} -->
                                  <div class="wp-block-group">
                                      <!-- wp:heading {"textAlign":"center","level":2} -->
                                      <h2 class="wp-block-heading has-text-align-center">Meet Our Instructors</h2>
                                      <!-- /wp:heading -->
                                      <!-- wp:paragraph {"textAlign":"center"} -->
                                      <p class="has-text-align-center">Expert personal trainers ready to guide you.</p>
                                      <!-- /wp:paragraph -->
                                      <!-- wp:shortcode -->
                                      [fitlife_trainers limit="3"]
                                      <!-- /wp:shortcode -->
                                  </div>
                                  <!-- /wp:group -->',
            )
        );
    }
}

/**
 * Render Callback for Trainer Spotlight Block (Dynamic Block - Task 3.2)
 */
function fitlife_render_trainer_spotlight_block( $attributes ) {
    if ( empty( $attributes['trainerId'] ) ) {
        return '<p class="fitlife-spotlight-error">' . esc_html__( 'Please select a trainer in the editor.', 'fitlife' ) . '</p>';
    }

    $trainer_id = intval( $attributes['trainerId'] );
    
    // Fetch trainer post
    $trainer = get_post( $trainer_id );
    if ( ! $trainer || 'fitlife_trainer' !== $trainer->post_type || 'publish' !== $trainer->post_status ) {
        return '<p class="fitlife-spotlight-error">' . esc_html__( 'Trainer not found or inactive.', 'fitlife' ) . '</p>';
    }

    // Retrieve CPT metadata (Task 2.2)
    $certification = get_post_meta( $trainer_id, '_fitlife_trainer_certification', true );
    $specialties   = get_the_terms( $trainer_id, 'specialty' );
    $specialty_names = array();
    if ( $specialties && ! is_wp_error( $specialties ) ) {
        foreach ( $specialties as $specialty ) {
            $specialty_names[] = $specialty->name;
        }
    }
    $specialty_str = ! empty( $specialty_names ) ? implode( ', ', $specialty_names ) : esc_html__( 'Personal Trainer', 'fitlife' );

    // Enqueue block specific styling dynamically if needed
    ob_start();
    ?>
    <div class="fitlife-trainer-spotlight-banner" style="display:flex; flex-wrap:wrap; align-items:center; gap:var(--space-md); padding:var(--space-lg); background:linear-gradient(135deg, var(--color-bg-card) 0%, rgba(15,23,42,0.9) 100%); border-radius:var(--radius-lg); border:1px solid rgba(255,255,255,0.06); margin-bottom:var(--space-md);">
        <div class="spotlight-image" style="flex: 0 0 150px; max-width: 150px;">
            <?php if ( has_post_thumbnail( $trainer_id ) ) : ?>
                <?php echo get_the_post_thumbnail( $trainer_id, 'thumbnail', array( 'style' => 'width:150px; height:150px; border-radius:50%; object-fit:cover; border:3px solid var(--color-brand-primary);' ) ); ?>
            <?php else : ?>
                <div style="width:150px; height:150px; border-radius:50%; background:var(--color-bg-dark); display:flex; align-items:center; justify-content:center; font-size:4rem; color:var(--color-brand-primary); border:3px solid var(--color-brand-primary);">
                    <i class="fa-solid fa-user"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="spotlight-content" style="flex:1; min-width:250px;">
            <span class="badge" style="margin-bottom:var(--space-xs); display:inline-block;"><?php esc_html_e( 'Trainer Spotlight', 'fitlife' ); ?></span>
            <h3 style="font-size:1.75rem; margin-bottom:4px; color:var(--color-white);"><?php echo esc_html( $trainer->post_title ); ?></h3>
            <p style="font-weight:600; color:var(--color-brand-primary); margin-bottom:var(--space-xs);"><?php echo esc_html( $specialty_str ); ?></p>
            <?php if ( $certification ) : ?>
                <p style="font-size:0.9rem; color:var(--color-text-muted); margin-bottom:var(--space-sm);"><i class="fa-solid fa-certificate" style="color:var(--color-accent);"></i> <?php echo esc_html( $certification ); ?></p>
            <?php endif; ?>
            <p style="color:var(--color-text-muted); font-size:0.95rem; margin-bottom:var(--space-md);"><?php echo wp_trim_words( $trainer->post_excerpt, 20 ); ?></p>
            <a href="<?php echo esc_url( get_permalink( $trainer_id ) ); ?>" class="cta-button" style="padding:8px 20px; font-size:0.9rem;">
                <?php esc_html_e( 'Book Now with', 'fitlife' ); ?> <?php echo esc_html( $trainer->post_title ); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Enqueue Block Variation Script for Gutenberg Editor (Task 3.3)
 */
function fitlife_enqueue_block_variations() {
    // Explicitly enqueue block editor scripts and styles to ensure they load in the inserter
    wp_enqueue_script( 'fitlife-program-highlight-editor-script' );
    wp_enqueue_style( 'fitlife-program-highlight-editor-style' );
    wp_enqueue_style( 'fitlife-program-highlight-style' );
    
    wp_enqueue_script( 'fitlife-trainer-spotlight-editor-script' );
    wp_enqueue_style( 'fitlife-trainer-spotlight-editor-style' );
    wp_enqueue_style( 'fitlife-trainer-spotlight-style' );

    wp_enqueue_script(
        'fitlife-block-variations',
        FITLIFE_CORE_URL . 'assets/js/editor-variations.js',
        array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
        '1.0.0',
        true
    );
}
add_action( 'enqueue_block_editor_assets', 'fitlife_enqueue_block_variations' );

/**
 * Fix junction resolution path issues for enqueued block assets on Windows hosts (Task 3.3 / Bugfix)
 */
function fitlife_fix_junction_assets_url( $src, $handle ) {
    if ( strpos( $handle, 'fitlife' ) !== false ) {
        // Replace absolute Windows paths appended to plugins_url due to junction resolution
        $src = preg_replace( '/wp-content\/plugins\/[a-zA-Z]:\/.*?\/build\//i', 'wp-content/plugins/fitlife-core/build/', $src );
    }
    return $src;
}
add_filter( 'script_loader_src', 'fitlife_fix_junction_assets_url', 99, 2 );
add_filter( 'style_loader_src', 'fitlife_fix_junction_assets_url', 99, 2 );
