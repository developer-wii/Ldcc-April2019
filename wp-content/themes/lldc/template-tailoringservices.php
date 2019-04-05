<?php
/* Template Name: Template tailoring services */

get_header();
?>
	<section>
		<div class="tailor_background">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="tailoring">
							<div class="tailoring_wrapper">
								<div class="tailoring_containt">
									<h1>
										<?php the_field('tailoring_services_txt'); ?>
									</h1>
								</div>
								<div class="tailoring_containt_line">
									<h3>
										<?php the_field('banner_desc'); ?>
									</h3>
								</div>

							</div>

						</div>

					</div>


				</div>
			</div>
		</div>

	</section>

	<section class="tailor_heading">
		<div class="container">
			<div class="row">
				<?php the_field('loram_header_txt'); ?>

				<?php
		$args=array(
			 'post_type'   => 'tailoringservices',
			 'post_status' => 'publish',
			 'posts_per_page' => -1,
			 'order'=> 'ASC'
		);
		 $tailoringservices = new WP_Query( $args );
		if( $tailoringservices->have_posts() ) :
			while( $tailoringservices->have_posts() ) :$tailoringservices->the_post();
			?>
					<div class="col-lg-3 col-md-4 col-sm-6 col-6">
						<div class="images_wrapper">
							<div class="tailor_img">
								<?php the_post_thumbnail(); ?>
							</div>
							<h3>
								<?php the_title(); ?>
							</h3>
						</div>
					</div>
					<?php
			endwhile;
			 wp_reset_postdata();
			endif;
		?>


						<!-- -->


			</div>
		</div>
	</section>

	<section class="contact_us">
		<div class="contact_wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-12">

						<?php the_field('contactus_txt'); ?>
						<div class="btn_all">
							<a href="<?php get_site_url(); ?>/contact-us/" class="btn_for_all" btn-responsive>
								<?php the_field('contact_button_txt'); ?>
							</a>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
get_footer();
