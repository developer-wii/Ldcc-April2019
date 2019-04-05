<?php

/**
 * Plugin Setting page for wp-delivery-area-pro.
 *
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.1
 * @package woo-delivery-area-pro
 */
?>
<div class="se-pre-con"></div>

<?php 
		$form  = new WDAP_FORM();
		$form->set_header( __( 'Delivery Area Enquiry Form Settings', WDAP_TEXT_DOMAIN ), $response );
		$data = maybe_unserialize(get_option('wp-delivery-area-pro'));
		$apply_on = array(
		 'product_page' => __( 'On Product Page',WDAP_TEXT_DOMAIN ),
		 'shop_page' => __( 'On Shop Page ',WDAP_TEXT_DOMAIN ),
		 'cart_page' => __( 'On Cart Page',WDAP_TEXT_DOMAIN ),
		 'checkout_page' => __( 'On Checkout page',WDAP_TEXT_DOMAIN ),

		 );

		$form->add_element( 'multiple_checkbox', 'apply_on[checkedvalue][]', array(
		  'lable' => __( 'Display Delivery Enquiry Form', WDAP_TEXT_DOMAIN ),
		  'value' => $apply_on,
		  'current' => $data['apply_on']['checkedvalue'],
		  'class' => 'chkbox_class ',
		  'desc' => __( 'Please select woocommerce pages.', WDAP_TEXT_DOMAIN ),
		  'default_value' => 'product_page',

		 ));

		 $form->add_element( 'group', 'map_general_settings', array(
			'value' => __( 'Product Availability Map ( On Product Page )', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',
			'desc' => __( 'This map will be displayed on product page in a new tab to display all the locations where the current product can be delivered.', WDAP_TEXT_DOMAIN ),

		));

		$form->add_element( 'text', 'wdap_googleapikey', array(
		'lable' => __( ' Enter Google Map API Key', WDAP_TEXT_DOMAIN ),
		'value' => isset( $data['wdap_googleapikey'] ) ? $data['wdap_googleapikey'] :'',
		'desc' => __( 'You need to get an api key for google map to work with your website. You can read and follow <b><a href="https://www.linkedin.com/pulse/important-changes-google-maps-api-v3-website-owners-sandeep-kumar" target="_blank">This</a></b> link to get api keys.', WDAP_TEXT_DOMAIN ),
		'class' => 'form-control',
		'placeholder' => 'Enter Google Map Key',
		'before' => '<div class="fc-6" >',
		'after' => '</div>',
		));

		$form->add_element( 'text', 'wdap_map_width', array(

		'lable' => __( ' Enter Google Map Width', WDAP_TEXT_DOMAIN ),

		'value' => isset( $data['wdap_map_width'] ) ? $data['wdap_map_width'] :'',
		'class' => 'form-control',

		'placeholder' => 'Enter Google Map Width',

		'required'=>true,

		'before' => '<div class="fc-6" >',

		'after' => '</div>',
		));

		$form->add_element( 'text', 'wdap_map_height', array(

		'lable' => __( ' Enter Google Map height', WDAP_TEXT_DOMAIN ),

		'value' => isset( $data['wdap_map_height'] ) ? $data['wdap_map_height'] :'',

		'class' => 'form-control',

		'required'=>true,

		'placeholder' => 'Enter Google Map Height',

		'before' => '<div class="fc-6" >',

		'after' => '</div>',

		));

		$form->add_element( 'number', 'wdap_map_zoom_level', array(

		'lable' => __( ' Enter Google Map Zoom Level', WDAP_TEXT_DOMAIN ),

		'value' => isset( $data['wdap_map_zoom_level'] ) ? $data['wdap_map_zoom_level'] :'',

		'class' => 'form-control',

		'placeholder' => 'Enter Google Map Zoom Level',

		'before' => '<div class="fc-6" >',

		'after' => '</div>',

		'default_value'=>5

		));

		$form->add_element( 'text', 'wdap_map_center_lat', array(

		'lable' => __( ' Enter Map Center Latitude', WDAP_TEXT_DOMAIN ),

		'value' => isset( $data['wdap_map_center_lat'] ) ? $data['wdap_map_center_lat'] :'',

		'class' => 'form-control',

		'placeholder' => 'Enter Map Center Latitude',

		'before' => '<div class="fc-6" >',

		'after' => '</div>',

		'default_value'=>40.730610

		));

		$form->add_element( 'text', 'wdap_map_center_lng', array(
			'lable' => __( ' Enter Map Center Longitude', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_map_center_lng'] ) ? $data['wdap_map_center_lng'] :'',
			'class' => 'form-control',
			'placeholder' => 'Enter Map Center Longitude',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>-73.935242
		));

		$form->add_element( 'textarea', 'wdap_map_style', array(
			'lable' => __( ' Enter Snazzy Map Google Map Style', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_map_style'] ) ? $data['wdap_map_style'] :'',
			'class' => 'form-control',
			'placeholder' => 'Enter Snazzy Map Google Map Style',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
		));
		$form->add_element( 'checkbox', 'enable_map_bound', array(
			'lable' => __( 'Enable Map Bound', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'current' => isset( $data['enable_map_bound'] ) ? $data['enable_map_bound'] : '',
			'desc' => __( 'YES', WDAP_TEXT_DOMAIN ),
			'default_value' => 'true',
		));		
		$form->add_element( 'checkbox', 'enable_markers_on_map', array(
			'lable' => __( 'Enable Markers on Map', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'current' => isset( $data['enable_markers_on_map'] ) ? $data['enable_markers_on_map'] : '',
			'desc' => __( 'YES', WDAP_TEXT_DOMAIN ),
			'default_value' => 'true',
		));		
		$form->add_element( 'checkbox', 'enable_polygon_on_map', array(
			'lable' => __( 'Enable Polygons on Map', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'current' => isset( $data['enable_polygon_on_map'] ) ? $data['enable_polygon_on_map'] : '',
			'desc' => __( 'YES', WDAP_TEXT_DOMAIN ),
			'default_value' => 'true',
		));

		$form->add_element( 'group', 'wdap_countries_restriction', array(
			'value' => __( 'Perform Searching WithIn A Specific Country', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',
		));

		$form->add_element( 'checkbox', 'enable_retrict_country', array(
			'lable' => __( 'Enable Country Restriction', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'id' => 'date_filters',
			'current' => isset( $data['enable_retrict_country'] ) ? $data['enable_retrict_country'] : '',
			'desc' => __( 'YES', WDAP_TEXT_DOMAIN ),
			'class' => 'chkbox_class keep_aspect_ratio switch_onoff',
			'data' => array( 'target' => '.enable_retrict_countries'),
			'default_value' => 'true',
		));

		$countries_obj   = new WC_Countries();
    	$countries   = $countries_obj->__get('countries');
		$newchoose_continent = array();
		foreach ( $countries as  $key => $values ) {

			 $newchoose_continent[] = array( 'id' => $key , 'text' => $values );

		}
		$selected_restricted_countries = $data['wdap_country_restriction_listing'];

		$form->add_element( 'category_selector', 'wdap_country_restriction_listing', array(
			'lable' => __( 'Choose Country',WDAP_TEXT_DOMAIN ),
			'data' => $newchoose_continent,
			'current' => (isset( $selected_restricted_countries ) and ! empty( $selected_restricted_countries )) ? $selected_restricted_countries : '',
			'desc' => __( 'Some places of different counties have same zipcodes. If your product delivery area falls under such category, you can specify your country here. By this google api will provide quick and more accurate results without confliction with similar zipcode of other country. Useful only if you are not specifying zipcodes directly in textbox.', WDAP_TEXT_DOMAIN ),

			'class' => 'enable_retrict_countries',
			'before' => '<div class="fc-9">',
			'after' => '</div>',
			'multiple' => 'false',
			'show'=>'false',		

		));

		$form->add_element( 'checkbox', 'enable_places_to_retrict_country_only', array(
			'lable' => __( 'Display Places Of Restricted Country Only', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'id' => 'enable_places_to_retrict_country_only',
			'current' => isset( $data['enable_places_to_retrict_country_only'] ) ? $data['enable_places_to_retrict_country_only'] : '',
			'desc' => __( 'When country restriction is enabled, display places of restricted country only in autosuggest textbox to user.', WDAP_TEXT_DOMAIN ),
			'class' => 'chkbox_class',
		));
		
		$form->add_element( 'group', 'wdap_order_restriction', array(
			'value' => __( 'Enable Order Restriction On Checkout Form', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',

		));

		$form->add_element( 'checkbox', 'enable_order_restriction', array(
			'lable' => __( 'Enable Order Restriction ', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'id' => 'date_filters',
			'current' => isset( $data['enable_order_restriction'] ) ? $data['enable_order_restriction'] : '',
			'desc' => __( 'YES', WDAP_TEXT_DOMAIN ),
			'class' => 'chkbox_class keep_aspect_ratio ',
			'default_value' => 'true',
		));


		$checkout_method = array(
		'via_zipcode' => __( 'Via Zipcode',WDAP_TEXT_DOMAIN ),
		);
		if(!empty($data['wdap_googleapikey'])){
			$checkout_method['via_address']=__( 'Via Address',WDAP_TEXT_DOMAIN );
		}
		
		$form->add_element( 'radio', 'wdap_checkout_avality_method', array(
			'lable' => __( 'Zipcode/Address For Checking On Checkout Page', WDAP_TEXT_DOMAIN ),
			'current' => ( isset( $data ['wdap_checkout_avality_method'] ) and ! empty( $data ['wdap_checkout_avality_method'] ) )? $data ['wdap_checkout_avality_method'] : $_POST['wdap_checkout_avality_method'],
			'radio-val-label' => $checkout_method,
			'default_value' => 'via_zipcode',
			'desc' => __( 'Checking of delivery will be decided based on this option. If via zipcode is selected, zipcode will be taken from the default woocommerce zipcode field and will be used in testing and message will be shown accordingly. if via address is selected, billing address is used for checking delivery status in that area(address). Via Zipcode is recommended way to check for delivery on checkout page.',WDAP_TEXT_DOMAIN )
		));



		$form->add_element( 'group', 'wdap_error_message', array(
			'value' => __( 'Manageable Messages For Frontend', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',
		));
		$errormessage=array(
				'notavailable'=>__("Product Not Available ",WDAP_TEXT_DOMAIN),
				'available'=>__("Product Available ", WDAP_TEXT_DOMAIN),
				'invalid'=>__("Invalid Zipcode ",WDAP_TEXT_DOMAIN),
				'th'=>__(" Product Availability Status",WDAP_TEXT_DOMAIN),
		);
		foreach ($errormessage as $key => $message) {
			$placeholder=$message;
			$desc= '';
			if($key=='th'){ $placeholder =__('Availability Status',WDAP_TEXT_DOMAIN);$desc= __( 'Shop Table Heading', WDAP_TEXT_DOMAIN ); }

			$form->add_element( 'text', 'wdap_error_'.$key, array(
				'lable' => __($message, WDAP_TEXT_DOMAIN ),
				'value' => isset( $data['wdap_error_'.$key] ) ? $data['wdap_error_'.$key] :'',
				'desc' =>$desc,
				'class' => 'form-control',
				'placeholder' =>  $placeholder,
				'before' => '<div class="fc-6" >',
				'after' => '</div>',
				'default_value' => $message
			));
		}

		$form->add_element( 'text', 'wdap_empty_zip_code', array(
			'lable' => __( 'Empty Zipcode Error', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_empty_zip_code'] ) ? $data['wdap_empty_zip_code'] :'',
			'class' => 'form-control',
			'placeholder' => __('Please enter zip code.',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=> __('Please enter zip code.',WDAP_TEXT_DOMAIN)
		));

		$form->add_element( 'text', 'wdap_order_restrict_error', array(
			'lable' => __( 'Order Restriction Error Message', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_order_restrict_error'] ) ? $data['wdap_order_restrict_error'] :'',
			'class' => 'form-control',
			'placeholder' => __('We could not complete your order due to Zip Code Unavailability.',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('We could not complete your order due to Zip Code Unavailability.',WDAP_TEXT_DOMAIN)
		));

		//End of Delivery Notifications
		$form->add_element( 'group', 'wdap_avl_button_settings', array(
			'value' => __( 'Delivery Area Form UI Settings', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',
		));

		$form->add_element( 'checkbox', 'disable_availability_tab', array(
			'lable' => __( 'Disable Product Availability', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'current' => isset( $data['disable_availability_tab'] ) ? $data['disable_availability_tab'] : '',
			'desc' => __( 'Disable product availability tab on all products', WDAP_TEXT_DOMAIN ),
			'class' => 'chkbox_class  ',
		));



		$form->add_element( 'text', 'wdap_check_buttonlbl', array(
			'lable' => __( 'Button Label', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_check_buttonlbl'] ) ? $data['wdap_check_buttonlbl'] :'',
			'class' => 'form-control',
			'placeholder' => __('Check Availability', WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('Check Availability',WDAP_TEXT_DOMAIN)
		));
		$form->add_element( 'text', 'wdap_frontend_desc', array(
			'lable' => __( 'Description', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_frontend_desc'] ) ? $data['wdap_frontend_desc'] :'',
			'class' => 'form-control',
			'placeholder' => __('Verify your pincode for correct delivery details',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('Verify your pincode for correct delivery details',WDAP_TEXT_DOMAIN)
		));

		$form->add_element( 'text', 'avl_button_color', array(

			'lable' => __( 'Button Text Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['avl_button_color'] ) ? $data['avl_button_color'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value' => '#fff',
		));

		$form->add_element( 'text', 'avl_button_bgcolor', array(
			'lable' => __( 'Button Background Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['avl_button_bgcolor'] ) ? $data['avl_button_bgcolor'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value' => '#a46497',
		));

		$form->add_element( 'text', 'success_msg_color', array(
			'lable' => __( 'Success Message Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['success_msg_color'] ) ? $data['success_msg_color'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value' => '#209620',
		));

		$form->add_element( 'text', 'error_msg_color', array(
			'lable' => __( 'Error Message Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['error_msg_color'] ) ? $data['error_msg_color'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value' => '#ff0000',
		));

		$form->add_element( 'group', 'product_delivery_area-zipcode', array(
			'value' => __( 'Choose Template For Delivery Area Enquiry Form', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',
		));

		$form->add_element( 'templates', 'wdap_zip_form_design', array(
			'id' => 'wdap_zip_form_design',
			'before' => '<div class="fc-12">',
			'after' => '</div>',
			'product' => 'wp-delivery-area-pro',
		    'instance' => 'wdap',
		    'dboption' => 'wp-delivery-area-pro',
		    'template_types' => array('zipcode'),
		    'templatePath' => WDAP_TEMPLATES,
		    'templateURL' => WDAP_TEMPLATES_URL,
		    'settingPage' => 'wdap_setting_settings',
		    'customiser' => 'false'
		));

		$form->add_element( 'group', 'product_delivery_area', array(
			'value' => __('Choose Template For Delivery Area Enquiry Form (Shortcode)', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',
		));

		$form->add_element( 'templates', 'wdap_shortcode_form_design', array(
			'id' => 'wdap_shortcode_form_design',
			'before' => '<div class="fc-12">',
			'after' => '</div>',
			'product' => 'wp-delivery-area-pro',
		    'instance' => 'wdap',
		    'dboption' => 'wp-delivery-area-pro',
		    'template_types' => array('shortcode'),
		    'templatePath' => WDAP_TEMPLATES,
		    'templateURL' => WDAP_TEMPLATES_URL,
		    'settingPage' => 'wdap_setting_settings',
		    'customiser' => 'false'
		));

		ob_start();
		echo do_shortcode('[delivery_area_form]');
		$preview = ob_get_contents();
		ob_clean();
		$form->add_element( 'html', 'shortcode_preview', array(
			'lable' => __( 'Form Preview', WDAP_TEXT_DOMAIN ),
			'html' =>  $preview,
			'before' => '<div class="fc-9">',
			'after' => '</div>',
			'class' => 'email_template_preview custom_email_template_control',
			'desc' => __('Form Preview Will Appear Here.',WDAP_TEXT_DOMAIN)
		));

	  	$form->add_element( 'text', 'shortcode_form_title', array(
			'lable' => __( 'Delivey Area Search Title', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_form_title'] ) ? $data['shortcode_form_title'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Delivery Area Form Title',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
		));
		$form->add_element( 'text', 'check_buttonPlaceholder', array(
			'lable' => __( 'Delivey Area Search Placeholder', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['check_buttonPlaceholder'] ) ? $data['check_buttonPlaceholder'] :'',
			'class' => 'form-control',
			'placeholder' => __('Delivey Area Search Placeholder ',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
		));
		$form->add_element( 'text', 'shortcode_form_description', array(
			'lable' => __( 'Delivery Area Form Description', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_form_description'] ) ? $data['shortcode_form_description'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Delivery Area Form Description',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
		));


		$form->add_element( 'text', 'wdap_address_empty', array(

			'lable' => __( 'Empty Address Message', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_address_empty'] ) ? $data['wdap_address_empty'] :'',
			'class' => 'form-control',
			'placeholder' => __('Please enter your address.',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('Please enter your address.',WDAP_TEXT_DOMAIN)
		));
		$form->add_element( 'text', 'address_not_shipable', array(

			'lable' => __( 'Not Shipping Area Message', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['address_not_shipable'] ) ? $data['address_not_shipable'] :'',
			'class' => 'form-control',
			'placeholder' => __('Sorry, We do not provide shipping in this area.',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('Sorry, We do not provide shipping in this area.',WDAP_TEXT_DOMAIN)

		));


		$form->add_element( 'text', 'address_shipable', array(

			'lable' => __( 'Shipping Area Message', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['address_shipable'] ) ? $data['address_shipable'] :'',
			'class' => 'form-control',
			'placeholder' => __('Yes, We provide shipping in this area.',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('Yes, We provide shipping in this area.',WDAP_TEXT_DOMAIN)
		));
		$form->add_element( 'text', 'form_success_msg_color', array(

			'lable' => __( 'Success Message Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['form_success_msg_color'] ) ? $data['form_success_msg_color'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value' => '#209620',

		));
		$form->add_element( 'text', 'form_error_msg_color', array(

			'lable' => __( 'Error Message Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['form_error_msg_color'] ) ? $data['form_error_msg_color'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value' => '#ff0000',

		));
		$form->add_element( 'text', 'wdap_form_buttonlbl', array(

			'lable' => __( 'Button Label', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['wdap_form_buttonlbl'] ) ? $data['wdap_form_buttonlbl'] :'',
			'class' => 'form-control',
			'placeholder' => __('Check Availability', WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('Check Availability',WDAP_TEXT_DOMAIN)
		));
		$form->add_element( 'text', 'form_button_color', array(

			'lable' => __( 'Button Text Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['form_button_color'] ) ? $data['form_button_color'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'id'    =>'form_button_color',
			'after' => '</div>',
			'default_value' => '#fff',
		));

		$form->add_element( 'text', 'form_button_bgcolor', array(

			'lable' => __( 'Button Background Color', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['form_button_bgcolor'] ) ? $data['form_button_bgcolor'] :'',
			'class' => 'form-control scolor color',
			'before' => '<div class="fc-6" >',
			'id'    =>'form_button_bgcolor',
			'after' => '</div>',
			'default_value' => '#a46497',
		));
		$form->add_element( 'checkbox', 'enable_locate_me_btn', array(

			'lable' => __( 'Enable Locate Me Button ', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'current' => isset( $data['enable_locate_me_btn'] ) ? $data['enable_locate_me_btn'] : '',
			'class' => 'chkbox_class ',
			'default_value' => 'true',

		));

		$form->add_element( 'checkbox', 'enable_product_listing', array(

			'lable' => __( 'Enable Product Listing ', WDAP_TEXT_DOMAIN ),
			'value' => 'true',
			'current' => isset( $data['enable_product_listing'] ) ? $data['enable_product_listing'] : '',
			'class' => 'chkbox_class ',
			'default_value' => 'true',

		));
		$form->add_element( 'text', 'product_listing_error', array(

			'lable' => __( 'Product Listing Error Message ', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['product_listing_error'] ) ? $data['product_listing_error'] : '',
			'class' => 'chkbox_class enable_product_listing ',
            'show'	=>false,
            'placeholder'=>__('Please select at least one product.', WDAP_TEXT_DOMAIN),		
            'default_value'=>__('Please select at least one product.',WDAP_TEXT_DOMAIN)
		));
		
		$form->add_element( 'text', 'can_be_delivered_redirect_url', array(

			'lable' => __( 'Delivery Availalble Redirect URL', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['can_be_delivered_redirect_url'] ) ? $data['can_be_delivered_redirect_url'] : '',
			'class' => 'chkbox_class can_be_delivered_redirect_url',
            'show'	=>false,
            'desc'=>__('Please enter URL where site needs to redirect when area specified by user is available for delivery i.e it comes under your delivery area. For eg. you can set URL of your shop page here. This redirection works on global shortcode form only not from default woocommerce pages. If redirect url is not specified the notifiction message is displayed by default.', WDAP_TEXT_DOMAIN),		
            'default_value'=>'',
            'placeholder' => __('Enter URL for redirecting when delivery is possible.', WDAP_TEXT_DOMAIN),	
		));
		
		$form->add_element( 'text', 'cannot_be_delivered_redirect_url', array(

			'lable' => __( 'Delivery Not Availalble Redirect URL', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['cannot_be_delivered_redirect_url'] ) ? $data['cannot_be_delivered_redirect_url'] : '',
			'class' => 'chkbox_class cannot_be_delivered_redirect_url',
            'show'	=>false,
            'desc'=>__('Please enter URL where site needs to redirect when delivery is not possible in the area specified by user. For eg. you can set URL of your any custom page here displaying a sorry message. This redirection works on global shortcode form only not from default woocommerce pages.  If redirect url is not specified the notifiction message is displayed by default.', WDAP_TEXT_DOMAIN),		
            'default_value'=>'',
            'placeholder' => __('Enter URL for redirecting when delivery is not possible.', WDAP_TEXT_DOMAIN),	
		));
		
		$form->add_element( 'text', 'product_listing_error', array(

			'lable' => __( 'Product Listing Error Message ', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['product_listing_error'] ) ? $data['product_listing_error'] : '',
			'class' => 'chkbox_class enable_product_listing ',
            'show'	=>false,
            'placeholder'=>__('Please select at least one product.', WDAP_TEXT_DOMAIN),		
            'default_value'=>__('Please select at least one product.',WDAP_TEXT_DOMAIN)
		));
		
		
		$form->add_element( 'group', 'product_delivery_area_shortcode', array(

			'value' => __( 'Global Delivery Area Map ( Using Shortcode ) Settings', WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-12">',
			'after' => '</div>',
		));

		$form->add_element( 'text', 'shortcode_map_title', array(
			'lable' => __( 'Delivey Area Map Title', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_title'] ) ? $data['shortcode_map_title'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Delivery Area Map Title',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('',WDAP_TEXT_DOMAIN),
		));
		$form->add_element( 'text', 'shortcode_map_description', array(
			'lable' => __( 'Delivery Area Map Description', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_description'] ) ? $data['shortcode_map_description'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Delivery Area Map Description',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>__('',WDAP_TEXT_DOMAIN),
		));

		$form->add_element( 'text', 'shortcode_map_width', array(

			'lable' => __( ' Enter Google Map Width', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_width'] ) ? $data['shortcode_map_width'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Google Map Width',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>750
		));
		$form->add_element( 'text', 'shortcode_map_height', array(

			'lable' => __( ' Enter Google Map height', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_height'] ) ? $data['shortcode_map_height'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Google Map Height',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>700,
		));

		$form->add_element( 'number', 'shortcode_map_zoom_level', array(
			'lable' => __( ' Enter Google Map Zoom Level', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_zoom_level'] ) ? $data['shortcode_map_zoom_level'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Google Map Zoom Level',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>5
		));
		$form->add_element( 'text', 'shortcode_map_center_lat', array(

			'lable' => __( ' Enter Map Center Latitude', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_center_lat'] ) ? $data['shortcode_map_center_lat'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Map Center Latitude',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>40.730610
		));
		$form->add_element( 'text', 'shortcode_map_center_lng', array(
			'lable' => __( ' Enter Map Center Longitude', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_center_lng'] ) ? $data['shortcode_map_center_lng'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Map Center Longitude',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
			'default_value'=>-73.935242
		));
		$form->add_element( 'textarea', 'shortcode_map_style', array(
			'lable' => __( ' Enter Snazzy Map Google Map Style', WDAP_TEXT_DOMAIN ),
			'value' => isset( $data['shortcode_map_style'] ) ? $data['shortcode_map_style'] :'',
			'class' => 'form-control',
			'placeholder' => __('Enter Snazzy Map Google Map Style',WDAP_TEXT_DOMAIN),
			'before' => '<div class="fc-6" >',
			'after' => '</div>',
		));
		$form->add_element('submit','WCRP_save_settings',array(
			'value' => __( 'Save Settings ',WDAP_TEXT_DOMAIN ),
			'before' => '<div class="fc-2">',
			'after' => '</div>',
		));

		$form->add_element('hidden','operation',array(
			'value' => 'save',
		));
		$form->add_element('hidden','hidden_zip_template',array(
			'value' => $data['default_templates']['zipcode'],
			'id' =>'hidden_zip_template',
		));
		$form->add_element('hidden','hidden_shortcode_template',array(
			'value' => $data['default_templates']['shortcode'],
			'id' =>'hidden_shortcode_template',
		));
		if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] ) {

			$form->add_element( 'hidden', 'entityID', array(
				'value' => intval( wp_unslash( $_GET['id'] ) ),
			));
		}
		$form->render();



