<?php
/**
 * Customer completed order email
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p><?php esc_html_e( 'We have finished processing your order. Your programs and training credentials are now active on your account!', 'fitlife' ); ?></p>

<!-- FitLife Custom Motivational Message callout (Task 4.3) -->
<div style="background-color: #f1f5f9; border-left: 4px solid #10b981; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 5px 0; color: #0f172a; font-size: 1.05rem;"><?php esc_html_e( 'FitLife Transformation Tip', 'fitlife' ); ?></h3>
    <p style="margin: 0; font-style: italic; color: #475569;">
        <?php 
        $goal = $order->get_meta( '_fitness_goal' );
        $motivations = array(
            'weight_loss'   => __( 'Consistency is key. Focus on daily habits and minor milestones, and the results will take care of themselves!', 'fitlife' ),
            'muscle_gain'   => __( 'Rest and nutrition are just as important as the workout. Fuel your body, sleep well, and lift heavy!', 'fitlife' ),
            'endurance'     => __( 'When your legs get tired, run with your heart. Stay persistent and push your limits!', 'fitlife' ),
            'flexibility'   => __( 'Patience is power. Stretching builds longevity and keeps your body moving like a well-oiled machine.', 'fitlife' ),
        );
        echo esc_html( isset($motivations[$goal]) ? $motivations[$goal] : __( 'Every training session is a step towards a healthier, stronger you. Let\'s make today count!', 'fitlife' ) );
        ?>
    </p>
</div>

<?php
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details.
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - action hook.
 */
do_action( 'woocommerce_email_additional_content', $email );

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
