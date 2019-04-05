<?php  
//include get_template_directory() . '/js/addproduct.php';
class OrderProduct_Function{
 
    public function __construct() {
        // widget actual processes

    }

/* get products in order page code start*/ 
public function get_products_bytermid($term_id,$query_args)	{
	$term_id = $term_id;
	$query_args = array( 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array( 
    array(
      'taxonomy' => 'product_cat',
      'field' => 'id',
      'terms' => $term_id
    )));
    return $query_args;
}
/* get products in order page code end*/ 
	
/* Add Product to cart on checking checkbox or Increasing quantity code start */
    public function add_product_to_cart( $id ) { 
		$productid = $id;
		if ( ! is_admin() ) {
			if($productid != ""){	
			$product_id = $productid; //replace with your own product id
			$found = false;
			//check if product already in cart
			if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->get_id() == $product_id )
					$found = true;
				}
			// if product not found, add it
				if ( ! $found )
					WC()->cart->add_to_cart( $product_id );
				}
			else {
				// if no products in cart, add it
				WC()->cart->add_to_cart( $product_id );
			}
		}
		$postcode=$_SESSION['orderpincode'];
echo '<input type="hidden" name="postcode_hidden" value="'.$postcode.'">';
		}
 		
	}
/* Add Product to cart on checking checkbox or Increasing quantity code end */

/* Remove Product from cart on checkbox uncheck or reducing product quantity code start */
public function removeproduct($id)
{
	$prodid = $id;	
	$cartId = WC()->cart->generate_cart_id( $prodid );
	$cartItemKey = WC()->cart->find_product_in_cart( $cartId );
	WC()->cart->remove_cart_item( $cartItemKey );
} 
/* Remove Product from cart on checkbox uncheck or reducing quantity code end */

/* Increase / decrease quantity functionality on cart page code start */
public function ajax_quantity_cart(){
	 // Set item key as the hash found in input.qty's name
    $cart_item_key = $_POST['hash'];
	$_SESSION['datepicker_value']=$_POST['datepicker_ajax'];
    // Get the array of values owned by the product we're updating
    $threeball_product_values = WC()->cart->get_cart_item( $cart_item_key );

    // Get the quantity of the item in the cart
    $threeball_product_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', apply_filters( 'woocommerce_stock_amount', preg_replace( "/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT)) ), $cart_item_key );

    // Update cart validation
    $passed_validation  = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity );

    // Update the quantity of the item in the cart
    if ( $passed_validation ) {
        WC()->cart->set_quantity( $cart_item_key, $threeball_product_quantity, true );
 }
}
/* Increase / decrease quantity functionality on cart page code end */

/* Remove label from billing fields in checkout page code start  */
/* public function remove_label_from_billingaddress($fields){
 foreach ($fields as $category => $value) {
        // loop by fields
        foreach ($fields[$category] as $field => $property) {
            // remove label property
            unset($fields[$category][$field]['label']);
        }
    }
     return $fields;
} */

/* change placeholder for Address fields in checkout page code start  */
public function change_label_from_defaultaddress($fields){
 $fields['address_1']['placeholder'] = 'Address 1';
    $fields['address_2']['placeholder'] = 'Address 2';
    $fields['postcode']['placeholder'] = 'Post code (Prefiedl)';
	if(isset($_SESSION['orderpincode'])) {
		$d1 = $_SESSION['orderpincode'];
	}
	else{
		$d1 = '';
	}
	$fields['postcode']['default'] = $d1;	
	//$fields['postcode']['required'] = false;	
 // unset($fields['billing']['billing_postcode']['validate']);
    return $fields;
}
/* change placeholder for Address fields in checkout page code end  */

