<?php
/**
 * The template for displaying the blog page (Posts Index)
 */

get_header();
?>

<section class="hero-section blog-directory-banner" style="background: radial-gradient(circle at 10% 80%, rgba(8, 145, 178, 0.08) 0%, transparent 60%); text-align:center; padding: var(--space-lg) 0;">
    <div class="container">
        <span class="badge" style="margin-bottom:var(--space-xs); display:inline-block;"><?php esc_html_e( 'Stay Informed', 'fitlife' ); ?></span>
        <h1 style="font-size:3rem; margin-bottom:var(--space-xs);"><?php esc_html_e( 'FitLife Blog & Guides', 'fitlife' ); ?></h1>
        <p style="color:var(--color-text-muted); max-width:600px; margin:0 auto; font-size:1.1rem;">
            <?php esc_html_e( 'Get the latest tips, tricks, and scientific research on training, nutrition, and mental performance.', 'fitlife' ); ?>
        </p>
    </div>
</section>

<main id="primary-content" class="container" style="padding-top: var(--space-lg);">
    <div class="main-grid">
        <div class="content-area">
            <?php if ( have_posts() ) : ?>
                <div class="blog-layout">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" class="blog-post-card">
                            <div style="height: 100%; min-height: 200px;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium_large', array( 'class' => 'post-img', 'alt' => get_the_title() ) ); ?>
                                <?php else : ?>
                                    <div class="post-img" style="display:flex; align-items:center; justify-content:center; font-size:3rem; color:var(--color-brand-primary); height: 100%;">
                                        <i class="fa-solid fa-feather-pointed"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="post-details">
                                <div class="fitlife-card-meta">
                                    <?php the_category( ', ' ); ?> &bull; <?php the_time( get_option( 'date_format' ) ); ?>
                                </div>
                                <h2 style="font-size: 1.5rem; margin-bottom: var(--space-xs);"><a href="<?php the_permalink(); ?>" style="color: var(--color-text-primary);"><?php the_title(); ?></a></h2>
                                <p style="color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: var(--space-md);">
                                    <?php echo wp_trim_words( get_the_excerpt(), 25 ); ?>
                                </p>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <span style="font-size: 0.85rem; color: var(--color-text-muted);"><?php esc_html_e( 'By', 'fitlife' ); ?> <strong><?php the_author(); ?></strong></span>
                                    <a href="<?php the_permalink(); ?>" class="cta-button" style="padding: 6px 14px; font-size:0.85rem; box-shadow:none;">
                                        <?php esc_html_e( 'Read Article', 'fitlife' ); ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div style="margin-top: var(--space-lg);">
                    <?php the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => __( 'Back', 'fitlife' ),
                        'next_text' => __( 'Next', 'fitlife' ),
                    ) ); ?>
                </div>
            <?php else : ?>
                <div style="padding: var(--space-lg); background: var(--color-bg-card); border-radius: var(--radius-md); text-align: center; border: 1px solid rgba(15,23,42,0.06);">
                    <p style="color: var(--color-text-muted);"><?php esc_html_e( 'No blog posts published yet.', 'fitlife' ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
