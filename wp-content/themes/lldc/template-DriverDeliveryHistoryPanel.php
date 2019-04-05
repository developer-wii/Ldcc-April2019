<?php 
/* Template Name: Driver Delivery History Panel */

get_header('driver');

global $current_user;
$user_roles=$current_user->roles[0];
if( $user_roles=='driver' || $user_roles=='administrator' ){}
else{
	?>  <script>window.location.href='<?php echo get_site_url(); ?>/login/';</script> <?php 
}
// only administrator and driver can see this. ( page validation )

?>

<div class="content" id="main">
	<section id="delivery_cnt" class="mar_botto">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 current_delivery">
					<div class="table_structure table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>Order Number</th>
									<th>Date</th>
									<th>Time</th>
									<th>Client Name</th>
									<th>Delivery Address</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
					 <?php 
					
						 $filters = array(
						'post_status' => array(
						    'wc-completed',
							'wc-out-delivery',
							'wc-pending',
							'wc-pending-order',
							'wc-cancelled',
							'wc-refunded',
							'wc-failed',
							),
						'post_type' => 'shop_order',
						'posts_per_page' => -1,
						);
						// fillter the order data 
						
						$loop = new WP_Query($filters);
						
						while ($loop->have_posts()) {
							$loop->the_post();
							$order = wc_get_order( $loop->post->ID );
                            $order_data = $order->get_data();
                            $order_status = $order_data['status'];
                
                            $date = $order->get_date_modified()->date('d-m-Y');
			                $time = $order->get_date_modified()->date('H:i:s');
							
						    $address .= $order_data['shipping']['address_1'];
                            $address .= $order_data['shipping']['address_2'].', ';
                            $address .= $order_data['shipping']['city'].' ';
                            $address .= $order_data['shipping']['postcode'];
							
							$full_name = $order_data['shipping']['first_name'].' '.$order_data['shipping']['last_name'];
							
							// generate dynamic class and button text from status code start
							if($order->get_status() == "completed") {
						        $status_btn = '<div class="deliver_active">Complete</div>';
                    		}else if($order->get_status() == "out-delivery"){
                    		    $status_btn = '<div class="deliver deliver_active">Out for Delivery</div>';
                    		}else if($order->get_status() == "pending"){
						        $status_btn = '<div class="deliver deliver_active">Pending payment</div>';
						    }else if($order->get_status() == "pending-order"){
						        $status_btn = '<div class="deliver deliver_active">Pending</div>';
						    }else if($order->get_status() == "cancelled"){
						        $status_btn = '<div class="error_msg">Cancelled</div>';
						    }else if($order->get_status() == "refunded"){
						        $status_btn = '<div class="deliver deliver_active">Refunded</div>';
						    }else if($order->get_status() == "failed"){
						        $status_btn = '<div class="error_msg">failed</div>';
						    }
						    // generate dynamic class and button text from status code end
						    
							
							// getting adn generating data of single order.
							?>
					<tr>
						<td><?php echo $order_data['id']; ?></td>
						<td><?php echo $date; ?></td>
						<td><?php echo $time;?></td>
						<td><?php echo $full_name; ?></td>
						<td><?php echo $address; ?></td>
						<td>
							<?php echo $status_btn; 
							    echo '<div class="Tool_tip">
									<div class="pullinleft"><img src="'.esc_url( get_template_directory_uri() ).'/images/delivery-truck.png" alt="delivery-truck"></div>
									<div class="pullinright"><b>Delivery Note</b>
										<p>'.$order->customer_message.'</p>
									</div>
								</div>';
							?>
						</td>
					</tr>
					<?php 
						 $address = '';
					}
					?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
		</div>
		<div class="social">
			<ul class="list">
				<li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/facebook.png" /></a></li>
				<li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/twtr.png" /></a></li>
				<li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/insta.png" /></a></li>
				<li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/you-tube.png" /></a></li>
				<li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/in.png" /></a></li>
			</ul>
		</div>
	<?php
	    get_footer();
	?>