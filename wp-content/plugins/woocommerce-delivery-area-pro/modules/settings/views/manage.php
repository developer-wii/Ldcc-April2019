<?php
$form  = new WDAP_FORM();
echo $form->show_header();
?>
<div class="flippercode-ui">

 <div class="fc-main">

  <div class="fc-container">

     <div class="fc-divider">

		 <div class="fc-back fc-how">
		 	
		<div class="fc-12">
			<h4 class="fc-title-blue"><?php _e('Woocommerce Delivery Area Pro Plugin Documentation',WDAP_TEXT_DOMAIN) ?> </h4>
			<div class="fc-overview">
				<p class="fc-overview" style="margin-left:1em;"><?php _e('Below are the detailed instructions to get you started with the plugin.',WDAP_TEXT_DOMAIN); ?>
				</p>	
			</div>

		<ol>

		 <li>

			<p class="fc-overview"><?php _e('<b> Creating New Collection :</b> </span> The very first step is to create a collection. A collection is an association between products and its delivery area and works as a rule for possibility of delivery of product. Create a new collection from <a href="'.admin_url('admin.php?page=wdap_add_collection').'" target="_blank">Here</a>. According to your requirements you can add selected products or all products in this collection. ',WDAP_TEXT_DOMAIN); ?></p></li>

			<li>

			<p class="fc-overview"><?php _e('<b>Define Delivery Area : </b>Now after adding products, next step is to define delivery area for products added in above step. You can enter zipcodes directly if you want to be more specific with delivery areas. You can also select a country or sub-continent or continent as a whole if you deliver in these area as whole. Also on google map, you can draw polygons to represent your delivery area.  This polygon drawing will be displayed on frontend under "product availability tab" on product page. You can display beautiful customised polygons by customising its properties. Start customizing polygons properties by clicking on it. You can assign new colors to polygons, change fill opacity, stroke width, stroke color etc. You can also choose to show a infowindow or to redirect on specific url after a user click on polygon. If you want to remove any polygon, you can easily delete it by clicking on delete icon. <br><br>If you deliver into a specific range of kilometers only from your store address, we\'ve got you covered by providing a method for this. Admin has an option to specify store location and kilometers in range from backend. When user will provide his delivery address on frontend, distance will be calculated betwewn these two locations. If its within the kilometers range specified in backend that means delivery can be done otherwise user will see a message product can\'t be delivered. But this method works only on delivery area form which is rendered by shortcode and which has autosuggest. This method does not work on woocommerce pages.<br><br> Usecase for this method : You can specify store location and kilometers range from backend in a collection and then display delivery area form on the homepage/frontpage of your website with help of shortcode. So this collections will work for homepage delivery area form exclusively. For specifying delivery area rules for woocommerce pages (product,shop) etc you can create another new collection and specify delivery area by any of other methods provided like by zipcode or by country or by drawing on google map. So this collection will handles requests from woocommerce pages.',WDAP_TEXT_DOMAIN)?></p></li>	
			<li>

			<p class="fc-overview"><?php _e("<b>Select Pages To Display Delivery Area Form On Woocommerce Pages</b>: </span> For checking product availability / delivery site visitors will see a delivery area form on frontend with a textbox to enter zipcode and a button. Users can easily check delivery of product with help of this form. Admin can allow display delivery area form on specific / all four woocommerce pages according to <a href='".admin_url('admin.php?page=wdap_setting_settings')."' target='_blank'>plugin settings</a> Site visitors will see appropriate messages every time they checked for delivery of product by entering zipcode.",WDAP_TEXT_DOMAIN); ?></p></li>

			

			<li>

			<p class="fc-overview"><?php _e('<b>Displaying Your Delivery Areas On Google Map : </b>Site admins can also display their store\'s delivery area on google map in an interactive way and this map can be displayed anywhere on the website with helo of shortcode. Setup general settings to display your delivery areas on google map on <a href='.admin_url('admin.php?page=wdap_setting_settings').' target="_blank">plugin settings</a> page. You can make map much attractive by adding snazzy maps styling. Zipcodes entered in step 1 or drawn polygons in listing rules, both will be displayed on the frontend  product availability map automatically. Google Map Key is necessary to show map and check product availability in polygon area. ',WDAP_TEXT_DOMAIN)?></p></li>

			<li>

			<p class="fc-overview"><?php _e("<b>Restrict Country </b>:</span> Not a mandatory step. This feature is useful in case the website delivers its product in a particular country only. When store owners enable this feature, and user enquires for delivery of product by providing zipcode on woocommerce page, user will get results more fastly. This feature is recommended for website shipping their products into a single country only.",WDAP_TEXT_DOMAIN)?></p></li>

			<li>
 			<p class="fc-overview"><?php _e("<b>Restrict Orders </b>:</span> Not a mandatory step. You can restrict users, if you want on checkout form to put an order if any of the product added in their cart is not ready to shipped in area of which the user enters the zipcode in the zipcode field of default woocommerce checkout form. Checkout Process will proceed only when every single product added in cart is ready to be shipped in the area that user specify by entering zipcode in default checkout form.",WDAP_TEXT_DOMAIN)?></p></li>


 			<li>
 			<p class="fc-overview"><?php _e("<b>Shortcode Enquiry Form </b>:</span> You could use shortcode for checking product availability on any page, post and widget using Shortcode:-<b> [delivery_area_form] </b>. You could enable auto location detection and product listing to check special product availability on your address. ",WDAP_TEXT_DOMAIN)	?></p></li>

 			<li>
 			<p class="fc-overview"><?php _e("<b>Redirect After Success And Error Message From Shortcode Enquiry Form </b>:</span> Now you could set success and error redirection instead of showing messages. Redirection will affective only from shortcode enquiry form. You could save redirection URL from <a href='".admin_url('admin.php?page=wdap_setting_settings')."' target='_blank'>Plugin Settings</a> page.  ",WDAP_TEXT_DOMAIN)	?></p></li>


 			<li>

 			<p class="fc-overview"><?php _e('<b>Display All Delivery Areas On Google Map </b>:</span> You can display all delivery areas of your store on map by using following shortcode : <b> [delivery_areas],[delivery_areas product_id="40"] </b>. This shortcode will draw all drawings and markers on google map. ', WDAP_TEXT_DOMAIN)	?></p></li>
 			<li>
 			<p class="fc-overview"><?php _e("<b>Show/Hide Zipcode Testing Form </b>:</span> You could show/hide zipcode availability form of any specific product on product and shop page. You could do this through product edit screen in backend.",WDAP_TEXT_DOMAIN)?></p></li>
 			<li>
 			
 			<p class="fc-overview"><?php _e("<b>Show/Hide Product Availability Tab </b>:</span> You could show/hide product availability tab of any specific product on product page. You could do this through Product edit screen in backend. If you want to hide product availability tab on all product, you can do it from <a href='".admin_url('admin.php?page=wdap_setting_settings')."' target='_blank'>Plugin Settings</a> and enable 'Disable Product Availability' checkbox.",WDAP_TEXT_DOMAIN)?></p></li>
 			<li>
 			
 			<p class="fc-overview"><?php _e("<b>Update Product in Any Selected Type Collections </b>:</span> You could update any product in selected type collections from product edit screen. Updated Products will show in selected products list on manage collections page under updated collection. ",WDAP_TEXT_DOMAIN)?></p></li>

		</ol>	

		 <p style="margin-left:1em;"><?php _e('If you have still any issue, Create your <a target="_blank" href="http://www.flippercode.com/forums">support ticket</a> and we would be happy to help you asap.',WDAP_TEXT_DOMAIN); ?> </p>

		</div>

	</div>

</div>

	</div>

</div>

</div>

