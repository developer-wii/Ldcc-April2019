
<?php
class Shordcodes_Function{
 
    public function __construct() {
        // widget actual processes
    }
	
/* Order Page Cart Sidebar code start*/
function sidebar_cart()
{
	wc_print_notices();
 ?>
<div id="custom-notification"></div>
<form class="woocommerce-cart-form" method="POST">
	<div class="order_content">
		<div class="order_list clearfix upper_section_cart">
		
	<ul class="list">

			<?php 
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<li>
						<?php
						if ( ! $product_permalink ) {
							echo '<span class="item">'.wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' ).'</span>';
						} else {
							echo '<span class="item">'.wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( $_product->get_name() ), $cart_item, $cart_item_key ) ).'</span>';;
						}

						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>' ) );
						}
						?>
						<span class="price">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</span>

						<span class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input( array(
								'input_name'   => "cart[{$cart_item_key}][qty]",
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $_product->get_max_purchase_quantity(),
								'min_value'    => '0',
								'product_name' => $_product->get_name(),
							), $_product, false );
						}
echo '<div class="customcart">';
						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
			?><input type="hidden" id="prhidden" value="product<?php echo $cart_item['product_id']; ?>"/> <?php	
			echo '</div>';
						?>
						</span>
					
					</li>

					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); 

			?>

			
			
		
</ul>
<?php
$user = wp_get_current_user();
echo '<div class="total"><span class="span_label">Subtotal:<span>£'.WC()->cart->subtotal.'</span></span>';
foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<span class="span_label"><tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr></span>			
			<tr class="order-total">
			<span class="span_label"><th><?php _e( 'Total', 'woocommerce' ); ?>:</th>
				<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php echo get_woocommerce_currency_symbol().WC()->cart->total; ?></td></span>
			</tr>
<?php endforeach; 
		

echo '</div>';
/* if ( in_array( 'student', (array) $user->roles ) ) { 
echo '<span class="total_discount">';wc_cart_totals_coupon_label( 'student_discount' );echo '<span  data-title="'.esc_attr( wc_cart_totals_coupon_label( 'student_discount', false ) ) .'">'; wc_cart_totals_coupon_html( 'student_discount' ); echo '</span></span>
<span class="total">Total:<span>£'.WC()->cart->total.'</span></span>';
} */
echo '</div>';

 
$user = wp_get_current_user();

echo '<div class="lower_section_cart">';		
if ( in_array( 'contractor', (array) $user->roles ) ) {
	echo '<div class="fields">
			<span class="field">';
			if(isset($_SESSION['order_comments'])) {
				echo '<textarea name="order_comment" class="input-text " id="order_comment" placeholder="Delivery Note" rows="2" cols="5" >'. $_SESSION['order_comments'].'</textarea>';
			} else {
				echo '<textarea name="order_comment" class="input-text " id="order_comment" placeholder="Delivery Note" rows="2" cols="5" ></textarea>';
			}
			echo '</span>
			<span class="date-time">
				<span class="date">';
				
				if(isset($_SESSION['datepicker_value'])) {
				   echo '<input placeholder="Delivery" type="text" id="datepicker" name="datepicker_order" value="'. $_SESSION['datepicker_value'].'" />';
				}
				else {
					 echo '<input placeholder="Delivery" type="text" id="datepicker" name="datepicker_order"/>';	
				}
				echo '</span>';
				if(isset($_SESSION['contractor_deliverytime'])) {
				   echo '<span class="time"><input type="time" name="contractor_deliverytime" placeholder="Time" value="'. $_SESSION['contractor_deliverytime'] .'"/>';
				}
				else {
					 echo '<span class="time"><input type="time" name="contractor_deliverytime" placeholder="Time"/>';
				}
				echo '</span>
				
		</div>';
}
else{	
echo '<div class="fields"><span class="date-time"><span class="date">';

if(isset($_SESSION['datepicker_value'])) {
   echo '<input placeholder="Delivery" type="text" id="datepicker" name="datepicker_order" value="'. $_SESSION['datepicker_value'].'" />';
}
else{
	 echo '<input placeholder="Delivery" type="text" id="datepicker" name="datepicker_order"/>';
}							
	echo '</span></span>';

	echo '</div>';						
	
}
echo '<div class="fields">';
if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" id="apply_coupon_custom" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
							<p class="result"></p>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } echo '</div>';		
//echo '<span class="pay_now">'.do_action( 'woocommerce_proceed_to_checkout' ).'</span>';
$checkout_page_url = get_permalink( wc_get_page_id( 'checkout' ) );
$cart_page_url = get_permalink( wc_get_page_id( 'order' ) );

echo '<div class="order_action"><span class="pay_now"><input type="submit" value="Checkout" name="checkout_button" /></span>';

if ( in_array( 'contractor', (array) $user->roles ) ) {
	echo '<span class="submit_pay"><input type="submit" value="Submit &amp; pay later" name="pay_later"></span>';
}
echo '</div>';
/* get value in session */
if(isset($_POST['checkout_button'])){
	$_SESSION['order_comments'] = $_POST['order_comment'];
	$_SESSION['datepicker_value']=$_POST['datepicker_order'];
	if ( in_array( 'contractor', (array) $user->roles ) ) {
	$_SESSION['contractor_deliverytime']=$_POST['contractor_deliverytime'];
	}
	?><script>window.location.href="<?php echo $checkout_page_url; ?>"</script> <?php
}

if(isset($_POST['pay_later'])){

	$_SESSION['order_comments'] = $_POST['order_comment'];
	$_SESSION['datepicker_value']=$_POST['datepicker_order'];
	if ( in_array( 'contractor', (array) $user->roles ) ) {
	$_SESSION['contractor_deliverytime']=$_POST['contractor_deliverytime'];
	}
?><script>alert('Item has beed added to cart.'); window.location.href="<?php echo $cart_page_url; ?>" </script> <?php
}
$postcode=$_SESSION['orderpincode'];
echo '<input type="hidden" name="postcode_hidden" value="'.$postcode.'">';
echo '</div>';							
?>			

</div>	
</form>
<?php 

}
/* Order Page Cart Sidebar code end*/
}