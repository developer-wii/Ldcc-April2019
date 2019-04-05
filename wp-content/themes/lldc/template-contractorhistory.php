<?php 
/* Template Name: Contractor Hisory Panel */

get_header();
global $current_user;
$user_roles=$current_user->roles;
if ( in_array( 'contractor', $user_roles, true ) ) {

?>
<section class="contrctr_history">
<div class="container">
	 <div class="admin_content">
		<div class="admin_sec">
			<div class="actionT clearfix">
				<ul class="items">
					<li><a href="#password_change"><img src="<?php echo get_template_directory_uri(); ?>/images/edit-pass.png" /></a></li>
					<li><a href="<?php echo wp_logout_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/log-out.png" /></a></li>
				</ul>
			</div>
<?php 

$query = new WC_Order_Query( array(
    'limit' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
) );

$orders = $query->get_orders();
//echo '<pre>'; print_r($orders);
?>
			<div class="admin-table">
				<table class="table" width="100%">
					<tr>
						<th>Order NO </th>
						<th class="order-note">Order Note</th>
						<th>Order date</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					<?php foreach($orders as $order) { 
						$orderid=$order->get_id();
					?>
					<tr>
						<td><?php echo $order->get_id(); ?></td>
	
						<td class="order-note"><?php echo $order->get_customer_note(); ?></td>
						<td><?php echo $order->get_date_created()->date('d/m/Y '); ?></td>
						<td><?php echo get_woocommerce_currency_symbol(); echo $order->get_total(); ?></td>
						<td><span class="pending status"><?php echo $order->get_status(); ?></span></td>
						<td><span><a href="#orderview_contractor" onclick="viewordercontractor(<?php echo $orderid; ?>);"><img src="<?php echo get_template_directory_uri(); ?>/images/eye.png"/></a></span><span><a href="#" onclick="trash_contractororder(<?php echo $orderid; ?>)" id="delete<?php echo $orderid; ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/delete.png" /></a></span></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<div class="change_pass_cntrctr" id="password_change">
			<div class="change_pass">
			<form method="POST">
				<h3>Change password</h3>
				<span class="field"><input type="password" placeholder="New password" name="contractor_newpwd" required/></span>
				<span class="field"><input type="password" placeholder="Confirm new password" name="contractor_cnewpwd" required/></span>
				<span class="change_btn"><input type="submit" value="Submit" name="contractor_pwdchange"/></span>
				</form>
			</div>
		</div>
		<div class="Order_view" style="display:none;" id="orderview_contractor">

		</div>
	 </div>
</div>
</section>
<?php } else {
echo '<div class="text-center"><p>You are not allowed to access this page</p></div>';
}	
?>

<?php 
if(isset($_POST['contractor_pwdchange'])){
	$currentid=get_current_user_id();
	$password=$_POST['contractor_newpwd'];
	$cpassword=$_POST['contractor_cnewpwd'];
	$hashpassword=wp_hash_password($password);
	if($password==$cpassword){
	echo '<pre>'; print_r(wp_update_user( array(
        'ID' => $currentid,
        'user_pass' => $password
   ) )); 
		?><script>alert('Your password has been updated successfully,Please login.');
		
		window.location.href="<?php echo get_site_url(); ?>/login";
		</script><?php
		
	}
	else{
		?><script>alert('Password did not match,please try again.');</script><?php
	}
}
?>
<script>
function viewordercontractor(orderid){
	jQuery('#orderview_contractor').show();
 	jQuery.ajax({		
			type: 'POST',		
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			orderid: orderid,
			action: 'view_contractor_order',	 
			},
			success: function(data)
			{			 
				 $('#orderview_contractor').html(data);							
			} 
		
	}); 
}
function trash_contractororder(orderid){
	if (confirm("Are you sure you want to move this order to trash?")){
      jQuery.ajax({		
			type: 'POST',		
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			orderid: orderid,
			action: 'move_contractor_order_totrash',	
			},
			success: function(data)
			{	
				alert('order#'+orderid+' successfully moved to trash.');
				window.location.reload();
				//$('.view_order').html(data);								
			}
		
	});
    }
	else{
		return false;
	}
}

function close_order_histroy(){
	jQuery('#orderview_contractor').hide();
}
</script>
<?php		
get_footer();