<?php 
/* Template Name: Shop control panel */

get_header('other');
require_once( get_template_directory() . '/inc/class-shop_control_panel.php');
$db = new ShopControlPanel_Function();
global $current_user;
$user_roles=$current_user->roles;
if ( (in_array( 'administrator', $user_roles, true )) || (in_array( 'shop_manager', $user_roles, true )) || (in_array( 'admin', $user_roles, true ))) {
?>
<div class="se-pre-con"></div>
	<div class="wrapper">

		<section class="shop_control">
			<div class="container">
				 <div class="shopC_list">
				 <div class="loader_order" style="display:none;"><img src="<?php echo get_template_directory_uri(); ?>/images/Trans_loader.gif"></div>
					 <ul class="nav nav-tabs history_orders">
						<li class="active"><a data-toggle="tab" href="#web_order">Web orders</a></li>
						<li><a data-toggle="tab" href="#contrctr_ordr">Contractor orders</a></li>
					  </ul>

					  <div class="tab-content">
						<div id="web_order" class="tab-pane fade in active">
						<?php 
$query = new WC_Order_Query( array(
    'limit'     => -1,
    'orderby'   => 'date',
    'order'     => 'DESC',
    'status'    => array(
        'wc-pending-order',
        'wc-out-delivery',
        'wc-completed',
    ),
    
) );

$orders = $query->get_orders();
//echo'<pre>'; print_r($orders);
?>
				<table width="100%">
					<tr>
						<?php 
						echo $response1 = $db->shopcontrolpanelheadings();
						?>
					</tr>
					<?php foreach($orders as $order) { 
						$customer_id=$order->customer_id;
						$user_meta=get_userdata($customer_id);
						$user_roles=$user_meta->roles;
						if (! in_array( 'contractor', $user_roles, true ) ) {?>
					<tr>
					<?php						
						$response = $db->shopcontrolpanel($order);
						echo $response;  	
					?>					
					</tr>
					<?php 
						}
					} ?>
			
				</table>
			<div class="view_order" id="order_view" style="display:none;">
			</div>
						</div>
						<div id="contrctr_ordr" class="tab-pane fade">
						  <table width="100%">
					<tr>
						<?php 
						echo $response1 = $db->shopcontrolpanelheadings();
						?>
					</tr>
					<?php foreach($orders as $order) { 
						$customer_id=$order->customer_id;
						$user_meta=get_userdata($customer_id);
						$user_roles=$user_meta->roles;
						if ( in_array( 'contractor', $user_roles, true ) ) {?>
					<tr>
					<?php						
						$response = $db->shopcontrolpanel($order);
						echo $response;  	
					?>					
					</tr>
					<?php 
						}
					} ?>
			
				</table>
				<div class="view_order" id="order_view_contractor" style="display:none;">
			</div>
						</div>
					  </div>
				 </div>
			
			</div>			
		</section>		
	</div>
<?php } else {
echo '<div class="text-center">You are not allowed to access this page.</div>';	
}
?>
<script>
function vieworderajax(orderid){
//history_orders
    
	jQuery('#order_view').show();
	jQuery('.loader_order').show();

	jQuery.ajax({		
			type: 'POST',
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			orderid: orderid,
			action: 'view_particular_order',	
			},
			success: function(data)
			{			 
				$('#order_view').html(data);	
				jQuery('.loader_order').hide();
				jQuery('input.get_barcode_'+orderid).focus();
			}
		
	});
	
}


function barcode_scanned_data(cur_id,order_id){
    var myObj = { cur_id : cur_id, order_id : order_id };
    console.log(myObj);
    if(cur_id == order_id){
        jQuery('.loader_order').show();
        var status_txt = "out-delivery";
    	jQuery.ajax({		
			type: 'POST',
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			orderid: order_id,
			status: status_txt,
			action: 'update_order_status',	
			},
			success: function(data)
			{
				jQuery('.loader_order').hide();
				alert("order scanned successfully");
				setTimeout(function(){ 
				    window.location.reload();
				}, 1000);
			}
    		
    	});
    }
    else{
        alert('id does not matched');
    }
    
}


function vieworderajax_con(orderid){ 
	jQuery('#order_view_contractor').show();
	jQuery.ajax({		
			type: 'POST',		
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			orderid: orderid,
			action: 'view_particular_order',	
			},
			success: function(data)
			{	
				$('#order_view_contractor').html(data);
				jQuery('input.get_barcode_'+orderid).focus();
			}
		
	});
	
	
}

function movetotrash_order(orderid){
	if (confirm("Are you sure you want to move this order to trash?")){
      jQuery.ajax({		
			type: 'POST',		
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			orderid: orderid,
			action: 'moveordertotrash',	
			},
			success: function(data)
			{	
				alert('order#'+orderid+' successfully moved to trash.');
				window.location.reload();
			}
		
	});
    }
	else{
		return false;
	}
}

function printfunction(){ 
    // jQuery('.list').print();
 
   var divToPrint=document.getElementById('order_view');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);  
  
  
}
</script>
<?php get_footer(); ?>