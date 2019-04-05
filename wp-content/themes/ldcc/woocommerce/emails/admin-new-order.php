<?php
/**
 * Admin new order email
 *
 * @author WooThemes
 * @package WooCommerce/Templates/Emails/HTML
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( 'You have received an order from %s. The order is as follows:', 'woocommerce' ), $order->billing_first_name . ' ' . $order->billing_last_name ); ?></p>


<table cellspacing="0" cellpadding="0" style="width:100%;border-bottom: 1px solid #eee;" border="0">
<tr>
    <td width="50%" valign="top">
        <?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>
    </td>
    <td width="50%" valign="top">
        
    <h2>Collection &amp; Delivery</h2>
<?php
    //var_dump($order);
    require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout();
    $orderODTData = $customWooCheckout->getOrderODTData($order->id);
    //var_dump($orderODTData);

    if(is_array($orderODTData) && !empty($orderODTData[0]->odt_collect_date) && !empty($orderODTData[0]->odt_dropoff_date))
    {
    $formatCollectDate = new DateTime($orderODTData[0]->odt_collect_date);
    $newCollectionFormat = $formatCollectDate->format('d-m-Y');
    
    $formatDropoffDate = new DateTime($orderODTData[0]->odt_dropoff_date);
    $newDropoffFormat = $formatDropoffDate->format('d-m-Y');
    ?>

    <strong>Your collection date is:</strong><br />
    <?php echo $newCollectionFormat; ?> - <?php echo $orderODTData[0]->odt_collect_time; ?>
    <br /><br />
    <strong>Your dropoff date is:</strong><br />
    <?php echo $newDropoffFormat; ?> - <?php echo $orderODTData[0]->odt_dropoff_time; ?>
     
    <div class="clear"></div>
        <?php
    }
    ?>

    </td>
</tr>
</table>

<?php do_action( 'woocommerce_email_before_order_table', $order, true, false ); ?>

<h2><a href="<?php echo admin_url( 'post.php?post=' . $order->id . '&action=edit' ); ?>"><?php printf( __( 'Order #%s', 'woocommerce'), $order->get_order_number() ); ?></a> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( false, true ); ?>
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


<?php do_action( 'woocommerce_email_after_order_table', $order, true, false ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, true, false ); ?>


<?php do_action( 'woocommerce_email_footer' ); ?>
