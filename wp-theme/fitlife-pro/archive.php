<?php
get_header();
?>

<main id="primary-content" class="container" style="padding-top: var(--space-lg);">
    <div class="main-grid">
        <div class="content-area">
            <?php if ( have_posts() ) : ?>
                <header class="page-header" style="margin-bottom: var(--space-lg);">
                    <h1 class="page-title"><?php the_archive_title(); ?></h1>
                    <?php the_archive_description( '<div class="taxonomy-description" style="color: var(--color-text-muted);">', '</div>' ); ?>
                </header>

                <div class="grid-layout">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php
                        // Check if CPT is fitlife_trainer or fitlife_program
                        if ( get_post_type() === 'fitlife_trainer' ) {
                            get_template_part( 'template-parts/card', 'trainer' );
                        } elseif ( get_post_type() === 'fitlife_program' ) {
                            get_template_part( 'template-parts/card', 'program' );
                        } else {
                            // Standard layout fallback
                            ?>
                            <div class="fitlife-card">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'fitlife-card-img' ) ); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="fitlife-card-content">
                                    <h2 class="fitlife-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <p class="fitlife-card-desc"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                    <div class="fitlife-card-footer">
                                        <span><?php the_time( get_option( 'date_format' ) ); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    <?php endwhile; ?>
                </div>

                <?php the_posts_navigation(); ?>
            <?php else : ?>
                <p><?php esc_html_e( 'No archives found.', 'fitlife' ); ?></p>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
