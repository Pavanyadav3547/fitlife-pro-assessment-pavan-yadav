<?php
/**
 * FitLife Pro Theme Functions
 */

if ( ! function_exists( 'fitlife_setup' ) ) {
    function fitlife_setup() {
        // Add theme support
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'custom-logo', array(
            'height'      => 80,
            'width'       => 240,
            'flex-width'  => true,
            'flex-height' => true,
        ) );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );

        // Enable menus support explicitly
        add_theme_support( 'menus' );

        // Register Navigation Menus
        register_nav_menus( array(
            'primary' => __( 'Primary Nav', 'fitlife' ),
            'footer'  => __( 'Footer Nav', 'fitlife' ),
        ) );

        // Support WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}
add_action( 'after_setup_theme', 'fitlife_setup' );

/**
 * Enqueue scripts and styles.
 */
function fitlife_scripts() {
    // Google Fonts - Outfit and Inter
    wp_enqueue_style( 'fitlife-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap', array(), null );

    // Theme Main Stylesheet
    wp_enqueue_style( 'fitlife-style', get_stylesheet_uri(), array( 'fitlife-fonts' ), '1.0.0' );

    // Enqueue FontAwesome for icons (useful for walker and buttons)
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

    // Theme Navigation JS (Deferred for performance)
    wp_enqueue_script( 'fitlife-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'fitlife_scripts' );

/**
 * Defer non-critical scripts for Performance Optimization (Task 5.1)
 */
function fitlife_defer_scripts( $tag, $handle, $src ) {
    $defer_scripts = array( 'fitlife-navigation', 'font-awesome' );
    if ( in_array( $handle, $defer_scripts ) ) {
        return '<script src="' . esc_url( $src ) . '" defer></script>' . "\n";
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'fitlife_defer_scripts', 10, 3 );

/**
 * Custom Walker Nav Menu Class for accessible menus (Task 1.3)
 */
class FitLife_Walker_Nav_Menu extends Walker_Nav_Menu {

    // Start Level (opening sub-menu ul)
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul class=\"sub-menu\" role=\"menu\" aria-label=\"" . esc_attr__( 'Sub Menu', 'fitlife' ) . "\">\n";
    }

    // Start Element (opening li and anchor)
    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        // In WP 4.4+, $data_object is WP_Post (WP_MenuItem)
        $menu_item = $data_object;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
        $classes[] = 'menu-item-' . $menu_item->ID;

        // Check if item has children
        $has_children = false;
        if ( isset( $args->walker->has_children ) && $args->walker->has_children ) {
            $has_children = true;
            $classes[] = 'menu-item-has-children';
        }

        // Active class logic
        if ( in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-ancestor', $classes ) ) {
            $classes[] = 'active';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $menu_item->ID, $menu_item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . ' role="none">';

        $atts = array();
        $atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
        $atts['target'] = ! empty( $menu_item->target )     ? $menu_item->target     : '';
        $atts['rel']    = ! empty( $menu_item->xfn )        ? $menu_item->xfn        : '';
        $atts['href']   = ! empty( $menu_item->url )        ? $menu_item->url        : '';

        // Accessibility attributes for parent items
        if ( $has_children ) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
        $title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' role="menuitem">';
        $item_output .= $args->link_before . $title . $args->link_after;
        
        // Add dropdown arrow icon if item has children
        if ( $has_children ) {
            $item_output .= ' <span class="dropdown-toggle-icon" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>';
        }
        
        $item_output .= '</a>';
        
        // Output dropdown toggle button for screen reader keyboards if top level
        if ( $has_children && $depth === 0 ) {
            $item_output .= '<button class="dropdown-toggle-btn screen-reader-text" aria-expanded="false" style="position: absolute; width: 1px; height: 1px; padding: 0; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0;">' . sprintf( esc_html__( 'Toggle submenu for %s', 'fitlife' ), $title ) . '</button>';
        }

        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
    }
}

/**
 * Clean up header scripts and styles (Performance Optimization)
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'shortlink_link' );

/**
 * Dynamic content helper for active brand colors (Task 2.4)
 */
function fitlife_customizer_css() {
    $brand_color = get_option( 'fitlife_brand_color', '#10b981' );
    if ( $brand_color !== '#10b981' ) {
        ?>
        <style type="text/css">
            :root {
                --color-brand-primary: <?php echo esc_html( $brand_color ); ?>;
                --color-brand-secondary: <?php echo esc_html( $brand_color ); ?>bb;
            }
            .cta-button {
                box-shadow: 0 4px 14px <?php echo esc_html( $brand_color ); ?>66;
            }
        </style>
        <?php
    }
}
add_action( 'wp_head', 'fitlife_customizer_css' );

/**
 * Optimize archive queries using pre_get_posts (Task 5.1 / User request)
 */
function fitlife_optimize_archive_queries( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // Program Archive Query Optimization
    if ( $query->is_post_type_archive( 'fitlife_program' ) || $query->is_tax( 'program_type' ) ) {
        $limit = get_option( 'fitlife_programs_per_page', 6 );
        $query->set( 'posts_per_page', intval( $limit ) );

        $filter = isset( $_GET['program_type_filter'] ) ? sanitize_text_field( $_GET['program_type_filter'] ) : '';
        if ( ! empty( $filter ) ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'program_type',
                    'field'    => 'slug',
                    'terms'    => $filter,
                )
            ) );
        }
    }

    // Trainer Archive Query Optimization (Remove sidebar support, allow URL filter)
    if ( $query->is_post_type_archive( 'fitlife_trainer' ) || $query->is_tax( 'specialty' ) ) {
        $filter = isset( $_GET['specialty_filter'] ) ? sanitize_text_field( $_GET['specialty_filter'] ) : '';
        if ( ! empty( $filter ) ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'specialty',
                    'field'    => 'slug',
                    'terms'    => $filter,
                )
            ) );
        }
    }
}
add_action( 'pre_get_posts', 'fitlife_optimize_archive_queries' );

/**
 * Register sidebar widget area (Primary Sidebar)
 */
function fitlife_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Primary Sidebar', 'fitlife' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'fitlife' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'fitlife_widgets_init' );

/**
 * Add custom fallback favicon to wp_head
 */
function fitlife_add_favicon() {
    if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
        $favicon_url = get_template_directory_uri() . '/assets/images/favicon.png';
        echo '<link rel="shortcut icon" href="' . esc_url( $favicon_url ) . '" type="image/png" />' . "\n";
        echo '<link rel="apple-touch-icon" href="' . esc_url( $favicon_url ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'fitlife_add_favicon' );

/**
 * Highlight custom post type menu items on CPT single and archive pages
 */
function fitlife_nav_menu_classes( $classes, $item, $args, $depth ) {
    $is_trainer_page = is_post_type_archive( 'fitlife_trainer' ) || is_singular( 'fitlife_trainer' ) || is_page( 'trainers' );
    $is_program_page = is_post_type_archive( 'fitlife_program' ) || is_singular( 'fitlife_program' ) || is_page( 'programs' );

    $url_path = parse_url( $item->url, PHP_URL_PATH );
    $url_path = rtrim( $url_path, '/' );

    $ends_with_trainers = preg_match( '#/trainers$#', $url_path );
    $ends_with_programs = preg_match( '#/programs$#', $url_path );

    if ( $is_trainer_page && $ends_with_trainers ) {
        $classes[] = 'current-menu-item';
        $classes[] = 'active';
    }

    if ( $is_program_page && $ends_with_programs ) {
        $classes[] = 'current-menu-item';
        $classes[] = 'active';
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'fitlife_nav_menu_classes', 10, 4 );



