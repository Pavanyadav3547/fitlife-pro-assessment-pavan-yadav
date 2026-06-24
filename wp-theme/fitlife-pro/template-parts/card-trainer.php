<?php
/**
 * Template part for rendering a trainer profile card
 */
$trainer_id = get_the_ID();
$specialties = get_the_terms( $trainer_id, 'specialty' );
$specialty_list = array();
if ( ! empty( $specialties ) && ! is_wp_error( $specialties ) ) {
    foreach ( $specialties as $specialty ) {
        $specialty_list[] = $specialty->name;
    }
}
$specialty_str = ! empty( $specialty_list ) ? implode( ', ', $specialty_list ) : esc_html__( 'General Fitness', 'fitlife' );

$certification = get_post_meta( $trainer_id, '_fitlife_trainer_certification', true );
$experience = get_post_meta( $trainer_id, '_fitlife_trainer_experience', true );
$hourly_rate = get_post_meta( $trainer_id, '_fitlife_trainer_hourly_rate', true );
?>

<div class="fitlife-card trainer-card" id="trainer-<?php echo esc_attr( $trainer_id ); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium', array( 'class' => 'fitlife-card-img', 'alt' => get_the_title(), 'loading' => 'lazy' ) ); ?>
        </a>
    <?php else : ?>
        <div class="fitlife-card-img" style="display:flex; align-items:center; justify-content:center; background:var(--color-bg-card); font-size:3rem; color:var(--color-brand-primary);">
            <i class="fa-solid fa-user-tie"></i>
        </div>
    <?php endif; ?>

    <div class="fitlife-card-content">
        <div class="fitlife-card-meta"><?php echo esc_html( $specialty_str ); ?></div>
        <h3 class="fitlife-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        
        <?php if ( $certification ) : ?>
            <p style="font-size:0.85rem; color:var(--color-text-muted); margin-bottom:4px;">
                <i class="fa-solid fa-certificate" style="color:var(--color-accent); margin-right:4px;"></i> <?php echo esc_html( $certification ); ?>
            </p>
        <?php endif; ?>

        <?php if ( $experience ) : ?>
            <p style="font-size:0.85rem; color:var(--color-text-muted); margin-bottom:var(--space-sm);">
                <i class="fa-solid fa-clock" style="margin-right:4px;"></i> <?php echo sprintf( esc_html__( '%s years experience', 'fitlife' ), esc_html( $experience ) ); ?>
            </p>
        <?php endif; ?>

        <p class="fitlife-card-desc"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
        
        <div class="fitlife-card-footer">
            <span style="font-weight:700; color:var(--color-white);">$<?php echo esc_html( $hourly_rate ? $hourly_rate : '0' ); ?>/hr</span>
            <a href="<?php the_permalink(); ?>" class="cta-button" style="padding: 6px 12px; font-size:0.85rem; box-shadow:none;">
                <?php esc_html_e( 'Book Now', 'fitlife' ); ?><span class="screen-reader-text"> <?php echo esc_html__( 'with', 'fitlife' ) . ' ' . get_the_title(); ?></span>
            </a>
        </div>
    </div>
</div>
