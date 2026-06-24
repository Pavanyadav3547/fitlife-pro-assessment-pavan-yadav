<?php
get_header();
?>

<main id="primary-content" class="container">
    <div class="main-grid">
        <div class="content-area">
            <?php if ( have_posts() ) : ?>
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Latest News', 'fitlife' ); ?></h1>
                </header>

                <div class="blog-posts">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'fitlife-card' ); ?> style="margin-bottom: var(--space-md); padding: var(--space-md);">
                            <header class="entry-header">
                                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <div class="entry-meta" style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: var(--space-xs);">
                                    <?php the_time( get_option( 'date_format' ) ); ?> | <?php the_author(); ?>
                                </div>
                            </header>

                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="post-thumbnail" style="margin-bottom: var(--space-sm);">
                                    <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto; border-radius: var(--radius-sm);' ) ); ?>
                                </div>
                            <?php endif; ?>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>

                    <?php the_posts_navigation(); ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'No content found.', 'fitlife' ); ?></p>
            <?php endif; ?>
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
