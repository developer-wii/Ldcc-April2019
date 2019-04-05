<?php 
/* Template Name: How it works page */
get_header();
//require_once( get_template_directory() . '/inc/class-registrations.php');
?>
<?php
//check delivery zones
$delivery_zones = WC_Shipping_Zones::get_zones();

 foreach ($delivery_zones as $key=>$the_zone ) {

 $locations=$the_zone['zone_locations'];
 foreach($locations as $location){
	 $location= $location->code;
	 if(stristr($location,'*')){		 
		$location = str_replace('*', '', $location);
		$locationarray[] = $location;
	}
	else{ 
		$locationarray[] = $location;
	}
 }	
} 	$implode_locations= implode(',',$locationarray);
?>

<section class="search_banner">
<div class="container">
<form action="/" method="get">
<!--input id="search" name="s" type="text" value="" placeholder="Search" /-->
<?php if(isset($_SESSION['orderpincode'])){ 
	echo '<input type="text" name="postcode" placeholder="Input your post code " id="postcode_order" value="'. $_SESSION['orderpincode'] .'" class="search"/>';
}
else{
	echo '<input type="text" name="postcode" placeholder="Input your post code " id="postcode_order" class="search"/>';
} ?>
<!--button id="searchsubmit" type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button-->
<button id="order_button" type="button" value="" class="searchsubmit"><i class="fa fa-search" aria-hidden="true"></i></button>
<input type="hidden" value="<?php echo $implode_locations; ?>" name="hidden_locations" id="hidden_locations"/>
</form>
<div id="text_orderlink"></div>
<?php if(!is_user_logged_in()){ ?>
		<a href="<?php echo get_site_url() ?>/login"><?php the_field('login'); ?></a> 
<?php } else { ?>
	<a href="<?php echo get_site_url() ?>/my-account"><?php the_field('my_account'); ?></a> 
<?php } ?>

</div>
</section>
<section class="how_it_works">
<div class="container">
<h4><?php the_field('how_it_works'); ?></h4>
<?php $image = get_field('how_it_works_desktop_image');
$image1 = get_field('how_it_works_mb_image1');
$image2 = get_field('how_it_works_mb_image2');
$image3 = get_field('how_it_works_mb_image3');
$image4 = get_field('how_it_works_mb_image4'); 
if( !empty($image) ): ?>
	<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="how_it_work_img"/>
<?php endif; ?>


<div class="mobile_section">
<div class="mobile_image_wrapper">
<?php if( !empty($image1) ): ?>
	<img src="<?php echo $image1['url']; ?>" alt="process"/>
<?php endif; ?>
</div>
<img src="http://londondrycleaningcompany.com/wp-content/uploads/2019/01/dashed_blue.png" alt="arrow-bottom" class="arrow_bottom_mbl">
<div class="mobile_image_wrapper">
<?php if( !empty($image2) ): ?>
	<img src="<?php echo $image2['url']; ?>" alt="process"/>
<?php endif; ?>
</div>
<img src="http://londondrycleaningcompany.com/wp-content/uploads/2019/01/dashed_blue.png" alt="arrow-bottom" class="arrow_bottom_mbl">
<div class="mobile_image_wrapper">
<?php if( !empty($image3) ): ?>
	<img src="<?php echo $image3['url']; ?>" alt="process"/>
<?php endif; ?>
</div>
<img src="http://londondrycleaningcompany.com/wp-content/uploads/2019/01/dashed_blue.png" alt="arrow-bottom" class="arrow_bottom_mbl">
<div class="mobile_image_wrapper last_mobile_img">
<?php if( !empty($image4) ): ?>
	<img src="<?php echo $image4['url']; ?>" alt="process"/>
<?php endif; ?>
</div>
</div>
</div>
</section>

<section class="client_riviews">
<div class="container">
<div class="row">
<div class="col-sm-6">
<?php $reviews=get_field('client_reviews_label'); ?>
<div class="client_review_wrap">
<h5 class="reviewtitle"><?php echo $reviews; ?></h5>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
	    <!-- Wrapper for slides --><p></p>
	<div class="carousel-inner">
	<?php 
		$args=array(
			 'post_type'   => 'testimonial',
			 'post_status' => 'publish',
		);
 		$testimonials = new WP_Query( $args );
		$i=1;
		if( $testimonials->have_posts() ) :
			 while( $testimonials->have_posts() ) :$testimonials->the_post();
				if($i==1){
					echo '<div class="review item active">';
				}
				else{
					echo '<div class="review item">';
				}
				?>
				
				<p><?php the_post_thumbnail('full', array('class' => 'client_img')); ?></p>
				<h6><?php the_title(); ?></h6>
				<p></p>
				<p><?php the_content(); ?></p>
			<?php 
			$rating_round=get_field('ratings');
		if ($rating_round =='' || $rating_round ==0) {
			echo '<p><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>';
		}			
		if ($rating_round <= 0.5 && $rating_round > 0) {
			echo '<i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
		}
		if ($rating_round <= 1 && $rating_round > 0.5) {
			echo '<p><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>';
		}
		if ($rating_round <= 1.5 && $rating_round > 1) {
			echo '<p><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>';
		}
		if ($rating_round <= 2 && $rating_round > 1.5) {
			echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>';
		}
		if ($rating_round <= 2.5 && $rating_round > 2) {
			echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>';
		}
		if ($rating_round <= 3 && $rating_round > 2.5) {
			echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>';
		}
		if ($rating_round <= 3.5 && $rating_round > 3) {
			echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i></p>';
		}
		if ($rating_round <= 4 && $rating_round > 3.5) {
			echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i></p>';
		}
    if ($rating_round <= 4.5 && $rating_round > 4) {
        echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></p>';
    }
    if ($rating_round <= 5 && $rating_round > 4.5) {
        echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></p>';
    }
	?>
				</div>
				<?php 
				$i++;
			 endwhile;
			 wp_reset_postdata();
		endif; 

	?>
	</div>
	<p>    <!-- Left and right controls --></p>
	<div>
	        <a class="left carousel-control" href="#myCarousel" data-slide="prev"><br>
	            <span class="glyphicon glyphicon-chevron-left"></span><br>
	            <span class="sr-only">Previous</span><br>
	        </a><br>
	        <a class="right carousel-control" href="#myCarousel" data-slide="next"><br>
	            <span class="glyphicon glyphicon-chevron-right"></span><br>
	            <span class="sr-only">Next</span><br>
	        </a>
	    </div>
	</div>
</div>

</div>
<div class="col-sm-6">
<div class="video_wrapper_div"></div>
<?php the_field('about-video'); ?>
</div>
</div>
</div>
</section>
<section class="our_coverage_area">
   <h4><?php the_field('how_it_works'); ?></h4>
  <?php the_field('contact_map'); ?>
   <div class="container">
      <h5><?php the_field('custom_heading'); ?></h5>
	  <?php if(!is_user_logged_in()){ ?>
      <a href="<?php echo get_site_url() ?>/user-registration" class="create_my_account"><?php the_field('create_account'); ?></a>
	  <?php } else { ?>
	<a href="<?php echo get_site_url() ?>/my-account" class=
	"create_my_account"><?php the_field('my_account'); ?></a> 
<?php } ?>
   </div>
</section>
<?php get_footer(); ?>