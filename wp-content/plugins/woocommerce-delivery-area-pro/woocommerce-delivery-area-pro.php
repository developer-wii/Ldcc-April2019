<?php
/**
 * WDAP_Delivery_Area class file.
 * @package woo-delivery-area-pro
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.9
/*

Plugin Name: Woocommerce Delivery Area Pro
Plugin URI: http://www.flippercode.com/
Description:  A woocommerce extention for checking shipping availablity of woocommerce products using zipcodes, displaying delivery areas to users on frontend using google maps.
Author: flippercode
Author URI: http://www.flippercode.com/
Version: 1.0.9
Text Domain: woo-delivery-area-pro
Domain Path: /lang/

*/

if ( ! class_exists( 'FC_Plugin_Base' ) ) {
	$pluginClass = plugin_dir_path( __FILE__ ) . '/core/class.plugin.php';
	if ( file_exists( $pluginClass ) )
	include( $pluginClass ); 
}

if ( ! class_exists( 'WDAP_Delivery_Area' ) and class_exists('FC_Plugin_Base') ) {

	/**
	 * Main plugin class
	 *
	 * @author Flipper Code <hello@flippercode.com>
	 * @package woo-delivery-area-pro
	 */

	Class WDAP_Delivery_Area extends FC_Plugin_Base {
		
	    /**
		 * Class Vars
		 *
		*/
	 	private   $dboptions;
		private   $applyOn;
		static    $continent_list;
		static    $sub_continent_list;
		static    $ctrycodewithcont;
		private   $collections;
		private   $current_request_response;
		private   $is_country_restrict = false;
		private   $ajax_params = array();
		private   $is_via_shortcode = false;

		/**
		 * Class Constructor
		 *
		*/
		public function __construct() {

		    error_reporting( E_ERROR | E_PARSE );
			parent::__construct( $this->_plugin_definition() );
			$this->dboptions = maybe_unserialize(get_option('wp-delivery-area-pro'));
			$this->applyOn   = $this->dboptions['apply_on']['checkedvalue'];
			$this->wdap_setup_class_vars();
			$this->register_plugin_hooks();	
			
		}

		function _plugin_definition() {

		  $this->pluginPrefix = 'wdap';	
		  $pluginClasses = array( 'wdap-form.php','wdap-controller.php','wdap-model.php','wdap-auto-update.php'); 
		  $pluginModules = array('overview','collection','settings','backup'); 
		  $pluginCssFilesFrontEnd = array( 'wdap-frontend.css','wdap-template.css','select2.css','select2-bootstrap.css');
		  $pluginCssFilesBackendEnd = array('font-awesome.min.css','wdap-backend.css',
											'select2.css','select2-bootstrap.css','wdap-template.css'); 
		  $pluginJsFilesFrontEnd = array('wdap-frontend.js','select2.js'); 
		  $pluginJsFilesBackEnd = array('select2.js','wdap-backend.js');
		  $pluginData = array('childFileRefrence' => __FILE__,
							  'childClassRefrence' => __CLASS__,
							  'pluginPrefix' => 'wdap',
							  'pluginDirectory' => plugin_dir_path( __FILE__ ),
							  'pluginTextDomain' => 'wp-delivery-area-pro',
							  'pluginURL' =>  plugin_dir_url( __FILE__ ),
							  'dboptions' => 'wp-delivery-area-pro',
							  'controller' => 'WDAP_Controller',
							  'model' => 'WDAP_Model',
							  'pluginLabel' => 'WP Delivery Area Pro',
							  'pluginClasses' =>  $pluginClasses,
							  'pluginmodules' => $pluginModules,
							  'pluginmodulesprefix' => 'WDAP_Model_',
							  'pluginCssFilesFrontEnd' => $pluginCssFilesFrontEnd,
							 'pluginCssFilesBackEnd' => $pluginCssFilesBackendEnd,
							  'pluginJsFilesFrontEnd' => $pluginJsFilesFrontEnd,
							 'pluginJsFilesBackEnd' => $pluginJsFilesBackEnd
							  );
		  return $pluginData;
		  
		}
		
		function register_plugin_hooks() {

			add_action('wp_head', array($this, 'wdap_dynamic_css'));
		    add_action('init',array($this,'wpdap_get_collections'));
			add_action( 'wp_enqueue_scripts', array($this,'wdap_googlemap_api_key'));	
			add_action( 'wp_ajax_wdap_ajax_call',array( $this, 'wdap_ajax_call' ) );
			add_action( 'wp_ajax_nopriv_wdap_ajax_call', array( $this, 'wdap_ajax_call' ) );
			add_filter( 'woocommerce_product_tabs', array($this,'wdap_woo_extra_tabs'));
			add_action('admin_init',array($this,'wpdap_coordinateforward'));
			add_action('admin_enqueue_scripts', array($this,'wdap_admin_enqueue'));
			add_shortcode('delivery_area_form',array($this, 'wdap_custom_checking_form'));
			add_shortcode('delivery_areas',array($this, 'wdap_polygon_markup'));
			add_action( 'load-post.php', array($this,'wdap_collection_meta_boxes_setup' ));
			add_action( 'load-post-new.php', array($this,'wdap_collection_meta_boxes_setup'));
			add_action( 'save_post', array($this, 'wdap_save_delivery_metabox'),1,2);
			add_filter( 'body_class', array($this,'wdap_standard_design_class'));
			add_filter( 'admin_body_class', array($this,'wdap_standard_design_class'));

			if(!empty($this->applyOn)) {

				if(in_array('product_page', $this->applyOn))
				add_action( 'woocommerce_after_add_to_cart_form', array($this,'wdap_zipcode_field'));

				if(in_array('cart_page', $this->applyOn) )
				add_action('woocommerce_cart_totals_before_order_total', array($this,'markup_on_cartpage'));				

				if(in_array('checkout_page', $this->applyOn) or (!empty($this->dboptions['enable_order_restriction']) and $this->dboptions['enable_order_restriction'] ) )
				{
				    add_action('woocommerce_after_checkout_billing_form', array($this,'wdap_custom_checkout_field'));

					if(!empty($this->dboptions['enable_order_restriction'] ))
						add_action('woocommerce_checkout_process', array($this,'wdap_custom_checkout_field_process'));

				}

				if(in_array('shop_page', $this->applyOn))
				add_action( 'woocommerce_after_shop_loop_item', array($this,'wdap_after_shop_loop_item'), 10); 
				add_filter( 'woocommerce_cart_item_class', array($this,'wdap_woocommerce_cart_item_class'), 10,3);

				if(!empty($this->dboptions['enable_order_restriction'] ))
				add_filter('woocommerce_order_button_html',array($this, 'wdap_change_placeOrder_html') );

			}
			add_action( 'admin_notices', array($this,'wdap_feedback_notice') );

		}
		
		function wdap_standard_design_class( $classes ) {

			if(!empty($this->dboptions['default_templates']['shortcode']) and $this->dboptions['default_templates']['shortcode']=='standard'){
			     if(is_admin()){
			     	$classes= ' wdap_standard_design';
			     	return $classes;
			     }else{
			     	$classes[] = 'wdap_standard_design';
			     }
			 }
		    return $classes;
		}
		
		function wdap_save_delivery_metabox($post_id, $post){

			if ( empty( $post_id ) || empty( $post ) )
			return;
			if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) )
			return;
			if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) )
			return;
			if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id )
			return;
			if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
			$checked_collection_id = array();
			$selected_collections_id = $_POST['collection_listing']; 
			$all_collections_id = $_POST['apply_on_all_products'];
			$all_collections_id_array = explode(",", $all_collections_id);
			$checked_collection_id =  array_merge($all_collections_id_array,$selected_collections_id);			
			$need_to_save = array(
					'zip_form' => $_POST['enable_zipcode_form'],
					'avl_tab'  => $_POST['enable_product_avalibility'],
					'checked_collection' => $checked_collection_id
				);
			update_post_meta($post_id, 'wdap_current_post_setup', serialize($need_to_save));
			$collections = $this->collections;
			$selected_collections = array();
			$existing_selected_collection = array();

			foreach ($collections as $collection) {
				if($collection->applyon == "Selected Products"){
					$selected_collections[$collection->id] = $collection;
					if(in_array($post_id, maybe_unserialize($collection->chooseproducts)) ){
						$existing_selected_collection[] = $collection->id;
					} 
				}
			}
			$existing_collection_length = count($existing_selected_collection); 
			$selected_collection_length = count($selected_collections_id); 
			$collections_to_remove_product = array_diff($existing_selected_collection,$selected_collections_id);
			$collections_to_add_product = array_diff($selected_collections_id, $existing_selected_collection);
			if($selected_collection_length>$existing_collection_length){

				if($existing_collection_length>0){	
					if( empty($collections_to_remove_product)){
						$this->wdap_add_product_collection($post_id,$selected_collections_id,$selected_collections);
					}
					if(!empty($collections_to_remove_product) and !empty($collections_to_add_product)){
						$this->wdap_remove_product_collection($post_id,$collections_to_remove_product, $selected_collections);
						$this->wdap_add_product_collection($post_id,$collections_to_add_product,$selected_collections);
					}
				}
				//Add
				if($existing_collection_length==0){
					$this->wdap_add_product_collection($post_id,$selected_collections_id,$selected_collections);
				}
			}elseif ($selected_collection_length<$existing_collection_length){
				//Remove
				if($selected_collection_length > 0){
					$differ_collections_id = array_diff($existing_selected_collection, $selected_collections_id);
					$this->wdap_remove_product_collection($post_id,$differ_collections_id, $selected_collections);
				}
				if(!empty($collections_to_add_product)){
					$this->wdap_add_product_collection($post_id,$collections_to_add_product,$selected_collections);
				}
				if(empty($selected_collections_id)){
					$this->wdap_remove_product_collection($post_id,$existing_selected_collection, $selected_collections);
				}						
			}else{
				//Remove
				$this->wdap_remove_product_collection($post_id,$collections_to_remove_product, $selected_collections);
				//Add
				$this->wdap_add_product_collection($post_id,$collections_to_add_product,$selected_collections);
			}
		}
		
		function wdap_add_product_collection($id,$collections_id,$selected_collections){
			global $wpdb;
			foreach ($collections_id as $key => $collection_id) {
				if( array_key_exists($collection_id, $selected_collections) ){
					$actual_collection =  $selected_collections[$collection_id];
					$chooseproducts = maybe_unserialize($actual_collection->chooseproducts);
					if(!in_array($id, $chooseproducts)){
						$chooseproducts[] = $id;
						$wpdb->update( WDAP_TBL_FORM, array('chooseproducts'=>serialize($chooseproducts)), array('id'=>$collection_id));
					}	
				} 	
			}
		}
		
		function wdap_remove_product_collection($id,$collections_id,$selected_collections){
			global $wpdb;
			foreach ($collections_id as $key => $collection_id) {
				if( array_key_exists($collection_id, $selected_collections) ){
					$actual_collection =  $selected_collections[$collection_id];
					$chooseproducts = maybe_unserialize($actual_collection->chooseproducts);
					if(in_array($id, $chooseproducts)){
						$key = array_search($id, $chooseproducts);
						unset($chooseproducts[$key]);
						$wpdb->update( WDAP_TBL_FORM, array('chooseproducts'=>serialize($chooseproducts)), array('id'=>$collection_id));
					}	
				}
			}	
		}

		function wpdap_get_collections() {
			global $wpdb;
		    $this->collections = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wdap_collection');
		}
		
		function wdap_dynamic_css(){
			
			$style='';
			$class = !empty($this->dboptions['default_templates']['zipcode'])?$this->dboptions['default_templates']['zipcode'] : 'default';
			$style.='<style type="text/css">';
			if(is_cart() or is_checkout()){
				$style.='span.notavilable{color:'.$this->dboptions['error_msg_color'].';}
				span.avilable{color:'.$this->dboptions['success_msg_color'].';}
				body.woocommerce-checkout .classic #wdapzipsumit { background:'.$this->dboptions['avl_button_bgcolor'].';}';
			}
			if( (!empty($this->dboptions['avl_button_color']) or !empty($this->dboptions['avl_button_bgcolor']) )  ){
				$style.='.'.$class.' input#wdapzipsumit {color:'.$this->dboptions['avl_button_color'].';
				  background-color:'.$this->dboptions['avl_button_bgcolor'].';}';
			}
			if($class=='smart'){
				$style.='input#wdapziptextbox {border: 2px solid '.$this->dboptions['avl_button_bgcolor'].';}';
			}
			if($class=='standard'){
				$style.='.standard input.wdapziptextbox {background-color:'.$this->dboptions['avl_button_bgcolor'].';}';
				$style.='.standard .wdapziptextbox::-webkit-input-placeholder, .standard input.wdapziptextbox {color: #ffffff;}';
				$style.='.standard input.wdapziptextbox:focus {background: '.$this->dboptions['avl_button_bgcolor'].';
				color: '.$this->dboptions['avl_button_color'].';
				}';
				$style.='body.woocommerce-checkout .standard #wdapzipsumit {background-color:'.$this->dboptions['avl_button_bgcolor'].' !important;}';

			}
			$style.='.classic .wdapziptextbox {border-color:'.$this->dboptions['avl_button_bgcolor'].';}';
			$style.=' .default #wdapziptextbox{border:1px solid'.$this->dboptions['avl_button_bgcolor'].';}';
			$style.='</style>';
			echo $style;
			
			global $wpdb;
		    $this->collections = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wdap_collection');
			if(!empty($this->collections)){
				$store_locations = array();

				foreach($this->collections as $collection){
					if($collection->wdap_map_region == 'by_distance'){
						 $address = unserialize($collection->wdap_map_region_value);
						 $address['address'] = array(json_decode($address['address']));

						 $avl_products = $this->wdap_get_collection_product_id($collection);

						 $location = array(
						 	'range'=>$address['range'],
						 	'lat'=>$address['address'][0]->lat,
						 	'lng'=>$address['address'][0]->lng,
						 	'product_id'=> $avl_products,

						 	);
						 $store_locations[] = $location;
					}	
				}
				
			}
						?>
			<script>
			var store_locations = <?php echo json_encode($store_locations); ?>;
			</script>
			<?php
			
		}

		/* Sending products id into js for store test
		*/
		function wdap_get_collection_product_id($collection){
		
			if($collection->applyon=="All Products"){
				return 'all';
			}
			if($collection->applyon=="Selected Products"){
				$products  = maybe_unserialize($collection->chooseproducts);
				return $products;
			}
			if($collection->applyon=="selected_categories"){
				$categories  = maybe_unserialize($collection->selectedcategories);				
				$cat_products = array();
				$prod_categories =$categories; //category IDs
				$product_args = array(
				    'numberposts' => -1,
				    'post_status' => array('publish'),
				    'post_type' => array('product'), 
				   	'tax_query'=> array(
				        array(
				            'taxonomy' => 'product_cat',
				            'field' => 'id',
				            'terms' => $prod_categories,
				            'operator' => 'IN',
				    ))

				);

				$products = get_posts($product_args);
				if($products){
					foreach ($products as $product) {
						$cat_products[]=$product->ID;
					}
				}
				wp_reset_postdata();
				return $cat_products;
			}


		}


		// Adding meta boxes at order meta screen
		function wdap_collection_meta_boxes_setup(){
			add_action( 'add_meta_boxes', array($this,'wdap_add_collection_meta_boxes') );
		}

		function wdap_add_collection_meta_boxes(){
			global $woocommerce, $post;
			if(!empty($post->post_type) and ($post->post_type=="product") ){
				add_meta_box('woo-delivery-area-pro', esc_html__( 'Woo Delivery Area Pro', WDAP_TEXT_DOMAIN ), array($this,'wdap_choose_collection_meta_box'), 'product','side', 'high');	
			}
		}
		function wdap_choose_collection_meta_box($object, $box){
			$product_id    = $object->ID;
			$saved_setting = get_post_meta($product_id, 'wdap_current_post_setup');
			$saved_setting = maybe_unserialize($saved_setting[0]);
			if(!empty($saved_setting['checked_collection']))
				$saved_collection_id = $saved_setting['checked_collection'];
			if(!empty($saved_setting)){
				$form_enable = ($saved_setting['zip_form'] == "on") ?'checked="checked"' : '';
				$tab_enable  = ($saved_setting['avl_tab'] == "on") ?'checked="checked"' : '';
			}
	 	  ?>
	 	  <form action="" method="POST" enctype="multipart/form-data" id="collection_settings">
	 	  	<div class="fc-form-group ">
	 	  		<div class="fc-3">
	 	  			<label for="enable_zipcode_form"><?php echo __('Disable Shipping Enquiry Form', WDAP_TEXT_DOMAIN); ?></label>
		 	  		<span class="checkbox ">
		 	  			<input type="checkbox" id="enable_zipcode_form" name="enable_zipcode_form"  class="chkbox_class" <?php if(isset($form_enable)){ echo $form_enable;} ?> >
		 	  		</span>
		 	  	</div>
	 	 	</div>
	 	 	<div class="fc-form-group ">
	 	  		<div class="fc-3">
	 	  			<label for="enable_product_avalibility"><?php echo __('Disable Product Avalibility Tab',WDAP_TEXT_DOMAIN); ?> </label>
		 	  		<span class="checkbox ">
		 	  			<input type="checkbox" id="enable_product_avalibility" name="enable_product_avalibility" class="chkbox_class" <?php if(isset($tab_enable)){ echo $tab_enable;} ?> >
		 	  		</span>
		 	  	</div>
	 	 	</div>	
	 	 	<?php
	 	 	if(!empty($this->collections)){ ?>
				<div class="fc-form-group ">
	 	  		<div class="fc-3">
	 	  			<label for="enable_product_listing"><h4><?php echo __('Assign Product To Collection', WDAP_TEXT_DOMAIN); ?> </h4></label>
		 	  	</div>
	 	 	</div>
			<?php }
			$collections = $this->collections;
			$all_products = array();
			if(!empty($collections)){
				foreach ($collections as $key => $collection) { 
					$checked  = false;
					$disabled = '';
					if($collection->applyon == "Selected Products"){
					$selected_products = maybe_unserialize($collection->chooseproducts);
					if(in_array($product_id, $selected_products)){
						$checked = true;
					}
					$checkedindatabase = in_array($collection->id, $saved_collection_id);
					if($checked){
						$collectionchecked = ($checked) ?' checked="checked"' : '';
					}
					?>
				<div class="fc-form-group ">
	 	  			<div class="fc-3">
	 	  			<label for="enable_product_listing"><?php echo $collection->title; ?></label>
	 	  			<input type="checkbox" id="enable_product_avalibility" value="<?php echo $collection->id; ?>" name="collection_listing[]" class="chkbox_class" <?php if($checked){ echo $collectionchecked;} ?> >
	 	  			</div>
				</div>
				<?php 
					}
				} 
			}
			?>
			<input type="hidden" name="apply_on_all_products" value="<?php if(!empty($all_products)){$product_string = implode(",", $all_products); echo $product_string;} ?>">	
	 	  </form>
		  <?php
		}
		
		function wdap_woocommerce_missing() {
			?>
			<div class="notice notice-error">
				<p><a target="_blank" href="https://wordpress.org/plugins/woocommerce/"><?php _e('WooCommerce',WDAP_TEXT_DOMAIN); ?></a><?php _e( ' is required for <b>Woocommerce Delivery Area Pro</b> plugin to work. Please install and configure woocommerce first.', WDAP_TEXT_DOMAIN ); ?>
				</p>
			</div>
			<?php
		}
		
		function wdap_custom_checking_form($atts){
			$txtPlaceholder = isset($this->dboptions["check_buttonPlaceholder"]) ? $this->dboptions["check_buttonPlaceholder"] : __("Type Delivery Location (Landmark, Road or Area)",WDAP_TEXT_DOMAIN);
		    $locatlbl = isset($this->dboptions["wdap_form_locateme"]) ? $this->dboptions["wdap_form_locateme"] : __("Locate Me",WDAP_TEXT_DOMAIN) ; 
			$locate_me_image = WDAP_IMAGES."loc.png"; 
			$btnlbl = isset($this->dboptions["wdap_form_buttonlbl"]) ? $this->dboptions["wdap_form_buttonlbl"] : __("Check Availability",WDAP_TEXT_DOMAIN) ; 
			$design_class = !empty($this->dboptions['default_templates']['shortcode'])? $this->dboptions['default_templates']['shortcode'] :'default';
			$html = '';
			$html.='<style type="text/css">';
			$html.='.wdap_product_availity_form button.check_availability{background-color:'.$this->dboptions["form_button_bgcolor"].'; color:'.$this->dboptions["form_button_color"].'; }
			  .smart .clearfix.first-column{background:'.$this->dboptions["form_button_bgcolor"].';}
			  .smart .select2-container .select2-choice{border-left: 0.2em solid '.$this->dboptions["form_button_bgcolor"].';}
			  .wdap_standard_design .select2-drop-active, .standard .clearfix.first-column, .standard .select2-container .select2-choice, .standard .select2-dropdown-open.select2-drop-above [class^="select2-choice"] {
   					 background-color:'.$this->dboptions["form_button_bgcolor"].';
				}.standard div#s2id_wdap_product_list{border-left: 1px solid '.$this->dboptions["form_button_bgcolor"].';}
				.default .select2-container .select2-choice,.default.enable_product_listing #wdap_type_location,.classic.enable_product_listing #wdap_type_location,.classic .select2-container .select2-choice{border:1px solid '.$this->dboptions["form_button_bgcolor"].';border-right: 0;}

			';
			$html.='</style>';
			if($design_class=='standard'){
				$locate_me_image = WDAP_IMAGES."352557-321.png"; 				
			}
			
			if(!empty($this->dboptions["enable_locate_me_btn"])){
				$design_class.=' enable_locate_me_btn';
			}
			if(!empty($this->dboptions["enable_product_listing"])){
				$design_class.=' enable_product_listing';
			}
			$html.='<div class="wdap_product_availity_form '.$design_class.'">';
			if(!empty($this->dboptions['shortcode_form_title'])){
				$html.='<h1 class="wdap-hero-title">'.$this->dboptions['shortcode_form_title'].'</h1>';
			}
			$html.='<div class="clearfix first-column"> 	
			        <input  type="text" id="wdap_type_location" class="type-location " name="location" placeholder="'.$txtPlaceholder.'" >';
			             if(isset($this->dboptions["enable_locate_me_btn"]) and (is_ssl()) ) { 
				   			$html.='<img src="'.$locate_me_image.'" class="locate-me locate-me-text" >';
				      	  } 
			        $html.='
			        <input type="hidden" name="nonce" value="'.wp_create_nonce("wdap_create_nonce").'">
			        <input type="hidden" value="" class="zipcode_check_params"  name="zipcode_check_params" />
			        <input type="hidden" name="convertedzipcode" value="" id="convertedzipcode" class="convertedzipcode">';  
			        if( isset ($this->dboptions["enable_product_listing"]) ) { 
			        	 $args = array( 'post_type' => 'product','posts_per_page'=>-1 );
						  $loop = new WP_Query( $args );
			        	$html.='
			            <select class="form_product_list" id="wdap_product_list">
			            <option  value="">'.__('Select Product',WDAP_TEXT_DOMAIN).'</option>';
			            while ( $loop->have_posts() ) : 
						      $loop->the_post();
						     $html.='<option  value="'.$loop->post->ID.'">'.get_the_title('','',false).'</option>';
						  endwhile;
						wp_reset_query();
						$html.='</select>
						';	
					 } 
			        $html.='
			      	  <button name="check_availability" class="check_availability">'.$btnlbl.'</button>
			    </div>';
		        $html.='<div class="error-container second-column" style="display: none;" >
			    	<div class="error-message">'.__("Please enter your address.",WDAP_TEXT_DOMAIN).'></div>
			    </div>';

			if(!empty($this->dboptions['shortcode_form_description'])){
				$html.='<div class="wdap-shortcode-desc" ><span>'.$this->dboptions['shortcode_form_description'].'</span></div>';
			}
			$html.='</div>';
			return $html;
		}
		
		function wdap_change_placeOrder_html($html){
	  		return '<button id="place_order" class=" button alt new_submit button">'.__('Place Order', WDAP_TEXT_DOMAIN).'</button>';

		}
		
		function wdap_after_shop_loop_item(){
			
			global $product;
			if(is_shop()){
				$id = $product->get_id();
				$wdap_current_post_setup = get_post_meta($id,'wdap_current_post_setup');
			 	if(!empty($wdap_current_post_setup[0])){
			 		$wdap_current_post_setup = maybe_unserialize($wdap_current_post_setup[0]);
			 		if( !(!empty($wdap_current_post_setup['zip_form']) and  $wdap_current_post_setup['zip_form'] == "on")){
			 			$this->wdap_zipsearchmarkup($id,'' );
			 		}
			 	}else
			 	$this->wdap_zipsearchmarkup($id,'' );			
			}
		}
		
		function  wdap_woocommerce_cart_item_class($cart_item, $cart_item1, $cart_item_key ){
			 return $cart_item1['product_id'].' '.$cart_item;
		}
		
		function markup_on_cartpage(){

			 $cartdata = WC()->cart->get_cart();
			 $ids = array();
			 foreach ( $cartdata as $key => $item ) {
				 $ids[] = $item['product_id'];
				}
			$this->wdap_zipsearchmarkup($ids,'');
		}

		function wdap_googlemap_api_key($hook_suffix){ 

			global $post;
			if(!empty($this->dboptions['wdap_googleapikey'])){
				$googlemapkey = $this->dboptions['wdap_googleapikey'];
				if($googlemapkey){
				wp_enqueue_script( 'front-gmaps', 'https://maps.googleapis.com/maps/api/js?key='.$googlemapkey.'&libraries=drawing,geometry,places', '', '', false ); 
				}
			}
		}
		
		function wdap_admin_enqueue($hook_suffix) {

			global $post_type;
			if(!empty($this->dboptions['wdap_googleapikey'])){
		    	$googlemapkey = $this->dboptions['wdap_googleapikey'];
				if($googlemapkey){
				 	wp_enqueue_script( 'backend-gmaps', 'https://maps.googleapis.com/maps/api/js?key='.$googlemapkey.'&libraries=drawing,places,geometry', '', '', false ); 
				}
		    }
		}
		
		function save_polygon_cordinates(){
		
			if(!empty($_POST['store_address_json'])){
				$_POST['store_address_json'] = str_replace("'",'"',$_POST['store_address_json']);
				$_POST['store_address_json'] = stripcslashes($_POST['store_address_json']);
			}
			$entityID = '';
			if ( isset( $_REQUEST['_wpnonce'] ) )
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) );
			if ( isset( $nonce ) and ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) 
			die( 'Cheating...' );
			$form_errors = array();
			$mandatory_fields = array('wdap_collection_title'=>'Collection Title');
			foreach($mandatory_fields as $key=>$field){
					if(empty($_POST[$key])){
					 $form_errors[] =  __("$field is a required field.",WDAP_TEXT_DOMAIN);	
					}
			}
			if($_POST['wdap_applyonRadio'] == 'Selected Products' and empty($_POST['wdap_select_product']) ){
				$form_errors[] = __('Please Select at least one product',WDAP_TEXT_DOMAIN);
			}
			if($_POST['wdap_applyonRadio'] == 'selected_categories' and empty($_POST['selectedcategories']) ){
				$form_errors[] = __('Please Select at least one Category.',WDAP_TEXT_DOMAIN);
			}
			$polygonlen = strlen($_POST['polygons_json']);
			
			if($_POST['wdap_map_region'] == 'zipcode' && ($polygonlen==2 or $polygonlen==0 ) && empty($_POST['wdap_zip_codearea']) ){
				if(empty($_POST['hasGoogleAPI']))
				$form_errors[] = __('Please enter comma seperated zipcodes in textarea.', WDAP_TEXT_DOMAIN);
				else
				$form_errors[] = __('Please enter either zipcodes or draw a polygon on google map that represents your delivery area.', WDAP_TEXT_DOMAIN);
			}
			
			if($_POST['wdap_map_region']=='country' && ($polygonlen==2 or $polygonlen==0 ) && empty($_POST['wdap_map_region_setting'])  ){
				$form_errors[] = __('Please select any country or draw any polygon.',WDAP_TEXT_DOMAIN);
			}
			if($_POST['wdap_map_region']=='sub-continents' && ($polygonlen==2 or $polygonlen==0 ) && empty($_POST['wdap_map_region_setting']['sub_continent'])  ){
				$form_errors[] = __('Please select any sub-continent or draw any polygon.',WDAP_TEXT_DOMAIN);
			}
			if($_POST['wdap_map_region']=='continents' && ($polygonlen==2 or $polygonlen==0 ) && empty($_POST['wdap_map_region_setting']['continent'])  ){
				$form_errors[] = __('Please select any continent or draw any polygon.',WDAP_TEXT_DOMAIN);
			}
			if($_POST['wdap_map_region']=='by_distance'){
								
				if(empty($_POST['wdap_store_address']) or empty($_POST['store_address_json']))
				$form_errors[] = __('Please specify nearest location to your store.',WDAP_TEXT_DOMAIN);
				if(empty($_POST['wdap_store_address_range']))
				$form_errors[] =  __('Please specify distance range in kilometers where you allow / do delivery for orders.',WDAP_TEXT_DOMAIN);
				
			}
			
			if(count($form_errors) == 0) {
				if ( isset( $_POST['entityID'] ) ) {
				$entityID = intval( wp_unslash( $_POST['entityID'] ) );
				}
				if ( $entityID > 0 ) {
					$where[ 'id' ] = $entityID;
				} else {
					$where = '';
				}
				$data= array();
			    $data['title']   = sanitize_text_field( wp_unslash( $_POST['wdap_collection_title'] ) );
  			    $data['applyon'] = sanitize_text_field( wp_unslash( $_POST['wdap_applyonRadio'] ) );
  				if($_POST['wdap_applyonRadio']=='Selected Products'){
  					if($_POST['wdap_select_product']){
	  					$data['chooseproducts'] = serialize($_POST['wdap_select_product']);
	  				}
  				}
   				if($_POST['wdap_map_region']=='zipcode'){

   					$allzipcodes      = sanitize_text_field( wp_unslash( $_POST['wdap_zip_codearea'] ) );
   					$allzipcodesarray = explode(",",$allzipcodes);
	   				$filteredallzipcodes = array_filter($allzipcodesarray );
	   				$data['wdap_map_region_value'] = serialize($filteredallzipcodes);
   				}else if($_POST['wdap_map_region']=='by_distance'){
					
					$address = serialize( array('range' => wp_unslash( $_POST['wdap_store_address_range'] ),'address' => $_POST['store_address_json'] ) );
					$data['wdap_map_region_value'] = $address ;
					
				}
   				else{
   					$data['wdap_map_region_value'] = serialize($_POST['wdap_map_region_setting'] );
   				}
   				if( !empty($_POST['selectedcategories']  )){
					$data['selectedcategories'] = serialize($_POST['selectedcategories']);
				} 
				$data['assignploygons']  =  wp_unslash( $_POST['polygons_json'] ) ;
				$data['wdap_map_region'] = sanitize_text_field( wp_unslash( $_POST['wdap_map_region'] ) );
   				$result = FlipperCode_Database::insert_or_update( WDAP_TBL_FORM, $data, $where );
				if ( false === $result ) {
					 $response['error'] = __( 'Something went wrong. Please try again.',WDAP_TEXT_DOMAIN );
				} elseif ( $entityID > 0 ) {
					$response['success'] = __( 'Collection Updated successfully.',WDAP_TEXT_DOMAIN );
			    } else {
					$response['success'] = __( 'Collection Saved successfully.',WDAP_TEXT_DOMAIN );
			    }
			    unset($_POST);
			    if ( $entityID > 0 ) {
			    $_POST['entityID'] = $entityID;
			    }
			    $_POST['operation'] = 'save';
			    return $response;
		   }else{
			$_POST['polygon_submission_error'] = $form_errors;
		   }
		}

		/**
		 * Provide Saved Drawing Coordinates To JS.
		 */
		function wpdap_coordinateforward() {

			if( isset($_GET['page'] ) and ($_GET['page'] == 'wdap_add_collection')){
				if(isset($_POST['deliverypro_submission']) and !empty($_POST['deliverypro_submission']))	
					$this->save_polygon_cordinates();
					$modelFactory = new WDAP_Model();
					$ques_obj = $modelFactory->create_object( 'collection' );
					$wdap_js_lang['ajax_url'] = admin_url( 'admin-ajax.php' );
					$wdap_js_lang['nonce'] = wp_create_nonce( 'wdap-call-nonce' );
					if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['id'] ) ) {
						$ques_obj = $ques_obj->fetch( array( array( 'id', '=', intval( wp_unslash( $_GET['id'] ) ) ) ) );
						$data = (array) $ques_obj[0];
						$final_all_poligons=$this->ChangePolyCoordinatesInJSObject($data['assignploygons']);
						$wdap_js_lang['polygons'] = $final_all_poligons;
					}
					if($this->dboptions){
						$wdap_js_lang['mapsettings']['zoom'] = !empty($this->dboptions['wdap_map_zoom_level']) ? $this->dboptions['wdap_map_zoom_level'] :'';
						$wdap_js_lang['mapsettings']['centerlat'] = !empty($this->dboptions['wdap_map_center_lat']) ?$this->dboptions['wdap_map_center_lat'] :'';
						$wdap_js_lang['mapsettings']['centerlng'] = !empty($this->dboptions['wdap_map_center_lng']) ? $this->dboptions['wdap_map_center_lng'] :'';
						$wdap_js_lang['mapsettings']['style'] 	  = !empty($this->dboptions['wdap_map_style']) ? $this->dboptions['wdap_map_style'] :'';	
					}
					$icon_url = WDAP_IMAGES.'/pin_blue.png';
					$icon_url = apply_filters('wdap_map_icon',$icon_url);
					$wdap_js_lang['icon_url'] = $icon_url;
					wp_enqueue_script('jquery');
					wp_enqueue_script('polygonsdraw',  WDAP_JS.'polygonsdraw.js', array( 'jquery' ));
					wp_localize_script( 'polygonsdraw', 'wdap_backend_obj', $wdap_js_lang );
			}
		}

		/**
		 * Validate custom field on checkout page.
		 */
		function wdap_custom_checkout_field_process(){
			
			global $woocommerce;
			$order_restriction_error = $this->dboptions['wdap_order_restrict_error'];
			if(!empty($_POST['billing_postcode'])){
				$getallvalues = json_decode(stripslashes($_POST['Chkziptestresult']));
				$nofound = false;
	            foreach ($getallvalues as $key => $value) {
	            	if($value->value=="NO"){
	            		$nofound = true;
	            	}
	            }
	            if($nofound)
	           	wc_add_notice( $order_restriction_error , 'error');

	          }
		}

		function wdap_zipsearchmarkup($id,$zipcode){

			global $post;
			if(is_array($id))  
		 	$id = implode(',', $id);

			$pagtype = '';
			if(is_cart())
				$pagtype = 'cart';

			if(is_checkout())
				$pagtype = 'checkout';

			if(is_shop())
				$pagtype = 'shop';

			if(is_single() and $post->post_type=='product' )
				$pagtype = 'single';

			$zipcode_placeholder = apply_filters('wdap_provide_zipcode_placeholder',__('Enter Zipcode',WDAP_TEXT_DOMAIN) );	
			$empty_zip_error = $this->dboptions['wdap_empty_zip_code'];
			$class = !empty($this->dboptions['default_templates']['zipcode'])?$this->dboptions['default_templates']['zipcode'] : 'default';
			?>
			<div id="wdap_zip_check" class="wdap_zip_form_container <?php echo $class; ?>">
				<div class="wdap_notification_message" style="display: none;"><?php _e($empty_zip_error,WDAP_TEXT_DOMAIN); ?></div>
			 	<?php if(!is_checkout()){ 
	
			 		?>
			 	<input type="text" value="<?php echo $zipcode; ?>" name="zipcode_check" id="wdapziptextbox" class="wdapziptextbox"  placeholder="<?php echo $zipcode_placeholder; ?>">
			 	<?php } ?>
				<input type="hidden" data-pagetype="<?php echo $pagtype;  ?>" value="<?php echo $id;?>" id="checkproductid" class="checkproductid" name="wdapcheckproductid" />
				<input type="hidden"  value="yes" id="wdap_start" class="wdap_start" name="wdap_start"/>
				<input type="hidden" value="" class="Chkziptestresult" id="Chkziptestresult" name="Chkziptestresult" />
				<input type="hidden" value="" class="zipcode_check_params"  name="zipcode_check_params" />
				<?php
				$arrow='';
				if($class=='standard'){	
					$arrow ='wdap_arrow';
				}
					$style='';
					if(is_checkout()){
						if(!empty($this->dboptions['enable_order_restriction'])){
							$style.="display:none;";
						}
					}
				
				 $submit_button_label = !empty($this->dboptions['wdap_check_buttonlbl']) ? $this->dboptions['wdap_check_buttonlbl'] : apply_filters('wdap_submit_btn_lbl',__('Check Avalibility', WDAP_TEXT_DOMAIN) );  ?> 
				<input type="button" style="<?php echo $style; ?>" value ="<?php echo $submit_button_label; ?>" id="wdapzipsumit" class="wdapzipsumit single_add_to_cart_button button alt <?php echo $arrow; ?>"  />
				
				<?php  
			 	if( ($pagtype=='single') and ($this->dboptions['wdap_frontend_desc']) ){ ?> <p class="zipcode_test_desc"><?php echo $this->dboptions['wdap_frontend_desc']; ?></p><?php } ?>
		 </div>
		<?php 
		}
		/**
		 * Create Custom field on checkout page.
		 */
		function wdap_custom_checkout_field( $checkout ) {

			 global $woocommerce;
			 $cartdata = WC()->cart->get_cart();
			 $ids = array();
			 foreach ( $cartdata as $key => $item ) {
			  	 $ids[] = $item['product_id'];
			 }
			 echo '<div id="wdap_custom_checkout_field">';
				   $this->wdap_zipsearchmarkup($ids,'');
			 echo '</div>';
		}
		/**
		 * Create  Product avalibility tab
		 * @param  $tabs
		 * @return $tabs
		 */
		function wdap_woo_extra_tabs($tabs){
			global $post,$post_type;
			$enable_tab = $this->dboptions['disable_availability_tab'] ? $this->dboptions['disable_availability_tab'] : '';
			if(is_single() and $post_type=="product" and !($enable_tab)){
				$id = $post->ID;
				$wdap_current_post_setup = get_post_meta($id,'wdap_current_post_setup');
			 	if(!empty($wdap_current_post_setup[0])){
			 		$wdap_current_post_setup = maybe_unserialize($wdap_current_post_setup[0]);
			 		if( !(!empty($wdap_current_post_setup['avl_tab']) and  $wdap_current_post_setup['avl_tab'] == "on")){
			 			$tabs = $this->wdap_woo_extra_tabs_handlers($id,$tabs);
			 		}
			 	}else
			 	$tabs = $this->wdap_woo_extra_tabs_handlers($id,$tabs);
			}
			return $tabs;
		}
		function wdap_woo_extra_tabs_handlers($postid,$tabs){

			$get_all_zipcodes = $this->get_all_zipcodes($postid);
			if(count($get_all_zipcodes['allpolycoordinates'])==0 and count($get_all_zipcodes['allzipcodes'])==0){
				return $tabs;
			}
			$tabs['avalibility_map'] = array(
				'title'  => apply_filters('wdap_pa_tab_heading',__( 'Product Availability', WDAP_TEXT_DOMAIN ) ),
					'priority' =>50,
					'callback' => array($this,'wdap_woo_avalibility_map_content')
			);
			return $tabs;
		}
		/**
		 * [Zip and polygon on product avialibility tab]
		 * @return [type] [description]
		 */
		function wdap_woo_avalibility_map_content(){

			global $post; $allzipcode = array();
			$get_all_zipcodes = $this->get_all_zipcodes($post->ID);
			$get_all_zipcodesarray = $get_all_zipcodes['allzipcodes'];
			foreach ($get_all_zipcodesarray as $key => $collection) {
					foreach ($collection as $key => $zipcode) {
						$allzipcode[] = $zipcode;
					}
			}
			$allzipcode    = array_unique($allzipcode); 
			$Newallzipcode = array_values($allzipcode);
			asort($Newallzipcode);
			if($Newallzipcode){
			?>
			<div class="wdap_zip_table">
				<table>
					<?php
					$zipcode_listing_heading = apply_filters('wdap_zipcode_listing_heading',__('Product is avaialble in below zipcode areas.',WDAP_TEXT_DOMAIN) );
					?>
					<thead><th align="center"><?php echo $zipcode_listing_heading;  ?></th></thead>
					<tbody></tbody>
					<tr><td><?php foreach ($Newallzipcode as $key => $zipcode) {
						?><span class="wdap_zip"><?php echo  $zipcode;  ?></span> <?php
					} ?></td></tr>
				</table>
			</div>
			<?php } 
			$googlemapkey = $this->dboptions['wdap_googleapikey'];
			if($googlemapkey){
				echo do_shortcode('[delivery_areas from_tab="yes" product_id="'.$post->ID.'"]');
			}	
		}

		public static function get_all_zipcodes($productid,$exclude_collections=array()){

			global $wpdb;
			$collections = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wdap_collection');
			$collectionzipcodes = array();
			$collectioncoodinates = array();
			$storelocations = array();
			foreach ( $collections as $collection ){ //Loop for testing every collection	
				$c_id = $collection->id;
				if(!empty($exclude_collections) and in_array($c_id, $exclude_collections)){
					continue;
				}
				if($collection->applyon == 'All Products' or empty($productid)){
					if($collection->wdap_map_region == 'zipcode'){
						$collectionzipcodes[] = unserialize($collection->wdap_map_region_value);
					}
					
					if($collection->wdap_map_region == 'by_distance'){
						
						$zipcode_of_store_address = unserialize($collection->wdap_map_region_value);
						$zipaddress = json_decode($zipcode_of_store_address['address']);
						$location = array('lat' => $zipaddress->lat, 'lng' => $zipaddress->lng,
						'placezipcode' => $zipaddress->placezipcode, 'place_country_name' => $zipaddress->place_country_name);
						$storelocations[] = $location;
						
					}
					if(strlen($collection->assignploygons)>2)
					$collectioncoodinates[] = $collection->assignploygons;
				}
				else if($collection->applyon == 'Selected Products')
				{	
					if(in_array($productid,	(array)unserialize($collection->chooseproducts))){
						if($collection->wdap_map_region == 'zipcode'){
							$collectionzipcodes[] = unserialize($collection->wdap_map_region_value);
						}
						if(strlen($collection->assignploygons)>2)	
						$collectioncoodinates[] = $collection->assignploygons;
					}
				}else{
					if(is_array($productid)){
					   $productid = $productid['0'];
					}
			   		$terms = get_the_terms($productid, 'product_cat');
			   		$products_category = array();
			   		if(!empty($terms)){
			   			foreach ($terms as $key => $term) {
			   				$products_category[]= $term->term_id;
			   			}
			   		}
			   		$collection_category = unserialize($collection->selectedcategories);
			   		$matched_category = array_intersect($products_category,$collection_category);
			   		if(!empty($matched_category)){
			   			if(strlen($collection->assignploygons)>2)
					   		$collectioncoodinates[] = $collection->assignploygons;
					   	if($collection->wdap_map_region == 'zipcode'){
							$collectionzipcodes[] = unserialize($collection->wdap_map_region_value);
						}
			   		}
				}
			}
			$allzipcoordinates = array();
			foreach ($collectionzipcodes as $key => $value) {
				foreach ($value as $key => $onezip) {
					$allzipcoordinates[]=$onezip;
				}
			}
			$data = array(
				'allpolycoordinates'=>$collectioncoodinates, //Polygon
				'allzipcodes'=>$collectionzipcodes, //
				'allzipcoordinates'=>$allzipcoordinates, //Marker Lat Lng array
				'allstorelocations' => $storelocations
				);
			$wdap_js_lang['allzipcode'] = $allzipcoordinates;
			$wdap_js_lang['allstorelocations'] = $storelocations;
			$processdata = array();
			foreach ($collectioncoodinates as $key => $value) {
				$processdata[] = self::ChangePolyCoordinatesInJSObject($value);
			}
			$wdap_js_lang['allpolycoordinates'] = $processdata;
			$wdap_js_lang['allzipcodes'] = $allzipcoordinates;
			$icon_url = WDAP_IMAGES.'/pin_blue.png';
			$icon_url = apply_filters('wdap_map_icon',$icon_url);
			$wdap_js_lang['icon_url'] = $icon_url;
			$data['map_data'] = $wdap_js_lang;
			return $data;
		}

		function wdap_ajax_call() {

			$operation = $_POST['operation'];
			$response = $this->$operation($_POST);
			echo json_encode($response);
     		exit;
		}

		function wdap_search_in_coordinate($data){

			$allcoodinates = $data;
			$final_all_poligons = array();
			foreach ($data as $key => $value) {
				$final_all_poligons[] = json_decode($value);
			}
			$final_all_poligons = (array) $final_all_poligons;
			$requirepolyset = array();
			foreach ($final_all_poligons as $key => $onepolygonsettings) {
				foreach ($onepolygonsettings as $key => $onepolygonvalues) {
						$removequote = $onepolygonvalues[0]->coordinate;
					foreach ($removequote as $key2 => $obj) {
						$temp_obj = array();
						$temp_obj['lat'] = (double) $obj->lat;
						$temp_obj['lng'] = (double) $obj->lng;
						$removequote[$key2] = (object) $temp_obj;
					}
					$requirepolyset[] = $removequote;
				}
			}
			return $requirepolyset;
		}

		//Main Ajax Handler Funciton
		function Check_for_zipmatch($data){

			$tempData = str_replace("\\", "",$data['zip_response']);
			$decoded = json_decode($tempData);			
			if(!empty($decoded)){
				$json  = json_encode($decoded);
				$array = json_decode($json, true);
				$this->current_request_response = $array;
			}
			$this->ajax_params = $data;			
			$shortcode = $data['shortcode']; 
			$pagetype = $data['pagetype'];
			$response = array();
			$cartproductidcheck = array();
			if($pagetype == 'single' or $pagetype=='shop')
			{
				$response=$this->wdap_get_zipcodematch($data);
				if($response['status'] == 'found'){
					$response['pagetype'] = $pagetype;
					unset($this->current_request_response);
					$this->ajax_params = array();
					return $response;
				}
			}
			if($pagetype=='cart' or $pagetype=='checkout' )
			{
			 $productsid = $data['productid'];
			 foreach ($productsid as  $productid) {
				$data['productid'] = $productid;
				$responsecart = $this->wdap_get_zipcodematch($data);
				$dataToStore = array(
					'id'=>$productid,
				 	 'status'=>$responsecart['status'],
				 	 'coordinatematch'=>$responsecart['coordinatematch']
				 );
				$cartproductidcheck[] = $dataToStore;
			  }
			  	$response['cartdata'] = $cartproductidcheck;
			}
			if($shortcode){
				$this->is_via_shortcode = true;
				$response = $this->wdap_get_zipcodematch($data);
				if($response['status']=='found'){
					return $response;
				}else{
					$zipcode1 = $this->get_zip_code_from_response();
					if(!empty($data['zipcode']) and !($data['zipcode']==$zipcode1) and !empty($zipcode1)){
						$data['zipcode'] = $zipcode1;
						$response = $this->wdap_get_zipcodematch($data);
					}
				}				
			}
			$response['zipcodestring'] = $this->getlatlngwithoutrestrict();
			$response['pagetype'] = $pagetype;
			unset($this->current_request_response);
			$this->ajax_params = array();
			$this->is_via_shortcode = false;
			return $response;

		}
		
		function get_zip_code_from_response(){

			$result  = $this->current_request_response;
			foreach ($result as $key => $value) {
				$lastkey = count($value['address_components'])-1;
				foreach ($value['address_components'] as $key => $countryname) {
					$conditionFirst = (in_array('postal_code',$countryname['types'])) ? true : false;
					if($conditionFirst){
						return $countryname['long_name'];
					}
				}
			}
		}
		
		// get list of country from respone.
		function getcountrylist($zip){
			$result  = $this->current_request_response;
			$countrylist = array();
			foreach ($result as $key => $value) {
				$lastkey=count($value['address_components'])-1;
				foreach ($value['address_components'] as $key => $countryname) {
					$conditionthree = (in_array('country', $value['address_components'][$key]['types'])) ? true : false;
					if( $conditionthree){
						$countrylist[] = $value['address_components'][$key]['short_name'];
					}
				}
			}
			$countrylist = array_unique($countrylist);
			return $countrylist;
		}

		function matchinzipcountry($data){
			$zip = $data['zipcode'];
			$mapregion = $data['mapregion'];
			$countrylistfromdb = unserialize($data['mapregionvalue']);
			if($mapregion == 'country'){
				$countrylistfromzip=$this->getcountrylist($zip);
				if($countrylistfromzip) {
					$result = array_intersect($countrylistfromzip, $countrylistfromdb['country']);
					if(count($result)>0)
						return true;
					}
			}else{
			 	if(in_array($zip, $countrylistfromdb))
					return true;
				}
				$partial_code_array = array();
			 	foreach ($countrylistfromdb as $key => $dbzip) {
			 		if(stristr($dbzip,'*')){
			 			$dbzip = str_replace('*', '', $dbzip);
			 			$partial_code_array[] = $dbzip;
			 		}
			 	}
			 	if(!empty($partial_code_array)){
			 		$matches = $this->partial_zip_find($zip,$partial_code_array);
			 		return $matches;
			 	}
			return false;
		}

		function partial_zip_find($needle, array $haystack){

		    foreach ($haystack as $key => $value) {
		        if (false !== stripos($needle, $value)) {
		            return true;
		        }
		    }
		    return false;
		}

		function checkincontinentandsub($data){

			$mapregion = $data['mapregion'];
			$zip = $data['zipcode'];
			$mapregionvalue = unserialize($data['mapregionvalue']);
		    $countrylistfromzip = $this->getcountrylist($zip);
		    $contrelate = (array) json_decode(self::$ctrycodewithcont);
		    if($countrylistfromzip){
			foreach ($countrylistfromzip as $onezip) {
				if(array_key_exists ($onezip , $contrelate)){
					$actualcontient    = $contrelate[$onezip]->continent;
					$actualsubcontient = $contrelate[$onezip]->sub_continent;
					if($mapregion == 'continents'){
						if(in_array($actualcontient, $mapregionvalue['continent']))
							return true;
						}
					else{
						if(in_array($actualsubcontient, $mapregionvalue['sub_continent']))
							return true;
						}
					}
				}
			}
			return false;

		}

		function getlatlngwithoutrestrict(){
			$result  = $this->current_request_response;
			$latlngcollection = array();
			foreach ($result as $key => $value) {
				$latlngcollection[] = $value['geometry']['location'];
			}
			return $latlngcollection;

		}
		//Search function for zip code match in all collections
		function wdap_get_zipcodematch($data){

			global $wpdb;
			$retrictcountrydata = array();
			$retrictziplatlng   = array();
			$zipcode = isset($data['zipcode'])?$data['zipcode']:'';
			if($zipcode)
			{
				$collections  = $this->collections;
				$startsearch  = false; 
				$match        = false;
				$collectionid = array(); 
				$productmatch = false;
				$collectioncoodinates = array();
				foreach ( $collections as $collection ){ //Loop for testing every collection
					$checkinmapregiondata = array(
				   			'mapregion'=>$collection->wdap_map_region,
				   			'mapregionvalue'=>$collection->wdap_map_region_value,
				   			'zipcode'=>$zipcode,
				   			'id'=>$collection->id
				   			);
				   if($collection->applyon == 'All Products'){
				   		if($collection->wdap_map_region == 'country' or $collection->wdap_map_region == 'zipcode'){
				   			$getmatch = $this->matchinzipcountry($checkinmapregiondata);
				   			}
				   		if(($collection->wdap_map_region == 'continents' or $collection->wdap_map_region == 'sub-continents') and !$getmatch ){
				   			$getmatch = $this->checkincontinentandsub($checkinmapregiondata);
				   		}
					   	if($getmatch){
					   		$startsearch = true;
					   		$collectionid[] = $collection->id;
					   		 break;
					   	}
					   	else{
							 $collectioncoodinates[] = $collection->assignploygons;
						}
				    }
					else if($collection->applyon == 'Selected Products')
					{
				   		$productid = $data['productid'];
				   		if(is_array($productid)){
				   			$productid = $productid['0'];
				   		}
				   		$product_match = true;
				   		if(!empty($data['shortcode'])){

				   			if(!empty($productid)){
				   				$product_match  = (in_array($productid,unserialize($collection->chooseproducts))) ? true : false;
				   			}else{
				   				$product_match = true;
				   			}	
				   		}else{
				   		$product_match = in_array($productid,unserialize($collection->chooseproducts));
				   		}
				   		if($product_match)
						{	
							if($collection->wdap_map_region == 'country' or $collection->wdap_map_region == 'zipcode'){
					   			$getmatch = $this->matchinzipcountry($checkinmapregiondata);
					   		}
					   		if(($collection->wdap_map_region == 'continents' or $collection->wdap_map_region == 'sub-continents') and !$getmatch ){
					   			$getmatch = $this->checkincontinentandsub($checkinmapregiondata);
					   		}
						   	if($getmatch){
						   		$startsearch = true;
						   		$collectionid[] = $collection->id;
						   		break;
						   	}
						   	else{
						   		$collectioncoodinates[] = $collection->assignploygons;
						   	}
						}
					}else{
							$matched_category;
							$productid = $data['productid'];
					   		if(is_array($productid)){
					   			$productid = $productid['0'];
					   		}
					   		$terms = get_the_terms($productid, 'product_cat');
					   		$products_category = array();
					   		if(!empty($terms)){
					   			foreach ($terms as $key => $term) {
					   				$products_category[]= $term->term_id;
					   			}
					   		}
					   		$collection_category = unserialize($collection->selectedcategories);
					   		$matched_category = array_intersect($products_category,$collection_category);
					   		if(!empty($matched_category) or !empty($data['shortcode']) ){
					   			if($collection->wdap_map_region == 'country' or $collection->wdap_map_region == 'zipcode'){
						   			$getmatch = $this->matchinzipcountry($checkinmapregiondata);
						   		}
						   		if(($collection->wdap_map_region == 'continents' or $collection->wdap_map_region == 'sub-continents') and !$getmatch ){
						   			$getmatch = $this->checkincontinentandsub($checkinmapregiondata);
						   		}
						   		if($getmatch){
						   		$matched_category = array_values($matched_category);
					   			$matched_id = $matched_category[0];
					   			$matched_category_obj = get_term_by('id', $matched_id, 'product_cat');
					   			$matched_category_name = $matched_category_obj->name;
							   		$startsearch = true;
							   		$collectionid[] = $collection->id;
							   		break;
							   	}
							   	else{
							   		$collectioncoodinates[] = $collection->assignploygons;
							   	}
					   		}
						}
					}
				}
			if($startsearch){
				$response = array('status' =>'found','collectionid'=>$collectionid);
			}
			else{
				if(!empty($collectioncoodinates)){
					$allcoordinates = $this->wdap_search_in_coordinate($collectioncoodinates);
					$response = array(
					  'status'=>'notfound',
					  'coordinatematch'=>$allcoordinates,
					);
				}else{
					$response = array(
					  'status'=>'notfound',
					  'coordinatematch'=>array(),
					);
				}	
			}
			return  $response;
		}

		function wdap_zipcode_field(){

			 global $product,$post_type;
			 $zipcode = isset($_POST['wdapziptextbox'])?$_POST['wdapziptextbox']:'';

			 $id = $product->get_id();

			 if(is_single() and $post_type=="product" ){

			 	$wdap_current_post_setup = get_post_meta($id,'wdap_current_post_setup');

			 	if(!empty($wdap_current_post_setup[0])){

			 		$wdap_current_post_setup = maybe_unserialize($wdap_current_post_setup[0]);

					if( !(!empty($wdap_current_post_setup['zip_form']) and  $wdap_current_post_setup['zip_form'] == "on")){	

			 			$this->wdap_zipsearchmarkup($id,$zipcode);

			 		}
			 	}else
			 	 $this->wdap_zipsearchmarkup($id,$zipcode);	
			 }	 
		}

		function wdap_setup_class_vars(){
			$this->is_country_restrict = isset($this->dboptions['enable_retrict_country']) ? true :false;
			include(WDAP_INC_DIR.'ctrycodewithcont.php');
		}
		
		function define_admin_menu() {
			$pagehook = add_menu_page(
				__( 'Woocommerce Delivery Area Pro', WDAP_TEXT_DOMAIN ),
				__( 'Woocommerce Delivery Area Pro', WDAP_TEXT_DOMAIN ),
				'wdap_admin_overview',
				WDAP_SLUG,
				array( $this,'processor' ),
				WDAP_IMAGES.'fc-small-logo.png'
			);
			return $pagehook;
		}

		function plugin_activation_work() {

			$showzipcodesearch  = array(
				'wdap_map_width'=> 750,
				'wdap_map_height'=> 700,
				'wdap_map_zoom_level'=> 5,
				'wdap_map_center_lat'=> 40.730610,
				'wdap_map_center_lng'=>-73.935242,
				'wdap_check_buttonlbl'=> 'Check Availability',
				'wdap_frontend_desc'=>'Verify your pincode for correct delivery details',
				'apply_on'=>array('checkedvalue'=>array('0'=>'product_page','1'=>'shop_page')),
				'wdap_error_notavailable'=>'Product Not Available',
				'wdap_error_available'=>'Product Available',
				'wdap_error_invalid'=>'Invalid Zipcode',
				'wdap_error_th'=>' Availability Status',
				'avl_button_color'=>'#ffffff',
				'avl_button_bgcolor'=>'#a46497',
				'success_msg_color'=>'#209620',
				'error_msg_color'=>'#ff0000',
				'wdap_order_restrict_error'=>'We could not complete your order due to Zip Code Unavailability.',
				'wdap_empty_zip_code'=>'Please enter zip code.',
				'wdap_address_empty'  =>'Please enter your address.',
				'address_not_shipable' =>'Sorry, We do not provide shipping in this area.',
				'address_shipable'=>'Yes, We provide shipping in this area.',
				'form_success_msg_color'=>'#209620',
				'form_error_msg_color'=>'#ff0000',
				'wdap_form_buttonlbl'=>'Check Availability',
				'form_button_color'=>'#fff',
				'check_buttonPlaceholder'=>'Type Delivery Location (Landmark, Road or Area',
				'form_button_bgcolor'=>'#a46497',
				'wdap_form_locateme'=>'Locate Me',
				'product_listing_error'=>'Please select at least one product.',
				'enable_locate_me_btn'=>'true',
				'enable_bound'=>'yes',
				'enable_markers_on_map'=>'true',
				'enable_polygon_on_map'=>'true',
				'enable_map_bound'=>'true',
				'wdap_checkout_avality_method'=>'via_zipcode',
				'shortcode_map_width'=> 750,
				'shortcode_map_height'=> 700,
				'shortcode_map_zoom_level'=> 5,
				'shortcode_map_center_lat'=> 40.730610,
				'shortcode_map_center_lng'=>-73.935242,
				'default_templates'=> array('zipcode' => 'default','shortcode' => 'default')
				);
			$drs = maybe_unserialize(get_option('wp-delivery-area-pro'));
			if(!empty($drs)){
				foreach ($drs as $key=>$settings) {
					if(!empty($drs[$key])){
						$showzipcodesearch[$key] = $drs[$key];
					}
				}
			}
			if( (!$drs) or array_key_exists('version', $drs))
			update_option( 'wp-delivery-area-pro',  wp_unslash( $showzipcodesearch));
		}

		function wdap_polygon_markup($atts){
			$factoryObject = new WDAP_Controller();
			$viewObject = $factoryObject->create_object( 'shortcode' );
			$output = $viewObject->display( 'delivery_area',$atts );
			return $output;
		}

		function wdap_localisation_parameter(){

			global $post;
			
			$wdap_js_lang = array();
			$wdap_js_lang['ajax_url'] = admin_url( 'admin-ajax.php' );
			$wdap_js_lang['nonce'] = wp_create_nonce( 'wdap-call-nonce' );
			$wdap_js_lang['exclude_countries'] = apply_filters('wdap_exclude_countries',array());
			$wdap_js_lang['marker_country_restrict'] = apply_filters('wdap_enable_marker_country_restrict',true);
			$wdap_js_lang['is_api_key'] =  !empty($this->dboptions['wdap_googleapikey']) ? 'yes': '';

			
			if(!empty($this->dboptions['wdap_country_restriction_listing']) and ($this->dboptions['enable_places_to_retrict_country_only'] == 'true')){ 
				$wdap_js_lang['autosuggest_country_restrict'] =  $this->dboptions['wdap_country_restriction_listing'][0];
			}
			if(is_checkout()){

				$wdap_js_lang['wdap_checkout_avality_method'] = !empty($this->dboptions['wdap_checkout_avality_method']) ? $this->dboptions['wdap_checkout_avality_method'] : '';

				$is_shipping = (!empty($this->dboptions['wdap_checkout_avality_method'])  &&  $this->dboptions['wdap_checkout_avality_method']=='via_shipping') ? true :false;
				
				$is_billing = (!empty($this->dboptions['wdap_checkout_avality_method']) &&$this->dboptions['wdap_checkout_avality_method'] == "via_billing") ? true : false; 

				$is_shipping_address = ($is_shipping && ((!empty($this->dboptions['wdap_checkout_avality_shipping']) ) && $this->dboptions['wdap_checkout_avality_shipping']=='via_address') ) ? true :false;
				
				$is_billing_address = ($is_billing && ((!empty($this->dboptions['wdap_checkout_avality_billing']) ) && $this->dboptions['wdap_checkout_avality_billing']=='via_address') ) ? true :false;

				$is_shipping_zipcode = ($is_shipping && ((!empty($this->dboptions['wdap_checkout_avality_shipping']) ) && $this->dboptions['wdap_checkout_avality_shipping']=='via_zipcode') ) ? true :false;
				
				$is_billing_zipcode = ($is_billing && ((!empty($this->dboptions['wdap_checkout_avality_billing']) ) && $this->dboptions['wdap_checkout_avality_billing']=='via_zipcode') ) ? true :false;

				if($is_shipping_address or $is_billing_address ){
					$wdap_js_lang['wdap_checkout_avality_method'] = 'via_address';
				}
				if($is_shipping_zipcode or $is_billing_zipcode ){
					$wdap_js_lang['wdap_checkout_avality_method'] = 'via_zipcode';
				}
			}
			if($this->dboptions){
				$wdap_js_lang['mapsettings']['zoom']      = !empty($this->dboptions['wdap_map_zoom_level']) ? $this->dboptions['wdap_map_zoom_level'] : '';
				$wdap_js_lang['mapsettings']['centerlat'] = !empty($this->dboptions['wdap_map_center_lat']) ? $this->dboptions['wdap_map_center_lat']:'';
				$wdap_js_lang['mapsettings']['centerlng'] = !empty($this->dboptions['wdap_map_center_lng']) ? $this->dboptions['wdap_map_center_lng'] : '';
				$wdap_js_lang['mapsettings']['style']     = !empty($this->dboptions['wdap_map_style']) ? $this->dboptions['wdap_map_style'] : '';	
				$wdap_js_lang['mapsettings']['enable_restrict']     = !empty($this->dboptions['enable_retrict_country']) ? true : '';	
				if(!empty($this->dboptions['enable_markers_on_map'])){
					$wdap_js_lang['mapsettings']['enable_markers_on_map']     = !empty($this->dboptions['enable_markers_on_map']) ? $this->dboptions['enable_markers_on_map'] : 'no';
				}elseif(WDAP_VERSION=='1.0.3'){
					$wdap_js_lang['mapsettings']['enable_markers_on_map'] =true;
				}
				if(!empty($this->dboptions['enable_map_bound'])){
					$wdap_js_lang['mapsettings']['enable_bound']     = !empty($this->dboptions['enable_map_bound']) ? $this->dboptions['enable_map_bound'] : 'no';
				}elseif(WDAP_VERSION=='1.0.3'){
					$wdap_js_lang['mapsettings']['enable_map_bound'] =true;
				}
				if(!empty($this->dboptions['enable_polygon_on_map'])){
					$wdap_js_lang['mapsettings']['enable_polygon_on_map']     = !empty($this->dboptions['enable_polygon_on_map']) ? $this->dboptions['enable_polygon_on_map'] : 'no';
				}elseif(WDAP_VERSION=='1.0.3'){
					$wdap_js_lang['mapsettings']['enable_polygon_on_map'] =true;
				}
				$wdap_js_lang['mapsettings']['restrict_country']     = !empty($this->dboptions['wdap_country_restriction_listing'][0]) ? $this->dboptions['wdap_country_restriction_listing'][0] : '';
			}
			$errormessage=array(
				'empty'=>__(!empty($this->dboptions['wdap_empty_zip_code']) ? $this->dboptions['wdap_empty_zip_code'] :" Please enter zip code. ",WDAP_TEXT_DOMAIN),
				'na'=>__(!empty($this->dboptions['wdap_error_notavailable']) ? $this->dboptions['wdap_error_notavailable'] :" Product Not Available ",WDAP_TEXT_DOMAIN),
				'a'=>__(!empty($this->dboptions['wdap_error_available'] ) ? $this->dboptions['wdap_error_available'] : " Product Available", WDAP_TEXT_DOMAIN),
				'invld'=>__(!empty($this->dboptions['wdap_error_invalid'] ) ? $this->dboptions['wdap_error_invalid'] : "Invalid Zipcode.",WDAP_TEXT_DOMAIN),
				'p'=>__("Products are ",WDAP_TEXT_DOMAIN),
				'th'=>__(!empty($this->dboptions['wdap_error_th']) ? $this->dboptions['wdap_error_th'] :" Availability Status ",WDAP_TEXT_DOMAIN),
				'pr'=>__("Products ",WDAP_TEXT_DOMAIN),
				'error_msg_color'=>!empty($this->dboptions['error_msg_color'] ) ? $this->dboptions['error_msg_color'] : "#ff0000",
				'success_msg_color'=>!empty($this->dboptions['success_msg_color'] ) ? $this->dboptions['success_msg_color'] : "#77a464"
				);
			$wdap_js_lang['errormessages'] = $errormessage;
			if(!empty($this->dboptions['enable_order_restriction']) and is_checkout() ){
				$wdap_js_lang['order_restriction'] = !empty($this->dboptions['enable_order_restriction']) ? $this->dboptions['enable_order_restriction'] : '';
			}
			if(!empty($this->dboptions['wdap_checkout_avality']) and ($this->dboptions['wdap_checkout_avality']=='via_address')){
				$wdap_js_lang['wdap_checkout_avality'] = $this->dboptions['wdap_checkout_avality'];
			}
			$shortcode_settings = array(
					'wdap_address_empty'     => !empty($this->dboptions['wdap_address_empty']) ? $this->dboptions['wdap_address_empty'] : __('Please enter your address', WDAP_TEXT_DOMAIN) ,
					'address_not_shipable'   => !empty($this->dboptions['address_not_shipable']) ? $this->dboptions['address_not_shipable'] : __('Sorry, We do not provide shipping in this area.', WDAP_TEXT_DOMAIN),
					'address_shipable'       => !empty($this->dboptions['address_shipable']) ? $this->dboptions['address_shipable'] : __('Yes, We provide shipping in this area.', WDAP_TEXT_DOMAIN),
					'prlist_error'       => !empty($this->dboptions['product_listing_error']) ? $this->dboptions['product_listing_error'] : __('Please select at least one product.', WDAP_TEXT_DOMAIN),
					'form_success_msg_color' => !empty($this->dboptions['form_success_msg_color']) ? $this->dboptions['form_success_msg_color'] :'',
					'form_error_msg_color'   => !empty($this->dboptions['form_error_msg_color']) ? $this->dboptions['form_error_msg_color'] :'',
					);
				$wdap_js_lang['shortcode_settings'] = $shortcode_settings;
				$wdap_js_lang['shortcode_map']['enable'] = true; 	
			   	$wdap_js_lang['shortcode_map']['zoom']      = !empty($this->dboptions['shortcode_map_zoom_level']) ? $this->dboptions['shortcode_map_zoom_level'] : '';
				$wdap_js_lang['shortcode_map']['centerlat'] = !empty($this->dboptions['shortcode_map_center_lat']) ? $this->dboptions['shortcode_map_center_lat']:'';
				$wdap_js_lang['shortcode_map']['centerlng'] = !empty($this->dboptions['shortcode_map_center_lng']) ? $this->dboptions['shortcode_map_center_lng'] : '';
				$wdap_js_lang['shortcode_map']['style']     = !empty($this->dboptions['shortcode_map_style']) ? $this->dboptions['shortcode_map_style'] : '';

			if(isset($this->dboptions['enable_product_listing'])) {
				$wdap_js_lang['shortcode_settings']['check_product'] = $this->dboptions['enable_product_listing'];
			}
			
			$wdap_js_lang['can_be_delivered_redirect_url'] = $this->dboptions['can_be_delivered_redirect_url'];
			$wdap_js_lang['cannot_be_delivered_redirect_url'] = $this->dboptions['cannot_be_delivered_redirect_url'];
			$wdap_js_lang['loader_image'] = WDAP_IMAGES.'loader.gif';
			
			return $wdap_js_lang;
		}

		function frontend_script_localisation() {
			global $post;
			$wdap_js_lang = $this->wdap_localisation_parameter();
			wp_localize_script( 'wdap-frontend.js', 'wdap_settings_obj', $wdap_js_lang );	
		}
		
		function backend_script_localisation(){
			$deletemessage = array();
			$deletemessage['deleltemessage'] = __('Are you sure you want to delete this?', WDAP_TEXT_DOMAIN);
			wp_localize_script( 'wdap-backend.js', 'errormessage', $deletemessage );

		}

		function wdap_feedback_notice() {
			
			$screen = get_current_screen();
			if($screen->parent_base == 'wdap_view_overview') {
				?>
				<div class="notice notice-success is-dismissible" style="margin-left: 0px;margin-top: 15px;">
					<p><?php _e( 'If this plugin is useful for you, please provide us a <a href="https://codecanyon.net/item/woo-delivery-area-pro/reviews/19476751" target="_blank">Star Rating & Review</a>. Also please provide us your valuable suggestions & feedbacks so that we can make this plugin even better for you.', WDAP_TEXT_DOMAIN ); ?></p>
				</div>
			<?php
				
			}
			
		}
	
		function ChangePolyCoordinatesInJSObject($data){
			
			$final_all_polygons = str_replace('','',$data);
			$final_all_polygons = json_decode($final_all_polygons);
			$final_all_polygons = (array) $final_all_polygons;	
			$onepolyset = array();
			$requirepolyset = array();
			foreach ($final_all_polygons as $key => $onepolygonsettings) {
				foreach ($onepolygonsettings as $key => $onepolygonvalues) {
					$onepolyset['id'] = $onepolygonvalues->id;
					$removequote = $onepolygonvalues->coordinate;
					$onepolyset['coordinate'] = $removequote;
					foreach ($removequote as $key2 => $obj) {
						$temp_obj = array();
						$temp_obj['lat'] = (double) $obj->lat;
						$temp_obj['lng'] = (double) $obj->lng;
						$onepolyset['coordinate'][$key2] = (object) $temp_obj;
					}
					$onepolyset['format'] = $onepolygonvalues->popygon_all_properties;
				}
				$requirepolyset[] = $onepolyset;
			}
			return $requirepolyset;
		}
		 /**
		 * Define all constants.WDAP_TBL_FORM
		 */
		function _define_constants() {
			global $wpdb;
			if ( ! defined( 'WDAP_SLUG' ) ) {
				define( 'WDAP_SLUG', 'wdap_view_overview' );
			}
			if ( ! defined( 'WDAP_PREMIUM' ) ) {
				define( 'WDAP_PREMIUM', 'true' );
			}
			if ( ! defined( 'WDAP_VERSION' ) ) {
				define( 'WDAP_VERSION', '1.0.9' );
			}
			if ( ! defined( 'WDAP_TEXT_DOMAIN' ) ) {
				define( 'WDAP_TEXT_DOMAIN', 'woo-delivery-area-pro' );
			}
			if ( ! defined( 'WDAP_FOLDER' ) ) {
				define( 'WDAP_FOLDER', basename( dirname( __FILE__ ) ) );
			}
			if ( ! defined( 'WDAP_DIR' ) ) {
				define( 'WDAP_DIR', plugin_dir_path( __FILE__ ) );
			}
			if ( ! defined( 'WDAP_URL' ) ) {
				define( 'WDAP_URL', plugin_dir_url( WDAP_FOLDER ) . WDAP_FOLDER . '/' );
			}
			if ( ! defined( 'WDAP_CORE_URL' ) ) {
				define( 'WDAP_CORE_URL', WDAP_URL . 'core/' );
			}
			if ( ! defined( 'WDAP_PLUGIN_CLASSES' ) ) {
				define( 'WDAP_PLUGIN_CLASSES', WDAP_DIR . 'classes/' );
			}
			if ( ! defined( 'WDAP_CONTROLLER' ) ) {
				define( 'WDAP_CONTROLLER', WDAP_CORE_URL );
			}
			if ( ! defined( 'WDAP_CORE_CONTROLLER_CLASS' ) ) {
				define( 'WDAP_CORE_CONTROLLER_CLASS', WDAP_CORE_URL . 'class.controller.php' );
			}
			if ( ! defined( 'WDAP_MODEL' ) ) {
				define( 'WDAP_MODEL', WDAP_DIR . 'modules/' );
			}
			if ( ! defined( 'WDAP_TEMPLATES' ) ) {
				define( 'WDAP_TEMPLATES', WDAP_DIR . 'templates/' );
			}
			if ( ! defined( 'WDAP_TEMPLATES' ) ) {

				define( 'WDAP_TEMPLATES', WDAP_DIR . 'templates/' );
			}
			if ( ! defined( 'WDAP_TEMPLATES_URL' ) ) {
				define( 'WDAP_TEMPLATES_URL', WDAP_URL . 'templates/' );
			}
			if ( ! defined( 'WDAP_INC_DIR' ) ) {
				define( 'WDAP_INC_DIR', WDAP_DIR . 'includes/' );
			}
			if ( ! defined( 'WDAP_INC_URL' ) ) {
				define( 'WDAP_INC_URL', WDAP_URL . 'includes/' );
			}
			if ( ! defined( 'WDAP_CSS' ) ) {
				define( 'WDAP_CSS', WDAP_URL . '/assets/css/' );
			}
			if ( ! defined( 'WDAP_JS' ) ) {
				define( 'WDAP_JS', WDAP_URL . 'assets/js/' );
			}
			if ( ! defined( 'WDAP_IMAGES' ) ) {
				define( 'WDAP_IMAGES', WDAP_URL . 'assets/images/' );
			}
			if ( ! defined( 'WDAP_FONTS' ) ) {
				define( 'WDAP_FONTS', WDAP_URL . 'fonts/' );
			}
			if ( ! defined( 'WDAP_TBL_FORM' ) ) {
				define( 'WDAP_TBL_FORM', $wpdb->prefix . 'wdap_collection' );
			}
			if ( ! defined( 'WDAP_TBL_BACKUP' ) ) {
				define( 'WDAP_TBL_BACKUP', $wpdb->prefix . 'wdap_backups' );
			}
			$upload_dir = wp_upload_dir();
			if ( ! defined( 'WDAP_BACKUP' ) ) {

				if ( ! is_dir( $upload_dir['basedir'].'/collections-backup' ) ) {
					mkdir( $upload_dir['basedir'].'/collections-backup' );
				}
				define( 'WDAP_BACKUP',$upload_dir['basedir'].'/collections-backup/' );
				define( 'WDAP_BACKUP_URL',$upload_dir['baseurl'].'/collections-backup/' );

			}
			
		}
	}
	new WDAP_Delivery_Area();

}
