<?php
/**
 * Class: WDAP_Model_Settings
 *
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package woo-delivery-area-pro
 */

if ( ! class_exists( 'WDAP_Model_Settings' ) ) {

	/**
	 * Setting model for Plugin Options.
	 *
	 * @package woo-delivery-area-pro
	 * @author Flipper Code <hello@flippercode.com>
	 */
	 
	class WDAP_Model_Settings extends FlipperCode_Model_Base {


		
		function __construct() {
			
		}


			/**
			 * Admin menu for Settings Operation
			 *
			 * @return array Admin menu navigation(s).
			 */
		function navigation() {
			return array(
			'wdap_setting_settings' => __( 'Plugin Settings', WDAP_TEXT_DOMAIN ),
			);
		}



			/**
			 * Add or Edit Operation.
			 */
		function save() {


			if(!empty($_POST)){
				//echo '<pre>'; print_r($_POST); exit;
			}	
				
			$entityID = '';

			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ); }

			if ( isset( $nonce ) and ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {

				die( 'Cheating...' );

			}

			$this->verify( $_POST );
			

			if(empty( $this->errors )){
				if($_POST['enable_retrict_country'] && empty($_POST['wdap_country_restriction_listing']) ){
					$this->errors[]= __( "Please select at least one country.",WDAP_TEXT_DOMAIN);
				}
				 if(empty($_POST['wdap_map_height'])){
					$this->errors[]= __( "Please enter map height.",WDAP_TEXT_DOMAIN);
				 }
				if(empty($_POST['wdap_map_width'])){
					$this->errors[]= __( "Please enter map width.",WDAP_TEXT_DOMAIN);
				 }
			}

			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}
			if ( isset( $_POST['entityID'] ) ) {
				$entityID = intval( wp_unslash( $_POST['entityID'] ) );
			}


			if ( $entityID > 0 ) {
				$where[ $this->unique ] = $entityID;
			} else {
				$where = '';
			}
			if(empty($_POST['default_templates']['zipcode'])){
				$_POST['default_templates']['zipcode'] = $_POST['hidden_zip_template'];
			}
			if(empty($_POST['default_templates']['shortcode'])){
				$_POST['default_templates']['shortcode'] = $_POST['hidden_shortcode_template'];
			}
			if(empty($_POST['enable_map_bound'])){
				$_POST['enable_map_bound']='no';
			}
			if(empty($_POST['enable_polygon_on_map'])){
				$_POST['enable_polygon_on_map']='no';
			}
			if(empty($_POST['enable_markers_on_map'])){
				$_POST['enable_markers_on_map']='no';
			}
			update_option( 'wp-delivery-area-pro', wp_unslash( $_POST ) );
			$response['success'] = __( 'Setting(s) saved successfully.', WDAP_TEXT_DOMAIN );
			return $response;
		}

			/**
			 * Delete rule object by id.
			 */
		public function delete() {
			if ( isset( $_GET['id'] ) ) {
				$id = intval( wp_unslash( $_GET['id'] ) );
				$connection = FlipperCode_Database::connect();
				$this->query = $connection->prepare( "DELETE FROM $this->table WHERE $this->unique='%d'", $id );
				return FlipperCode_Database::non_query( $this->query, $connection );
			}
		}
	}
}
