<?php
/* Template Name:  Monthly Plan */
get_header();
global $current_user;
$user_roles=$current_user->roles; ?>
<div class="wcn">
</div>

	<section class="stdnt_pln_section">

		<div class="stdnt_pln_banner text-center">

			<div class="container">

				<div class="monthly_heading">
					<figure class="laundry_img"><img src="<?php echo get_site_url();?>/wp-content/uploads/2018/09/monthly_banner_img.png" /></figure>
					<div class="banner_text">
						<h5>
							<?php the_field('banner_text1'); ?>
						</h5>
						<h4>
							<?php the_field('banner_text2'); ?> </h4>
					</div>
				</div>


			</div>
		</div>


		<div class="student_plan_list">
			<div class="container">
				<div class="row">

					<?php
				/* get product categories code start*/
				 $get_featured_cats = array(
					'product_cat' => 'monthly-plan-packages',
					 'orderby'      => 'name',
				//	'include'      => array(31),
					'post_type' => 'product',
					'posts_per_page' => -1,
				);

				/* $args = array(
				  'post_type'   => 'monthlyplans',
				  'post_status' => 'publish',
				  'posts_per_page'=>-1
				 ); */
				$all_categories = new WP_Query( $get_featured_cats );
				//$all_categories = get_categories( $get_featured_cats );
				if( $all_categories->have_posts() ) :
				?>

						<?php
					  while( $all_categories->have_posts() ) :
						$all_categories->the_post();
						?>
<?php $currency = get_woocommerce_currency_symbol();
$price = get_post_meta( $post->ID, '_regular_price', true);
$sale = get_post_meta( $post->ID, '_sale_price', true);
?>

<?php if($price!='') { ?>
	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 text-center img-responsive">
		<div class="cstm_images">
			<?php the_post_thumbnail( 'medium' ) ; ?><br>
			<a href="<?php the_permalink(); ?>"><span class="plan-title text-center"><?php the_title(); ?></span></a>


			<?php if($sale!=''){ ?>
			<span id="rate-regplan"><del><?php echo $currency.$sale; ?><del></span>
			<?php } ?>
			<span id="rate-saleplan"></del><?php echo $currency.$price; ?><del></span>
			
			<!-- <form class="cart" action="" method="post" enctype="multipart/form-data"> -->

				<div class="quantity">
					<input class="minus" type="button" value="-">
					<input type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">
					<input class="plus" type="button" value="+">
				</div>

				<button type="submit" name="add-to-cart" value=<?php echo $post->ID; ?> class="single_add_to_cart_button add_to_cart_custom_buton_ajax button alt" id="custom-cart">Add to cart</button>
				<!-- add_to_cart_custom_buton_ajax -->

			<!-- </form> -->
			
		</div>

	</div>
<?php } ?>

							<?php
					  endwhile;
					  wp_reset_postdata();
					?>
								<?php
				else :
				  esc_html_e( 'No Monthly plans found!', 'text-domain' );
				endif;
				?>

				</div>
			</div>
		</div>
		<?php
/* Create account button and text */
 if((!is_user_logged_in()) || (in_array( 'administrator', $user_roles, true ))){ ?>
			<div class="row text-center">
				<a href="<?php echo get_site_url(); ?>/user-registration" class="cstm-btn">
					<?php echo the_field('create_acc-text1'); ?>
				</a>
			</div>

			<div class="row text-center register-link">
				<p>
					<?php echo the_field('create_acc_text2');?> </p>

			</div>
			<?php } ?>
	</section>

	<?php get_footer(); ?>
