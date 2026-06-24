<?php
/**
 * WooCommerce Single Product template override.
 * Renders the product details with custom wrapper styling.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<main id="primary-content" class="container shop-container" style="padding-top: var(--space-lg); padding-bottom: var(--space-xl);">
    <div class="fitlife-woocommerce-wrapper" style="background: var(--color-bg-card); border: 1px solid rgba(255,255,255,0.06); border-radius: var(--radius-lg); padding: var(--space-md); margin-bottom: var(--space-lg);">
        <?php
            /**
             * woocommerce_before_main_content hook.
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action( 'woocommerce_before_main_content' );
        ?>

        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>

            <?php wc_get_template_part( 'content', 'single-product' ); ?>

        <?php endwhile; // end of the loop. ?>

        <?php
            /**
             * woocommerce_after_main_content hook.
             *
             * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
             */
            do_action( 'woocommerce_after_main_content' );
        ?>
    </div>
</main>

<?php
get_footer( 'shop' );
