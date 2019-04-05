<?php
/* Template Name: Alter & repairs */
get_header();
global $current_user;
$user_roles=$current_user->roles;
?>
	<section class="alter_section">

		<div class="alter_section_banner text-center">

			<div class="container">
				<div class="banner-text">
					<div class="banner-text-main">
						<p>
							<?php the_title(); ?>
						</p>
					</div>
				</div>

			</div>
		</div>


		<div class="alter_list">


			<div class="list-detail text-center">
				<div class="container">
					<div class="row">
						<?php

				$args = array(
				  'post_type'   => 'alterrepairs',
				  'post_status' => 'publish',
					'posts_per_page'=>3,
					'order' => 'ASC',
				 );
				$all_categories = new WP_Query( $args );
				if( $all_categories->have_posts() ) :
				?>

							<?php
					  while( $all_categories->have_posts() ) :
						$all_categories->the_post();
						?>

								<div class="col-md-12">
									<div class="inner-text">
										<div class="main-text">
											<h3>
												<?php the_field('main_header_text'); ?>
											</h3>
										</div>
										<div class="sub-text">
											<h5>
												<?php the_field('sub_header_text'); ?>
											</h5>
										</div>
									</div>
								</div>
								<?php
					  endwhile;
					  wp_reset_postdata();
					?>
									<?php
				else :
				  esc_html_e( 'No Data Available!', 'text-domain' );
				endif;
				?>
					</div>
				</div>



			</div>

		</div>
	</section>


	<?php get_footer(); ?>
