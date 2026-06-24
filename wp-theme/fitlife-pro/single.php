<?php
get_header();
?>

<main id="primary-content" class="container" style="padding-top: var(--space-lg);">
    <div class="main-grid">
        <article class="content-area">
            <?php while ( have_posts() ) : the_post(); ?>
                <header class="post-header" style="margin-bottom: var(--space-md);">
                    <?php if ( get_post_type() === 'post' ) : ?>
                        <div class="post-category" style="color: var(--color-brand-primary); font-weight:600; text-transform:uppercase;">
                            <?php the_category(', '); ?>
                        </div>
                    <?php endif; ?>
                    <h1 class="post-title" style="font-size:2.5rem; margin-top:var(--space-xs);"><?php the_title(); ?></h1>
                    
                    <?php if ( get_post_type() === 'post' ) : ?>
                        <div class="post-meta" style="color: var(--color-text-muted); font-size:0.9rem;">
                            <?php the_time( get_option( 'date_format' ) ); ?> | <?php the_author(); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail" style="margin-bottom: var(--space-md);">
                        <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto; border-radius: var(--radius-md); max-height:450px; object-fit:cover;' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Custom Meta Fields Render for CPTs (Task 2.2) -->
                <?php if ( get_post_type() === 'fitlife_trainer' ) : ?>
                    <div class="cpt-meta-container" style="margin-top: var(--space-lg); padding: var(--space-md); background: var(--color-bg-card); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.06);">
                        <h2 style="font-size:1.5rem; margin-bottom:var(--space-sm); color: var(--color-brand-primary);"><?php esc_html_e('Trainer Profile Info', 'fitlife'); ?></h2>
                        <ul style="list-style:none; display:grid; grid-template-columns: 1fr 1fr; gap: var(--space-sm);">
                            <li><strong><?php esc_html_e('Certification:', 'fitlife'); ?></strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_fitlife_trainer_certification', true ) ); ?></li>
                            <li><strong><?php esc_html_e('Years of Experience:', 'fitlife'); ?></strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_fitlife_trainer_experience', true ) ); ?></li>
                            <li><strong><?php esc_html_e('Hourly Rate:', 'fitlife'); ?></strong> $<?php echo esc_html( get_post_meta( get_the_ID(), '_fitlife_trainer_hourly_rate', true ) ); ?> / hr</li>
                            <li>
                                <strong><?php esc_html_e('Social Links:', 'fitlife'); ?></strong>
                                <?php
                                $insta = get_post_meta( get_the_ID(), '_fitlife_trainer_instagram', true );
                                $yt = get_post_meta( get_the_ID(), '_fitlife_trainer_youtube', true );
                                if ( $insta ) echo '<a href="' . esc_url($insta) . '" target="_blank" style="margin-right:10px;"><i class="fa-brands fa-instagram"></i> Instagram</a>';
                                if ( $yt ) echo '<a href="' . esc_url($yt) . '" target="_blank"><i class="fa-brands fa-youtube"></i> YouTube</a>';
                                ?>
                            </li>
                        </ul>
                    </div>
                <?php elseif ( get_post_type() === 'fitlife_program' ) : ?>
                    <div class="cpt-meta-container" style="margin-top: var(--space-lg); padding: var(--space-md); background: var(--color-bg-card); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.06);">
                        <h2 style="font-size:1.5rem; margin-bottom:var(--space-sm); color: var(--color-brand-primary);"><?php esc_html_e('Program Specifications', 'fitlife'); ?></h2>
                        <ul style="list-style:none; display:grid; grid-template-columns: 1fr 1fr; gap: var(--space-sm);">
                            <li><strong><?php esc_html_e('Duration:', 'fitlife'); ?></strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_fitlife_program_duration', true ) ); ?> <?php esc_html_e('weeks', 'fitlife'); ?></li>
                            <li><strong><?php esc_html_e('Difficulty Level:', 'fitlife'); ?></strong> <span class="badge"><?php echo esc_html( get_post_meta( get_the_ID(), '_fitlife_program_difficulty', true ) ); ?></span></li>
                            <li><strong><?php esc_html_e('Equipment Required:', 'fitlife'); ?></strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_fitlife_program_equipment', true ) ); ?></li>
                            <li><strong><?php esc_html_e('Maximum Participants:', 'fitlife'); ?></strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_fitlife_program_max_participants', true ) ); ?></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Reviews / Comments section (Task 2.4 - Settings integration) -->
                <?php if ( comments_open() || get_comments_number() ) : ?>
                    <div class="reviews-container" style="margin-top: var(--space-lg); padding: var(--space-md); background: var(--color-bg-card); border-radius: var(--radius-md); border: 1px solid rgba(15,23,42,0.06); box-shadow: var(--shadow-sm);">
                        <h2 style="font-size:1.5rem; margin-bottom:var(--space-sm); color: var(--color-brand-primary);"><?php esc_html_e('Reviews & Ratings', 'fitlife'); ?></h2>
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>

            <?php endwhile; ?>
        </article>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
