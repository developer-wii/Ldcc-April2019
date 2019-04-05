<?php
/**
 * Customer note email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php _e( "Hello, a note has just been added to your order:", 'woocommerce' ); ?></p>

<blockquote><?php echo wpautop( wptexturize( $customer_note ) ) ?></blockquote>

<p><?php _e( "For your reference, your order details are shown below.", 'woocommerce' ); ?></p>

<?php
if(is_object($order))
{
?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php printf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( $order->is_download_permitted(), true ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<h2>Collection &amp; Delivery</h2>
<?php
    //var_dump($order);
    require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout();
    $orderODTData = $customWooCheckout->getOrderODTData($order->id);
    //var_dump($orderODTData);

    if(is_array($orderODTData) && !empty($orderODTData[0]->odt_collect_date) && !empty($orderODTData[0]->odt_dropoff_date))
    {
       ?>
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
    ?>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>

<?php
}
else
{
    echo '<br /><br /><p><strong>There seems to be an issue lifting your order details, please contact us for assistance.</strong></p>';
}
?>
    
<?php do_action( 'woocommerce_email_footer' ); ?>
