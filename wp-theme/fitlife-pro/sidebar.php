<aside id="secondary" class="sidebar widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'fitlife' ); ?>">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php else : ?>
        <?php if ( ! is_shop() && ! is_product() && ! is_cart() && ! is_checkout() ) : ?>
            <div class="widget">
                <h3 class="widget-title"><?php esc_html_e( 'Search Site', 'fitlife' ); ?></h3>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
        <?php if ( ! is_shop() && ! is_product() && ! is_cart() && ! is_checkout() ) : ?>
            <div class="widget">
                <h3 class="widget-title"><?php esc_html_e( 'About FitLife Pro', 'fitlife' ); ?></h3>
                <p><?php esc_html_e( 'We build customized training solutions to help you unlock your best performance.', 'fitlife' ); ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</aside>
