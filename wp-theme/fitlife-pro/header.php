<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $meta_desc = get_bloginfo( 'description' );
    if ( empty( $meta_desc ) || 'Just another WordPress site' === $meta_desc ) {
        $meta_desc = esc_html__( 'FitLife Pro provides customized athletic conditioning, sports nutrition coaching, and dedicated training programs designed by certified coaches.', 'fitlife' );
    }
    ?>
    <meta name="description" content="<?php echo esc_attr( $meta_desc ); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip to Main Content Link for WCAG AA compliance -->
<a href="#primary-content" class="skip-link"><?php esc_html_e( 'Skip to content', 'fitlife' ); ?></a>

<header id="masthead" class="site-header" role="banner">
    <div class="container header-container">
        <div class="logo">
            <?php
            if ( has_custom_logo() ) {
                the_custom_logo();
            } else {
                echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
                echo 'FitLife<span>Pro</span>';
                echo '</a>';
            }
            ?>
        </div>

        <button class="mobile-nav-toggle" aria-controls="primary-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'fitlife' ); ?>">
            <i class="fa-solid fa-bars"></i>
        </button>

        <nav id="primary-navigation" class="primary-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'fitlife' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'primary-nav-ul',
                'fallback_cb'    => false,
                'walker'         => new FitLife_Walker_Nav_Menu(),
                'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
            ) );
            ?>
        </nav>
    </div>
</header>

<div id="content" class="site-content">
