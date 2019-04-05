<?php 
/* Template Name: Driver Current Delivery Panel */

get_header('driver');

global $current_user;
$user_roles=$current_user->roles[0];
if( $user_roles=='driver' || $user_roles=='administrator' ){}
else{
	?>  <script>window.location.href='<?php echo get_site_url(); ?>/login/';</script> <?php 
}
// only administrator and driver can open this ( page privileges ).

// to print list of order processing and deliverd 
?>
		<div class="content" id="main">
			<section id="delivery_cnt">
				<div class="container">
					<div class="row">
						<div class="col-sm-12 current_delivery">
							<div class="table_structure table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>Order Number</th>
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
                            							'wc-pending-order',
                            							),
                                                'post_type' => 'shop_order',
                                                'posts_per_page' => -1,
                                                'orderby' => 'ID',
                                            );
                                            // order fillter
                                            
                                            $loop = new WP_Query($filters);
                                            
                                            while ($loop->have_posts()) {
                                                
                                                $loop->the_post();
                                                $order = wc_get_order( $loop->post->ID );
                                                $order_data = $order->get_data();
                                                $order_status = $order_data['status'];
                                                
											    $order_status_class = ($order_status == 'completed')? 'deliver_active' : 'deliver' ;
											    
											    $address .= $order_data['shipping']['address_1'];
                                                $address .= $order_data['shipping']['address_2'].', ';
                                                $address .= $order_data['shipping']['city'].' ';
                                                $address .= $order_data['shipping']['postcode'];

                                                if($order_status == 'out-delivery'){
                                                    $status = 'Out for Delivery';
                                                    $dodp_link = '<a href="'. get_permalink( get_page_by_title( "Driver Order Delivery Panel" ) ).''.$order_data['id'] .'" > '.$order_data['shipping']['first_name'].' '.$order_data['shipping']['last_name'].'</a>';
                                                }else if ($order_status == 'pending-order'){
                                                    $status = "Pending";
                                                    $dodp_link = $order_data['shipping']['first_name'].' '.$order_data['shipping']['last_name'];
                                                }
                                                else{
                                                    $status = "Completed";
                                                    $dodp_link = $order_data['shipping']['first_name'].' '.$order_data['shipping']['last_name'];
                                                }
                                                
                                                // get order and user full details.
                                                        
                                                ?>
                                                
                                                
                                                <tr >
        											<td><?php  echo $order_data['id']; ?></td>
        											<td> <?php echo $dodp_link; ?> </td>
        											<td><?php echo $address; ?></td>
        											<td>
        												<p class="<?php echo $order_status_class; ?>"><?php echo $status; ?></p>
        											</td>
        										</tr>
        										
                                                
                                                <?php
                                                    $address = '';
                                            }
                                            wp_reset_query();
									    
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