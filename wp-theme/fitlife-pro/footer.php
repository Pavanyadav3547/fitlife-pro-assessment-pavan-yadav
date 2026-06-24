</div><!-- #content -->

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-columns">
            <div class="footer-column brand-column">
                <h3 class="footer-logo">FitLife<span>Pro</span></h3>
                <p><?php esc_html_e( 'Elevate your physical capacity, optimize your nutrition, and crush your goals with custom training regimens designed by world-class certified coaches.', 'fitlife' ); ?></p>
                <div class="footer-socials">
                    <a href="#" class="footer-social-link" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="footer-social-link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="footer-social-link" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-column link-column">
                <h3><?php esc_html_e( 'Quick Links', 'fitlife' ); ?></h3>
                <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'fitlife' ); ?>">
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'footer-nav-ul',
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ) );
                    } else {
                        echo '<ul class="footer-nav-ul">';
                        echo '<li><a href="' . esc_url( home_url( '/trainers/' ) ) . '">' . esc_html__( 'Our Coaches', 'fitlife' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/programs/' ) ) . '">' . esc_html__( 'Workout Programs', 'fitlife' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/shop/' ) ) . '">' . esc_html__( 'Online Shop', 'fitlife' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/blog/' ) ) . '">' . esc_html__( 'Fitness Blog', 'fitlife' ) . '</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </nav>
            </div>
            <div class="footer-column contact-column">
                <h3><?php esc_html_e( 'Get In Touch', 'fitlife' ); ?></h3>
                <ul class="footer-contact-list">
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <strong><?php esc_html_e( 'Email Support', 'fitlife' ); ?></strong><br>
                            <?php 
                            $contact_email = get_option( 'fitlife_contact_email', 'contact@fitlifepro.com' );
                            ?>
                            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <div>
                            <strong><?php esc_html_e( 'Call Us', 'fitlife' ); ?></strong><br>
                            <a href="tel:+18005553488">+1 (800) 555-3488</a>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <div>
                            <strong><?php esc_html_e( 'Headquarters', 'fitlife' ); ?></strong><br>
                            <?php esc_html_e( '100 Fitness Plaza, Suite 400, Los Angeles, CA 90025', 'fitlife' ); ?>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="footer-column newsletter-column">
                <h3><?php esc_html_e( 'Newsletter', 'fitlife' ); ?></h3>
                <p><?php esc_html_e( 'Subscribe to receive elite training guides, athletic drills, and seasonal discount drops.', 'fitlife' ); ?></p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing!'); this.reset();">
                    <input type="email" placeholder="<?php esc_attr_e( 'Enter your email', 'fitlife' ); ?>" class="newsletter-input" required aria-label="<?php esc_attr_e( 'Email address', 'fitlife' ); ?>">
                    <button type="submit" class="newsletter-submit"><?php esc_html_e( 'Subscribe Now', 'fitlife' ); ?></button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> FitLife Pro. <?php esc_html_e( 'All rights reserved.', 'fitlife' ); ?></p>
            <div class="footer-bottom-links">
                <a href="#"><?php esc_html_e( 'Privacy Policy', 'fitlife' ); ?></a>
                <a href="#"><?php esc_html_e( 'Terms of Service', 'fitlife' ); ?></a>
                <a href="#"><?php esc_html_e( 'Accessibility', 'fitlife' ); ?></a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
