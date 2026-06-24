<?php
/**
 * The template for displaying FitLife Programs Archive
 */

get_header();

// Fetch all Program Type taxonomy terms for the filter bar
$program_types = get_terms( array(
    'taxonomy'   => 'program_type',
    'hide_empty' => true,
) );

// Get current filter from query string (if any)
$current_type = isset( $_GET['program_type_filter'] ) ? sanitize_text_field( $_GET['program_type_filter'] ) : '';
?>

<section class="hero-section program-directory-banner" style="background: radial-gradient(circle at 10% 80%, rgba(5, 150, 105, 0.08) 0%, transparent 60%); text-align:center; padding: var(--space-lg) 0;">
    <div class="container">
        <span class="badge" style="margin-bottom:var(--space-xs); display:inline-block;"><?php esc_html_e( 'Our Fitness Plans', 'fitlife' ); ?></span>
        <h1 style="font-size:3rem; margin-bottom:var(--space-xs);"><?php esc_html_e( 'Fitness & Workout Programs', 'fitlife' ); ?></h1>
        <p style="color:var(--color-text-muted); max-width:600px; margin:0 auto; font-size:1.1rem;">
            <?php esc_html_e( 'Choose from a variety of expert-designed training programs tailored to build muscle, drop fat, or improve athletic mobility.', 'fitlife' ); ?>
        </p>
    </div>
</section>

<main id="primary-content" class="container" style="padding-top: var(--space-lg);">
    <!-- Filter Bar (Task 1.2 / Task 1.4) -->
    <div class="filter-bar">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'fitlife_program' ) ); ?>" class="filter-btn <?php echo empty($current_type) ? 'active' : ''; ?>">
            <?php esc_html_e( 'All Programs', 'fitlife' ); ?>
        </a>
        <?php foreach ( $program_types as $type ) : ?>
            <?php 
            $filter_url = add_query_arg( 'program_type_filter', $type->slug, get_post_type_archive_link( 'fitlife_program' ) );
            $active_class = ( $current_type === $type->slug ) ? 'active' : '';
            ?>
            <a href="<?php echo esc_url( $filter_url ); ?>" class="filter-btn <?php echo esc_attr( $active_class ); ?>">
                <?php echo esc_html( $type->name ); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Programs Grid -->
    <?php if ( have_posts() ) : ?>
        <div class="grid-layout">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/card', 'program' ); ?>
            <?php endwhile; ?>
        </div>

        <!-- Pagination Links -->
        <div style="margin-top: var(--space-lg); text-align: center;">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( '&laquo; Back', 'fitlife' ),
                'next_text' => __( 'Next &raquo;', 'fitlife' ),
            ) );
            ?>
        </div>
    <?php else : ?>
        <p style="text-align:center; color:var(--color-text-muted);"><?php esc_html_e( 'No programs found with the selected category.', 'fitlife' ); ?></p>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="cta-banner" style="margin-top: var(--space-xl); margin-bottom: var(--space-lg); padding: var(--space-xl) var(--space-md); background: linear-gradient(135deg, var(--color-bg-card) 0%, rgba(248,250,252,0.8) 100%); border-radius: var(--radius-lg); border: 1px solid rgba(15,23,42,0.06); text-align:center; box-shadow: var(--shadow-premium);">
        <h2 style="font-size:2rem; margin-bottom:var(--space-xs);"><?php esc_html_e( 'Looking for a custom plan?', 'fitlife' ); ?></h2>
        <p style="color:var(--color-text-muted); max-width:550px; margin:0 auto var(--space-md);"><?php esc_html_e( 'Get paired with one of our coaches to build a personalized workout and nutrition regimen specific to your body type.', 'fitlife' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/trainers/' ) ); ?>" class="cta-button">
            <?php esc_html_e( 'Find Your Coach', 'fitlife' ); ?>
        </a>
    </section>
</main>

<?php
get_footer();
