<?php
get_header();
?>

<main id="primary-content" class="container" style="padding-top: var(--space-lg);">
    <div class="main-grid">
        <div class="content-area">
            <?php if ( have_posts() ) : ?>
                <header class="page-header" style="margin-bottom: var(--space-lg);">
                    <h1 class="page-title">
                        <?php 
                        printf( 
                            esc_html__( 'Search Results for: %s', 'fitlife' ), 
                            '<span style="color:var(--color-brand-primary);">' . get_search_query() . '</span>' 
                        ); 
                        ?>
                    </h1>
                </header>

                <div class="blog-posts">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'fitlife-card' ); ?> style="margin-bottom: var(--space-md); padding: var(--space-md);">
                            <header class="entry-header">
                                <h2 class="entry-title" style="font-size:1.5rem; margin-bottom:var(--space-xs);"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <span style="font-size:0.85rem; color:var(--color-brand-primary); text-transform:uppercase; font-weight:600;">
                                    <?php echo esc_html( get_post_type() ); ?>
                                </span>
                            </header>
                            <div class="entry-summary" style="margin-top: var(--space-xs);">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>

                    <?php the_posts_navigation(); ?>
                </div>
            <?php else : ?>
                <header class="page-header" style="margin-bottom: var(--space-lg);">
                    <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'fitlife' ); ?></h1>
                </header>
                <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'fitlife' ); ?></p>
                <div style="margin-top: var(--space-md);">
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
