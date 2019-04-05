<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(isset($_SESSION['orderpincode'])) {
      $value1 = $_SESSION['orderpincode'];
} else {
     $value1 = 'Session not initiallized.';
}
echo '<input type="hidden" name="test"  value="'.$value1.'"/>';
if(isset($_SESSION['datepicker_value'])) {
    $value = $_SESSION['datepicker_value'];
} else {
     $value = 'Session not initiallized.';
}

echo '<input type="hidden" name="postcode_hidden" value="'.$postcode.'">';
wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>
    <section class="student_rgstr_form checkout_guest">
        <div class="container">
            <div class="formSR">
                <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

                    <?php if ( $checkout->get_checkout_fields() ) : ?>

                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <div class="col2-set form_fields clearfix" id="customer_details">
                        <div class="col-1 form_box user_form_box box01 col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>

                        <div class="col-2 form_box user_form_box box02 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <h3 id="order_review_heading">
                                    <?php _e( 'Your order', 'woocommerce' ); ?>
                                </h3>
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            </div>
                        </div>
                    </div>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                    <?php endif; ?>



                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>



                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            </div>
        </div>
        </form>
    </section>
    <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
<section class="discount_sec">
			<div class="container">
				<h3>JOIN US AND SAVE UP <span>25%</span> OF YOUR MONTHLY LAUNDRY</h3>
				<div class="content">
					<?php 
				/* get product categories code start*/
				$args = array(
				  'post_type'   => 'monthlyplans',
				  'post_status' => 'publish',
					'posts_per_page'=>-1
				 );
				$all_categories = new WP_Query( $args );
				if( $all_categories->have_posts() ) :
				?>
				<ul class="list clearfix">
					<?php
					  while( $all_categories->have_posts() ) :
						$all_categories->the_post();
						?>
						  <li><?php the_post_thumbnail(); ?></li>
						<?php
					  endwhile;
					  wp_reset_postdata();						
				endif;
				?>
				</ul>
				</div>
				<span class="see_more"><a href="<?php echo get_site_url();?>/monthly-plan/">See more</a></span>
			</div>
		</section>