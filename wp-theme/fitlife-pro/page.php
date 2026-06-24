<?php
get_header();
?>

<main id="primary-content" class="container">
    <div class="main-grid">
        <article class="content-area">
            <?php while ( have_posts() ) : the_post(); ?>
                <header class="page-header" style="margin-bottom: var(--space-md);">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="page-thumbnail" style="margin-bottom: var(--space-md);">
                        <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto; border-radius: var(--radius-md);' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="page-content entry-content">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>
        </article>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
