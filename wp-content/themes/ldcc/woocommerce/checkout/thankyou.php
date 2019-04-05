<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<section id="order-placed-review" class="order-placed-review">
      <div class="row">
          <div class="small-12 medium-12 large-12 columns">  
        
        <div class="row">
            <div class="small-12 medium-4 large-4 columns your-order-list">
              
    <?php
    if ( $order )
    {
    ?>
 
	<?php if ( $order->has_status( 'failed' ) )
         { 
          ?>

		<p><?php _e( 'Unfortunately your order cannot be processed, please try again by placing a new order.', 'woocommerce' ); ?></p>

		<p>
                    <?php 
                    if ( is_user_logged_in() )
                    { ?>     
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
                    <?php 
                    } 
                    ?>
		</p>

   
                
         <?php 
         } 
         else 
         { 
          ?>

        <h2>Your Order</h2>
		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

                
                <?php
                /*
                require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
                $customWooCheckout = new customWooCheckout();
                $orderODTData = $customWooCheckout->getOrderODTData($order->id);
                //var_dump($orderODTData);
                
                if(is_array($orderODTData))
                {
                   ?>
                    <h2>Collection &amp; Delivery</h2>
                
                    <ul class="order_details">
			<li class="oc-collect-date">
				Collection Date:
				<strong><?php echo $orderODTData[0]->odt_collect_date; ?></strong>
			</li>
			<li class="oc-collect-time">
				Collection Time:
				<strong><?php echo $orderODTData[0]->odt_collect_time; ?></strong>
			</li>
			<li class="oc-drop-date">
				Dropoff Date:
				<strong><?php echo $orderODTData[0]->odt_dropoff_date; ?></strong>
			</li>
			<li class="oc-drop-time">
				Dropoff Time:
				<strong><?php echo $orderODTData[0]->odt_dropoff_time; ?></strong>
			</li>
		</ul>
		<div class="clear"></div>
                    <?php
                }
                */
                ?>
                
         <?php
         } 
         ?>
            
                
            </div>
            <div class="small-12 medium-6 large-6 columns left view-order-details-right">
        <?php do_action( 'woocommerce_thankyou', $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	

            </div>
        </div>
                
<?php
} 
else
{
?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php 
} 
?>
          

</div>
</div>
          
          
    </div>
</section>
