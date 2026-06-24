<?php
get_header();
?>

<main id="primary-content" class="container" style="padding: var(--space-xl) var(--space-sm); text-align: center;">
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="font-size: 6rem; color: var(--color-brand-primary); margin-bottom: var(--space-xs);">404</h1>
        <h2 style="font-size: 2rem; margin-bottom: var(--space-sm);"><?php esc_html_e( 'Page Not Found', 'fitlife' ); ?></h2>
        <p style="color: var(--color-text-muted); margin-bottom: var(--space-lg);">
            <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'fitlife' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cta-button">
            <?php esc_html_e( 'Go to Homepage', 'fitlife' ); ?>
        </a>
    </div>
</main>

<?php
get_footer();
