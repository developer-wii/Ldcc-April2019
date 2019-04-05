<?php 

/*
*
*Template Name: Contact Us
*
*
*/


get_header(); ?>


<div id="content" class="site-content contact">
  <section class="contact_section">
	  	<div class="corporate_banner text-center">
			<div class="container">
			   <h1><?php the_field('banner_title'); ?></h1>
			   <p><?php the_field('banner_tagline'); ?></p>
			</div>
		</div>
        <div class="cnt_text">
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
        				<div class="sec_cnt text-center">
        					<h3><?php the_field('contact_title'); ?></h3>
        					<p><?php the_field('contact_content'); ?></p>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="form_map">
        	<div class="container">
        		<div class="row">
        			<div class="col-md-6">
        				<div class="form_div">
        					<h4 class="text-center"><?php the_field('form_title'); ?></h4>
    					    <?php echo do_shortcode('[contact-form-7 id="501" title="Contact Page Form"]'); ?>
        				</div>
        			</div>
        			<div class="col-md-6">
        				<div class="address">
        				   <div class="site_address">
        				      <?php the_field('address_content'); ?>
        				   </div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="map_location">
        	<div class="container">
        		<div class="row">
        		    <div class="col-md-12">
        		    	<div class="title text-center">
        				    <h3><?php the_field('map_title'); ?></h3>
        			    </div>
        		    </div>
        		</div>
        	</div>
        	<div class="iframe_map">
			    <?php the_field('contact_map'); ?>
			</div>
        </div>
  </section>
</div>

<?php get_footer(); ?>

