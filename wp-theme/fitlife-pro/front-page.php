<?php
/**
 * Theme Front Page Template (Premium Light Theme Layout)
 */

get_header();

// Setup cached query for home trainers (Task 5.1 - 12 hours)
$home_trainers = get_transient( 'fitlife_home_trainers' );
if ( false === $home_trainers ) {
    $home_trainers = new WP_Query( array(
        'post_type'      => 'fitlife_trainer',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
    ) );
    set_transient( 'fitlife_home_trainers', $home_trainers, 12 * HOUR_IN_SECONDS );
}

// Setup cached query for home programs (Task 5.1 - 12 hours)
$home_programs = get_transient( 'fitlife_home_programs' );
if ( false === $home_programs ) {
    $home_programs = new WP_Query( array(
        'post_type'      => 'fitlife_program',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
    ) );
    set_transient( 'fitlife_home_programs', $home_programs, 12 * HOUR_IN_SECONDS );
}
?>

<?php
$hero_bg = content_url( '/uploads/2026/06/banner-image.png' );
?>
<!-- Hero Banner (Premium styling with CSS animation) -->
<section class="hero-section" style="background-image: linear-gradient(135deg, rgba(248, 250, 252, 0.92) 30%, rgba(248, 250, 252, 0.8) 100%), url('<?php echo esc_url( $hero_bg ); ?>');" aria-label="<?php esc_attr_e( 'Hero Banner', 'fitlife' ); ?>">
    <div class="container hero-grid">
        <div class="hero-content animate-fade-in">
            <span class="badge" style="margin-bottom: var(--space-xs); display: inline-block; background: rgba(5,150,105,0.08); color: var(--color-brand-primary);"><?php esc_html_e( 'Welcome to FitLife Pro', 'fitlife' ); ?></span>
            <h1 class="hero-title"><?php esc_html_e( 'Elevate Your Fitness & Transform Your Body', 'fitlife' ); ?></h1>
            <p class="hero-desc"><?php esc_html_e( 'Experience customized athletic conditioning, sports nutrition coaching, and dedicated training programs designed by certified fitness coaches.', 'fitlife' ); ?></p>
            <div style="display:flex; gap:15px; flex-wrap:wrap; margin-top: var(--space-md);">
                <a href="<?php echo esc_url( home_url( '/trainers/' ) ); ?>" class="cta-button">
                    <?php esc_html_e( 'Find Your Coach', 'fitlife' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>" class="cta-button" style="background:transparent; border:2px solid var(--color-brand-primary); color:var(--color-brand-primary); box-shadow:none;">
                    <?php esc_html_e( 'Explore Programs', 'fitlife' ); ?>
                </a>
            </div>
        </div>
        <div class="hero-image-container">
            <div style="width:100%; height:420px; background:linear-gradient(135deg, #ffffff 0%, rgba(5,150,105,0.1) 100%); border-radius:var(--radius-lg); border:1px solid rgba(15,23,42,0.06); display:flex; align-items:center; justify-content:center; flex-direction:column; gap:20px; box-shadow: var(--shadow-premium);">
                <i class="fa-solid fa-heart-pulse" style="font-size:5rem; color:var(--color-brand-primary); animation: heartBeat 2s infinite;"></i>
                <div style="text-align:center; padding: 0 var(--space-sm);">
                    <h3 style="margin: 0; font-size:1.4rem; color: var(--color-text-primary);"><?php esc_html_e( 'Active Training Hub', 'fitlife' ); ?></h3>
                    <p style="margin: 5px 0 0 0; color: var(--color-text-muted); font-size: 0.95rem;"><?php esc_html_e( 'Personalized plans & daily coach support', 'fitlife' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Features Section -->
<section class="features-section" style="padding: var(--space-xl) 0; background-color: #ffffff; border-bottom: 1px solid rgba(15,23,42,0.04);">
    <div class="container">
        <div class="section-header">
            <span class="badge"><?php esc_html_e( 'Why FitLife', 'fitlife' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Our Core Pillars', 'fitlife' ); ?></h2>
            <p class="section-subtitle"><?php esc_html_e( 'Everything you need to optimize physical capacity and build healthy habits.', 'fitlife' ); ?></p>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-md);">
            <div style="padding: var(--space-md); background: var(--color-bg-body); border-radius: var(--radius-md); text-align: center; border: 1px solid rgba(15,23,42,0.05); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: rgba(5,150,105,0.08); border-radius: 50%; display:flex; align-items:center; justify-content:center; margin: 0 auto var(--space-sm) auto; font-size: 1.5rem; color: var(--color-brand-primary);">
                    <i class="fa-solid fa-dumbbell"></i>
                </div>
                <h3><?php esc_html_e( 'Custom Workouts', 'fitlife' ); ?></h3>
                <p style="color:var(--color-text-muted); font-size:0.95rem;"><?php esc_html_e( 'Bespoke fitness structures designed around your posture, joints, and availability.', 'fitlife' ); ?></p>
            </div>

            <div style="padding: var(--space-md); background: var(--color-bg-body); border-radius: var(--radius-md); text-align: center; border: 1px solid rgba(15,23,42,0.05); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: rgba(5,150,105,0.08); border-radius: 50%; display:flex; align-items:center; justify-content:center; margin: 0 auto var(--space-sm) auto; font-size: 1.5rem; color: var(--color-brand-primary);">
                    <i class="fa-solid fa-apple-whole"></i>
                </div>
                <h3><?php esc_html_e( 'Nutrition Tracking', 'fitlife' ); ?></h3>
                <p style="color:var(--color-text-muted); font-size:0.95rem;"><?php esc_html_e( 'Registered sports dietitians to help you hit calories, macros, and micro-nutrient profiles.', 'fitlife' ); ?></p>
            </div>

            <div style="padding: var(--space-md); background: var(--color-bg-body); border-radius: var(--radius-md); text-align: center; border: 1px solid rgba(15,23,42,0.05); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 60px; height: 60px; background: rgba(5,150,105,0.08); border-radius: 50%; display:flex; align-items:center; justify-content:center; margin: 0 auto var(--space-sm) auto; font-size: 1.5rem; color: var(--color-brand-primary);">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3><?php esc_html_e( 'Expert Support', 'fitlife' ); ?></h3>
                <p style="color:var(--color-text-muted); font-size:0.95rem;"><?php esc_html_e( 'Direct, chat-based and video check-ins with your assigned coach to keep you accountable.', 'fitlife' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section class="programs-section" style="padding: var(--space-xl) 0; border-bottom: 1px solid rgba(15,23,42,0.04);" aria-label="<?php esc_attr_e( 'Fitness Programs', 'fitlife' ); ?>">
    <div class="container">
        <div class="section-header">
            <span class="badge"><?php esc_html_e( 'Curated Workouts', 'fitlife' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Featured Fitness Programs', 'fitlife' ); ?></h2>
            <p class="section-subtitle"><?php esc_html_e( 'Tailored training structures for all experience and capability levels.', 'fitlife' ); ?></p>
        </div>

        <?php if ( $home_programs->have_posts() ) : ?>
            <div class="grid-layout">
                <?php while ( $home_programs->have_posts() ) : $home_programs->the_post(); ?>
                    <?php get_template_part( 'template-parts/card', 'program' ); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div style="text-align:center;">
                <a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>" class="cta-button" style="box-shadow:none;">
                    <?php esc_html_e( 'View All Programs', 'fitlife' ); ?>
                </a>
            </div>
        <?php else : ?>
            <div style="text-align:center; padding:var(--space-lg); background:var(--color-bg-card); border-radius:var(--radius-md); color:var(--color-text-muted);">
                <p><?php esc_html_e( 'Please create Fitness Programs CPT entries to see them listed here.', 'fitlife' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Trainers Section -->
<section class="trainers-section" style="padding: var(--space-xl) 0; background-color: #ffffff;" aria-label="<?php esc_attr_e( 'Expert Trainers', 'fitlife' ); ?>">
    <div class="container">
        <div class="section-header">
            <span class="badge"><?php esc_html_e( 'World-Class Coaches', 'fitlife' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Meet Our Elite Trainers', 'fitlife' ); ?></h2>
            <p class="section-subtitle"><?php esc_html_e( 'Learn from certified trainers who specialize in weight loss, bodybuilding, and mobility.', 'fitlife' ); ?></p>
        </div>

        <?php if ( $home_trainers->have_posts() ) : ?>
            <div class="grid-layout">
                <?php while ( $home_trainers->have_posts() ) : $home_trainers->the_post(); ?>
                    <?php get_template_part( 'template-parts/card', 'trainer' ); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div style="text-align:center;">
                <a href="<?php echo esc_url( home_url( '/trainers/' ) ); ?>" class="cta-button" style="box-shadow:none;">
                    <?php esc_html_e( 'View All Coaches', 'fitlife' ); ?>
                </a>
            </div>
        <?php else : ?>
            <div style="text-align:center; padding:var(--space-lg); background:var(--color-bg-card); border-radius:var(--radius-md); color:var(--color-text-muted);">
                <p><?php esc_html_e( 'Create Trainer CPT entries to show them listed here.', 'fitlife' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Products Section -->
<section class="products-section" style="padding: var(--space-xl) 0; border-top: 1px solid rgba(15,23,42,0.04); background-color: var(--color-bg-body);" aria-label="<?php esc_attr_e( 'Featured Products', 'fitlife' ); ?>">
    <div class="container">
        <div class="section-header">
            <span class="badge"><?php esc_html_e( 'Shop Fitness', 'fitlife' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Featured Gear & Supplements', 'fitlife' ); ?></h2>
            <p class="section-subtitle"><?php esc_html_e( 'Explore our premium bundles and supplements to accelerate your fitness results.', 'fitlife' ); ?></p>
        </div>

        <?php
        $featured_products = new WP_Query( array(
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'post_status'    => 'publish',
        ) );

        if ( $featured_products->have_posts() ) :
            ?>
            <div class="woocommerce">
                <ul class="products columns-4">
                    <?php while ( $featured_products->have_posts() ) : $featured_products->the_post(); ?>
                        <?php wc_get_template_part( 'content', 'product' ); ?>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
            <div style="text-align:center; margin-top:var(--space-md);">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="cta-button" style="box-shadow:none;">
                    <?php esc_html_e( 'Visit Supplement Shop', 'fitlife' ); ?>
                </a>
            </div>
        <?php else : ?>
            <div style="text-align:center; padding:var(--space-lg); background:var(--color-bg-card); border-radius:var(--radius-md); color:var(--color-text-muted);">
                <p><?php esc_html_e( 'No products found. Please add products in WooCommerce dashboard to see them listed here.', 'fitlife' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
@keyframes heartBeat {
    0% { transform: scale(1); }
    14% { transform: scale(1.1); }
    28% { transform: scale(1); }
    42% { transform: scale(1.1); }
    70% { transform: scale(1); }
}
</style>

<?php
get_footer();

