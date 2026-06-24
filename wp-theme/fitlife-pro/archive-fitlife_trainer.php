<?php
/**
 * The template for displaying FitLife Trainers Archive (Full width, no sidebar layout)
 */

get_header();

// Fetch all Specialty taxonomy terms for the filter bar
$specialties = get_terms( array(
    'taxonomy'   => 'specialty',
    'hide_empty' => true,
) );

// Get current filter from query string (if any)
$current_specialty = isset( $_GET['specialty_filter'] ) ? sanitize_text_field( $_GET['specialty_filter'] ) : '';
?>

<section class="hero-section trainer-directory-banner" style="background: radial-gradient(circle at 10% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 60%); text-align:center; padding: var(--space-lg) 0;">
    <div class="container">
        <span class="badge" style="margin-bottom:var(--space-xs); display:inline-block;"><?php esc_html_e( 'Our Team', 'fitlife' ); ?></span>
        <h1 style="font-size:3rem; margin-bottom:var(--space-xs);"><?php esc_html_e( 'Our Expert Trainers', 'fitlife' ); ?></h1>
        <p style="color:var(--color-text-muted); max-width:600px; margin:0 auto; font-size:1.1rem;">
            <?php esc_html_e( 'Learn from certified coaches, dietitians, and fitness specialists dedicated to your transformation.', 'fitlife' ); ?>
        </p>
    </div>
</section>

<main id="primary-content" class="container" style="padding-top: var(--space-lg);">
    <!-- Filter Bar -->
    <div class="filter-bar">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'fitlife_trainer' ) ); ?>" class="filter-btn <?php echo empty($current_specialty) ? 'active' : ''; ?>">
            <?php esc_html_e( 'All Specialties', 'fitlife' ); ?>
        </a>
        <?php foreach ( $specialties as $specialty ) : ?>
            <?php 
            $filter_url = add_query_arg( 'specialty_filter', $specialty->slug, get_post_type_archive_link( 'fitlife_trainer' ) );
            $active_class = ( $current_specialty === $specialty->slug ) ? 'active' : '';
            ?>
            <a href="<?php echo esc_url( $filter_url ); ?>" class="filter-btn <?php echo esc_attr( $active_class ); ?>">
                <?php echo esc_html( $specialty->name ); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Trainers Grid (Full Width, No Sidebar) -->
    <?php if ( have_posts() ) : ?>
        <div class="grid-layout">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/card', 'trainer' ); ?>
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
        <p style="text-align:center; color:var(--color-text-muted);"><?php esc_html_e( 'No trainers found with the selected specialty.', 'fitlife' ); ?></p>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="cta-banner" style="margin-top: var(--space-xl); margin-bottom: var(--space-lg); padding: var(--space-xl) var(--space-md); background: linear-gradient(135deg, var(--color-bg-card) 0%, rgba(15,23,42,0.8) 100%); border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.06); text-align:center;">
        <h2 style="font-size:2rem; margin-bottom:var(--space-xs);"><?php esc_html_e( 'Not sure who to choose?', 'fitlife' ); ?></h2>
        <p style="color:var(--color-text-muted); max-width:550px; margin:0 auto var(--space-md);"><?php esc_html_e( 'Get in touch with our team and we will pair you with a trainer that matches your fitness goals and schedule.', 'fitlife' ); ?></p>
        <a href="mailto:<?php echo esc_attr( get_option('fitlife_contact_email', 'contact@fitlifepro.com') ); ?>" class="cta-button">
            <?php esc_html_e( 'Contact Our Coordinator', 'fitlife' ); ?>
        </a>
    </section>
</main>

<?php
get_footer();
