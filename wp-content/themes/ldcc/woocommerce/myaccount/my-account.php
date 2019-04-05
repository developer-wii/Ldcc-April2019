<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<p class="myaccount_user">
	<?php
	printf(
		__( 'Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'woocommerce' ) . ' ',
		$current_user->display_name,
		wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) )
	);

	printf( __( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>.', 'woocommerce' ),
		wc_customer_edit_account_url()
	);
	?>
</p>

<?php do_action( 'woocommerce_before_my_account' ); ?>

<section id="myaccount" class="myaccount">
    <div class="row">
        <div class="small-12 medium-6 large-6 columns left account-orders">
            <?php wc_get_template( 'myaccount/my-downloads.php' ); ?>
            <?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>
        </div>
        <div class="small-12 medium-6 large-6 columns right account my-address-details">
            <?php wc_get_template( 'myaccount/my-address.php' ); ?>
        </div>
    </div>
</section>

<?php do_action( 'woocommerce_after_my_account' ); ?>