/*change in checkout fields code start*/
public function change_label_from_checkoutaddress($fields){
	global $current_user;
$user_roles=$current_user->roles;

	//echo '<pre>'; print_r($fields); exit;
	//get value of delivery date from session
if(isset($_SESSION['datepicker_value'])) {
		$default = $_SESSION['datepicker_value'];
	}
	else{
		$default = '';
	}
	
	if(isset($_SESSION['contractor_deliverytime'])) {
		$time = $_SESSION['contractor_deliverytime'];
	}
	else{
		$time = '';
	}
	
	if(isset($_SESSION['orderpincode'])) {
		$d1 = $_SESSION['orderpincode'];
	}
	else{
		$d1 = '';
	}
	if(isset($_SESSION['order_comments'])) {
		$comments = $_SESSION['order_comments'];
	}
	else{
		$comments = '';
	}
	unset($fields['billing']['billing_postcode']['validate']);
$fields['order']['order_comments']['placeholder'] = 'Delivery Note';
$fields['order']['order_comments']['default'] = $comments;
	//Placeholder changes for below fields.
	$fields['billing']['billing_phone']['placeholder'] =  'Contact Number*';
	$fields['billing']['billing_phone']['required'] = true;
	$fields['billing']['billing_email']['placeholder'] =  'Email*'; 
	$fields['billing']['billing_first_name']['placeholder'] = 'Name*';
    $fields['billing']['billing_last_name']['placeholder'] = 'Last name';
    $fields['billing']['billing_city']['placeholder'] = 'City';
    $fields['billing']['billing_state']['placeholder'] = 'State';
	//Add new field of delivery date
	$fields['order']['datepicker_order']=array(
		'type' => 'text',
		'class' => array(
			'my-field-class form-row-wide'
		) ,
		'id' => 'datepicker',
		'placeholder' => 'Delivery',
		'default'=> $default,
	);
	if ( in_array( 'contractor', $user_roles, true ) ) {
	$fields['order']['contractor_deliverytime']=array(
		'type' => 'time',
		'class' => array(
			'my-field-class form-row-wide'
		) ,
		'id' => 'datepicker',
		'placeholder' => 'Time',
		'default'=> $time,
	);
	}
	unset($fields['billing']['billing_company']);
	return $fields;
/* Remove label from Address fields in checkout page code end  */
}
/*change in checkout fields code end*/


/*view user by id ajax function in admin panel page code start*/
function viewuserbyid($id,$role){
	?>
	            <!--div class="change-pass col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<h3>Change password</h3>
					<input type="password" name="admin_newpwd" placeholder="New password"/>
					<span class="change_btn"><input type="submit" value="Change password" name="admin_changepwd"/></span>
				</div-->
				<div class="admin_edit_table col-lg-8 col-md-8 col-sm-8 col-xs-12" >
				<form method="POST">
					<h3>Edit</h3>
					<?php 
					$user=get_user_by( 'id', $id) ;
						$role=$user->roles[0];
					?>
					<table width="100%">
						<tr>
							<th>Admin name</th>
							<th>User name</th>
							<th>Email</th>
							<th>Password</th>
							<th>Admin type</th>
						</tr>
						<tr>
							<td><input type="text" name="edit_displayname" value="<?php echo $user->display_name ; ?>" size="8"></td>
							<td><input type="text" name="edit_username" value="<?php echo $user->user_login ; ?>" size="8"></td>
							<td><input type="text" name="edit_emailname" value="<?php echo $user->user_email ; ?>" size="16"></td>
							<td><input type="password" name="edit_password" value="<?php echo $user->user_pass ; ?>" size="10"></td>
							<td><select name="admin_roleedit"><option value="">Select</option><option value="administrator" <?php if($role=='administrator'){ echo 'selected="selected"'; } ?>>Type ( Super admin)</option><option value="admin" <?php if($role=='admin'){ echo 'selected="selected"'; } ?>>Type ( Admin)</option><option value="shop_manager" <?php if($role=='shop_manager'){ echo 'selected="selected"'; } ?>>Type ( Shop manager )</option></select><?php //echo $user->roles[0]; ?></td>
							<input type="hidden" name="id_user" value="<?php echo $id; ?>"/>
						</tr>
					</table>
					<div class="actionedit">
					<span class="Discard"><input type="button" value="Discard" onclick="discard_view_adminpanel()"></span>
						<span class="save_changes"><input type="submit" value="save changes" name="save_changesadmin"></span>
					</div>
					</form>
				</div>
				<?php		
}
/*view user by id ajax function in admin panel page code end*/

/*view order by id in contractor history page code start*/
public function vieworderforcontractor($order_id){
	$order = wc_get_order($order_id);
	$order_data = $order->get_data();
	?>
	<div class="order_list clearfix">
		<h3>Order view</h3>
		<table class="table" width="100%">
			<tr>
				<th>Order NO</th>
				<th>Order date</th>
				<th>Order price</th>
				<th>Time</th>
				<th>Status</th>
				<th class="order-note">Client note</th>
				<!--th>Attachment</th-->
			</tr>
			<tr>
				<td><?php echo $order_data['id']; ?></td>
				<td><?php  echo $order_data['date_created']->date('d/m/Y '); ?></td>
				<td><?php echo get_woocommerce_currency_symbol(); echo $order_data['total']; ?></td>
				<td><?php echo $order_data['date_created']->date('h:i A'); ?></td>
				<td><span class="pending status"><?php echo $order_data['status']; ?></span></td>
				<td class="order-note"><?php echo $order_data['customer_note']; ?></td>
				<!--td></td-->
			</tr> 
		</table>
		<div class="actionedit">
			<span class="close_order"><input type="button" value="Close" onclick="close_order_histroy();"/></span>
			<span class="Delete_order"><input type="button" value="Delete" onclick="trash_contractororder('<?php echo $order_data['id']; ?>')"/></span>
		</div>
	</div>
	<?php
}
/*view order by id in contractor history page code end*/

