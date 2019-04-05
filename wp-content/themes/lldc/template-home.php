<?php 
/* Template Name: Home */

get_header();

 ?>

<div class="banner">
<div class="contentB">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
the_content();
endwhile; ?>
<?php endif; ?>

<div class="order_input">
<form method="POST">
<?php if(isset($_SESSION['orderpincode'])){ 
	echo '<input type="text" name="postcode" placeholder="Input your post code " id="postcode_order" value="'. $_SESSION['orderpincode'] .'"/>';
}
else{
	echo '<input type="text" name="postcode" placeholder="Input your post code " id="postcode_order"/>';
} ?>
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
<input type="button" value="Order Online" id="order_button" name="submit_home" />
<input type="hidden" value="<?php echo $implode_locations; ?>" name="hidden_locations" id="hidden_locations"/>

</form>
</div>
<div id="text_orderlink"></div>
<div class="rgstr-liks">
<p><a href="<?php echo get_site_url();?>/user-registration/">Register</a> | <a href="<?php echo get_site_url();?>/login">Login</a></p>
<p class="widgetRws"><img src="<?php echo get_site_url();?>/wp-content/themes/lldc/images/star.png" />Review widget</p>

</div>
</div>
</div>
	
 <?php
 get_footer();?>
