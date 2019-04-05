<?php
/* Template Name: Order page */

get_header();

require_once( get_template_directory() . '/inc/class-theme_shortcodes.php');
require_once( get_template_directory() . '/inc/class-order_product.php');
$pr = new OrderProduct_Function();
$db = new Shordcodes_Function();
global $current_user;
$user_roles=$current_user->roles;
//$_SESSION['orderpincode'] = $_POST['postcode'];
?>

	<?php

$orderby = 'name';
$order = 'asc';
$hide_empty = false ;
$cat_args = array(
	'orderby'    => $orderby,
	'order'      => $order,
	'hide_empty' => $hide_empty,
	'exclude'=>array(31,32)
);
$product_categories = get_terms( 'product_cat', $cat_args );
?>
		<section class="contrctr_orders">
			<div class="container">
				<?php if ( in_array( 'contractor', $user_roles, true ))  { ?>
				<div class="order_history clearfix">
					<div class="history_box"><a href="<?php echo get_site_url(); ?>/contractor-history-panel/"><img src="<?php echo get_template_directory_uri();?>/images/order-history.png" />Order History</a></div>
				</div>
				<?php } ?>
				<div class="cat_list clearfix">
					<ul class="nav nav-tabs">

						<?php
				//echo "<pre>";
				//print_r($product_categories);
				$i = 0;
				foreach ($product_categories as $key => $category) {
					$id[] = $category->term_id;
					$thumbnail_id = get_woocommerce_term_meta( $id[$i], 'thumbnail_id', true );
					$image = wp_get_attachment_url( $thumbnail_id );
				 ?>
							<li <?php if($i==0){echo 'class="active"';}?>><a data-toggle="tab" href="#<?php echo $category->name; ?>"><img class="white" src="<?php echo $image;?>" /><img class="blue" src="<?php echo $image;?>" /><?php echo $category->name;?></a></li>
							<?php
				 $i++;
			} ?>
					</ul>
					<div class="loader_order" style="display:none;">
						<img src="<?php echo get_template_directory_uri(); ?>/images/Trans_loader.gif" />
						<!--<img src="<?php echo get_template_directory_uri(); ?>/images/Preloader_2.gif" />-->
					</div>
					<div class="left-content">
						<div class="tab-content">
							<?php
				if(!WC()->cart->is_empty()):
								foreach(WC()->cart->get_cart() as $cart_item ):
								$items_id[] = $cart_item['product_id'];
								endforeach;
								endif;
				  if(!empty($id)){
					  $j = 0;
				 foreach ($id as $key => $termdata) {
					 //echo "<pre>";
					 //print_r($termdata);

				?>
								<div id="<?php echo get_term($termdata)->name;?>" class="tab-pane fade <?php if($j ==0){echo 'in active';}?>">
									<div class="user_orders">
										<ul class="items clearfix">
											<?php
		$id = $termdata;
		$products = $pr->get_products_bytermid($id,$query_args);
		//$products = get_products_bytermid($id,$query_args);
		$products1 = new WP_Query($products);
		$produc = $products1->posts;

		if(!empty($produc)){
		 foreach ($produc as $key => $produc) {
			 //$saleprc = $_product->get_sale_price($produc->ID);
			 $_product = wc_get_product( $produc->ID );
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $produc->ID) );

		?>
												<li class="pro_div">

													<span class="right_span">
													<span class="pro_img"><img src="<?php echo $thumbnail[0] ;?>" /></span>
													</span>
													<span class="left_span">
<span class="pro_checkbox">



<input type="checkbox" <?php if(!empty($items_id)){if(in_array($produc->ID, $items_id)){ echo "checked ";} } ?>name="product_id[]" value="<?php echo $produc->ID;?>" id="product<?php echo $produc->ID; ?>" class="checkboxorder" onclick="productorder(<?php echo $produc->ID; ?>)"/>
<label for="product<?php echo $produc->ID; ?>" id="add-to-cart-button"><?php if(in_array($produc->ID, $items_id)){ echo "Remove";} else{ echo 'Add To Cart'; }  ?></label>
</span>
													<span class="item-name pro_name"><?php echo $produc->post_title;?> </span>

													<span class="price pro_price"><?php if( $_product->is_on_sale() ) {
	 echo '<span class="reg_price">';
	 echo '<del>';
	 echo get_woocommerce_currency_symbol();
	 echo $_product->get_regular_price();  echo '</del>'; echo '&nbsp';
	 echo '</span>'; echo '<span class="sale_price">';
	 echo get_woocommerce_currency_symbol(); echo $_product->get_sale_price();  echo '</span>'; } else { echo '<span class="reg_price">';echo get_woocommerce_currency_symbol(); echo $_product->get_regular_price(); echo '</span>';} ?></span>
													</span>

												</li>
												<?php

									}
								}
								?>
										</ul>
									</div>
								</div>
								<?php
						$j++;
					}

					}
					?>

						</div>
					</div>
					<?php if ( in_array( 'contractor',  $user_roles, true ) ) { ?>
					<div id="your_basket_id" class="your_basket">
						<?php }
				else{ ?>
						<div id="your_basket_id" class="your_basket user_bsket">
							<?php } ?>
							<h4>SHOPPING BASKET</h4>
							<?php
					$response = $db->sidebar_cart(); //get cart
					echo $response;
					?>
								<?php// echo do_shortcode('[sidebar_cart]');?>

						</div>

					</div>
				</div>

		</section>
		<script>
			function productorder(productid) {
				//var id= $('#product' + productid).val();
				var datepicker = $('#datepicker').val();
				//var datepicker=$('#datepicker').val();
				if ($('#product' + productid).is(':checked')) {
					$('.loader_order').show();
					$.ajax({
						type: "POST",
						url: '<?php echo get_template_directory_uri();  ?>/ajax/addproduct.php',
						data: {
							prodid: productid,
							datepicker_ajax: datepicker
						},
						success: function(data) {
							$('#your_basket_id').load(document.URL + ' .woocommerce-cart-form', function(responseTxt, statusTxt, xhr) {
								if (statusTxt == "success") {
									$('.loader_order').hide();
								}
							});
							$('.countdynamic').load(document.URL + ' .header-cart-count', function(responseTxt, statusTxt, xhr) {
								if (statusTxt == "success") {
									$('.loader_order').hide();
								}
							});

							// $('.woocommerce-cart-form').load(document.URL +  ' .woocommerce-cart-form');
							$("body").on("click", "#datepicker", function() {
								$(this).datepicker({
									minDate: 0
								});
								$(this).datepicker("show");
							});
						}
					});
				} else {
					$('.loader_order').show();
					$.ajax({
						type: "POST",
						url: '<?php echo get_template_directory_uri();  ?>/ajax/removeproduct.php',
						data: {
							prodid: productid,
							datepicker_ajax: datepicker
						},
						success: function() {
							$('#your_basket_id').load(document.URL + ' .woocommerce-cart-form', function(responseTxt, statusTxt, xhr) {
								if (statusTxt == "success") {
									$('.loader_order').hide();
								}
							});
							$('.countdynamic').load(document.URL + ' .header-cart-count', function(responseTxt, statusTxt, xhr) {
								if (statusTxt == "success") {
									$('.loader_order').hide();
								}
							});

							$("body").on("click", "#datepicker", function() {
								$(this).datepicker({
									minDate: 0
								});
								$(this).datepicker("show");
							});
						}
					});
				}
			}

		</script>

		<script>
			$(document).ready(function() {
				$('input[name="apply_coupon"]').click(function(ev) {
					$form = $(evt.currentTarget);
					ev.preventDefault();
					/*       // Get the coupon code
		var code = jQuery( 'input#coupon_code').val();

		// We are going to send this for processing
		data = {
			action: 'spyr_coupon_redeem_handler',
			coupon_code: code
		}

		// Send it over to WordPress.
		jQuery.post( woocommerce_params.ajax_url, data, function( returned_data ) {
			if( returned_data.result == 'error' ) {
				jQuery( 'p.result' ).html( returned_data.message );
			} else {
				// Hijack the browser and redirect user to cart page
				window.location.href = returned_data.href;
			}
		})
 */
					// Prevent the form from submitting



					var cart = this;
					var $text_field = $('#coupon_code');
					var coupon_code = $text_field.val();

					var data = {
						security: wc_cart_params.apply_coupon_nonce,
						coupon_code: coupon_code
					};

					$.ajax({
						type: 'POST',
						url: get_url('apply_coupon'),
						data: data,
						dataType: 'html',
						success: function(response) {
							$('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
							show_notice(response);
							$(document.body).trigger('applied_coupon', [coupon_code]);
						},
						complete: function() {
							unblock($form);
							$text_field.val('');
							cart.update_cart(true);
						}
					});
				});
			});

		</script>
		<?php
 get_footer();