/*move order to trash by id in contractor history page code end*/
public function move_contractor_order_totrash($order_id){
	wp_trash_post($order_id,true);
}
/*move order to trash by id in contractor history page code end*/

/* Woocommerce auto register guest users code start */
public function register_guest_users($order_id){
	 $order = new WC_Order($order_id);
  
  //get the user email from the order
  $order_email = $order->billing_email;
    
  // check if there are any users with the billing email as user or email
  $email = email_exists( $order_email );  
  $user = username_exists( $order_email );
  
  // if the UID is null, then it's a guest checkout
  if( $user == false && $email == false ){
    
    // random password with 12 chars
    $random_password = wp_generate_password();
    
    // create new user with email as username & newly created pw
    $user_id = wp_create_user( $order_email, $random_password, $order_email );
    
    //WC guest customer identification
    update_user_meta( $user_id, 'guest', 'yes' );
 
    //user's billing data
    update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
    update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
    update_user_meta( $user_id, 'billing_city', $order->billing_city );
    update_user_meta( $user_id, 'billing_company', $order->billing_company );
    update_user_meta( $user_id, 'billing_country', $order->billing_country );
    update_user_meta( $user_id, 'billing_email', $order->billing_email );
    update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
    update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
    update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
    update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
    update_user_meta( $user_id, 'billing_state', $order->billing_state );
 
    // user's shipping data
    update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
    update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
    update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
    update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
    update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
    update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
    update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
    update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
    update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
    update_user_meta( $user_id, 'shipping_state', $order->shipping_state );
    
    // link past orders to this newly created customer
    wc_update_new_customer_past_orders( $user_id );
  
  
   $siteurl=get_site_url();
		$to = $order_email;
		$subject = 'User registration for London dry cleaning company.';
	
		$body = 'Hello <b>'.$order->billing_first_name.' '.$order->billing_last_name.'</b>, You have successfully registered to <a href='.$siteurl.'>'.$siteurl.'</a>.<br/>Please use this credentials to login into the site :<br/> <b>Username: </b>'.$order_email.' <b>Password: </b>'.$random_password.'.<br/>Thank You.';
		$headers = array('Content-Type: text/html; charset=UTF-8');
	 
		wp_mail( $to, $subject, $body, $headers );
}	
}

/* adding data and meta to order detail start */

function checkout_field_display_admin_order_meta($order){
    
    echo '<p><strong>'.__('Delivery Date').':</strong> ' . get_post_meta( $order->get_id(), 'woo_custom_datepicker', true ) . '</p>';
	
	echo '<p><strong>'.__('Delivery Time').':</strong> ' . get_post_meta( $order->get_id(), 'woo_custom_time', true ) . '</p>';
	
	$deliver_order_meta = get_post_meta( $order->get_id(),'_d_order_meta' );
	// get persone name only from deliver order meta 
	
	echo '<p><strong>'.__('Person name ').':</strong> ' . unserialize($deliver_order_meta[0])['p_name'] . '</p>';
	
	if(unserialize($deliver_order_meta[0])['ak_recipet']){$a_r = 'Yes';}
	else{ $a_r = 'No'; }
	
	echo '<p><strong>'.__('Acknowledge Receipt ').':</strong> '.$a_r.' </p>';
	
	if(unserialize($deliver_order_meta[0])['d_w_sign']){
	    echo '<p><strong>'.__('Signature ').':</strong> Delivered without Signature. </p>';
	}
	else{
	    echo '<p><strong>'.__('Signature ').':</strong> <img src="'.unserialize($deliver_order_meta[0])['image64base'].'" height="100px" widht="200px"></p>';
	}
    
}
/* adding data and meta to order detail end */



// change order status code start 
function order_status_change($order_id, $status){
    $order = wc_get_order( $order_id );
    $order->update_status( $status );
}
// change order status code end



// register custom order status code start

function fn_register_post_status($order_status_name, $lable){
    register_post_status( $order_status_name, array(
        'label'                     => $lable,
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( $lable.' <span class="count">(%s)</span>', $lable.' <span class="count">(%s)</span>' )
    ) );
}
// register custom order status code end


//add custom order status to list of order status code start
function add_custom_order_status_to_list_of_order_status($order_statuses){
    
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-pending-order'] = 'Pending-order';
            $new_order_statuses['wc-out-delivery']  = 'Out for delivery';
        }
    }
    return $new_order_statuses;
    
}
//add custom order status to list of order status code end





/* Woocommerce auto register guest users code end */	
}