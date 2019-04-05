
<?php  
//include get_template_directory() . '/js/addproduct.php';
class ShopControlPanel_Function{
 
    public function __construct() {
        // widget actual processes

    }

/*view order by id in shop control page code start*/
public function vieworderbyid($order_id){
	$order = wc_get_order($order_id);
	$order_data = $order->get_data();
	?>
	<h3>View order</h3>
	<div class="content" id="DivToPrint">
		<div class="details">
			<ul class="list">
				<li><span>Order number :</span><?php echo $order_data['id']; ?></li>
				<li><span>Client name :</span><?php echo $order_data['billing']['first_name']; echo ' '; ?><?php echo $order_data['billing']['last_name']; ?></li>
				<li><span>Email :</span><?php echo $order_data['billing']['email']; ?></li>
				<li><span>Date : </span><?php  echo $order_data['date_created']->date('Y-m-d '); ?></li>
				<li><span>Time :</span><?php echo $order_data['date_created']->date('h:i A'); ?></li>
				<li><span>Scan  :</span><input type="text" onchange="barcode_scanned_data(this.value,<?php echo $order_data['id']; ?>)" class="get_barcode get_barcode_<?php echo $order_data['id']; ?>" /></li>
			</ul>
			<ul class="list">
				<li><span>Price :</span><?php echo get_woocommerce_currency_symbol(); echo $order_data['total']; ?></li>
				<li><span>Payment :</span><?php echo $order_data['payment_method']; ?></li>
				<li><span>Detail (order break down) :</span><?php echo $order_data['status']; ?></li>
				<li><span>Barcode :</span><svg id="barcode"></svg></li>
			</ul>
			<span class="print">
				<a href="#" onclick="printfunction()">Print</a>
			</span>
		</div>
	</div>
	<script>
	var bar_id= <?php echo $order_id; ?>;
	JsBarcode("#barcode",  bar_id);
	//alert(bar_id);
	</script>
	<?php
}
/*view order by id in shop control page code end*/

/*move order to trash by id in shop control page code end*/
public function moveorderto_trash_byid($order_id){
	wp_trash_post($order_id,true);
}
/*move order to trash by id in shop control page code end*/




/* Shop control panel view orders code start*/
public function shopcontrolpanel($order){
		$customer_id=$order->customer_id;
		$user_meta=get_userdata($customer_id);
		$user_roles=$user_meta->roles;
		$orderid=$order->get_id();
		?>

		<td><?php echo $order->get_id(); ?></td>
		<td><?php echo $order->get_billing_first_name().' '.$order->get_billing_last_name(); ?></td>
		<td><?php echo $order->get_billing_email();?> </td>
		<td> <?php echo $order->get_date_created()->date('Y-m-d '); ?></td>
		<td><?php echo date('h:i A', strtotime($order->get_date_created())); ?></td>
		<td><?php echo get_woocommerce_currency_symbol(); echo $order->get_total(); ?></td>
		<td>Paid</td>
		<td>
		<?php 
		    if($order->get_status() == "completed") {
		        echo '<span class="complete status completed">Complete</span>';
    		}else if($order->get_status() == "out-delivery"){
    		    echo '<span class="pending status">Out for Delivery</span>';
    		}else{
    		    echo '<span class="pending status stlPanding">Pending</span>';
    		}		
		?>
		</td>
		<td><span>
		<?php if (! in_array( 'contractor', $user_roles, true ) ) { ?>
		<a href="#order_view" id="vieworderajax" onclick="vieworderajax(<?php echo $orderid; ?>);"><img src="<?php echo get_template_directory_uri(); ?>/images/eye.png" /></a> <?php } else { ?>
		<a href="#order_view_contractor" id="vieworderajax" onclick="vieworderajax_con(<?php echo $orderid; ?>);"><img src="<?php echo get_template_directory_uri(); ?>/images/eye.png" /></a> 
		<?php } ?>
		</span>
		<span><a href="#" onclick="movetotrash_order(<?php echo $orderid; ?>)" id="delete<?php echo $orderid; ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/delete.png" /></a></span></td>
<?php

}
/* Shop control panel view orders code end*/
/* Shop control panel view orders headings code start*/
public function shopcontrolpanelheadings(){
		echo '<th>Order No</th>
			    <th>Client name</th>
			    <th>Email</th>
			    <th>Date</th>
			    <th>Time</th>
			    <th>Price</th>
			    <th>Payment</th>
			    <th>Status</th>
			    <th>Action</th>';
}
/* Shop control panel view orders headings code start*/			
}