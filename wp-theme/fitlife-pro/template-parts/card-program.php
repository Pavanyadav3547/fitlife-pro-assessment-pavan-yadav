<?php
/**
 * Template part for rendering a fitness program card
 */
$program_id = get_the_ID();
$types = get_the_terms( $program_id, 'program_type' );
$type_list = array();
if ( ! empty( $types ) && ! is_wp_error( $types ) ) {
    foreach ( $types as $type ) {
        $type_list[] = $type->name;
    }
}
$type_str = ! empty( $type_list ) ? implode( ', ', $type_list ) : esc_html__( 'General Training', 'fitlife' );

$duration = get_post_meta( $program_id, '_fitlife_program_duration', true );
$difficulty = get_post_meta( $program_id, '_fitlife_program_difficulty', true );
$equipment = get_post_meta( $program_id, '_fitlife_program_equipment', true );
?>

<div class="fitlife-card program-card" id="program-<?php echo esc_attr( $program_id ); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium', array( 'class' => 'fitlife-card-img', 'alt' => get_the_title(), 'loading' => 'lazy' ) ); ?>
        </a>
    <?php else : ?>
        <div class="fitlife-card-img" style="display:flex; align-items:center; justify-content:center; background:var(--color-bg-card); font-size:3rem; color:var(--color-brand-primary);">
            <i class="fa-solid fa-dumbbell"></i>
        </div>
    <?php endif; ?>

    <div class="fitlife-card-content">
        <div class="fitlife-card-meta"><?php echo esc_html( $type_str ); ?></div>
        <h3 class="fitlife-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        
        <p class="fitlife-card-desc"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
        
        <div style="font-size:0.85rem; color:var(--color-text-muted); margin-bottom:var(--space-sm); display:flex; flex-wrap:wrap; gap:10px;">
            <span><i class="fa-solid fa-calendar-days" style="margin-right:4px;"></i> <?php echo esc_html( $duration ? $duration : '0' ); ?> weeks</span>
            <?php if ( $equipment ) : ?>
                <span><i class="fa-solid fa-toolbox" style="margin-right:4px;"></i> <?php echo esc_html( $equipment ); ?></span>
            <?php endif; ?>
        </div>

        <div class="fitlife-card-footer">
            <span class="badge"><?php echo esc_html( $difficulty ? $difficulty : 'Beginner' ); ?></span>
            <a href="<?php the_permalink(); ?>" class="cta-button" style="padding: 6px 12px; font-size:0.85rem; box-shadow:none;">
                <?php esc_html_e( 'Learn More', 'fitlife' ); ?><span class="screen-reader-text"> <?php echo esc_html__( 'about', 'fitlife' ) . ' ' . get_the_title(); ?></span>
            </a>
        </div>
    </div>
</div>
