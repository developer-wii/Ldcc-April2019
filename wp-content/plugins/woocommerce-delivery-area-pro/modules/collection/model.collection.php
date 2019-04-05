<?php
/**
 * Class: WDAP_Model_Collection
 *
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package woo-delivery-area-pro
 */

if ( ! class_exists( 'WDAP_Model_Collection' ) ) {

	/**
	 * Setting model for Plugin Options.
	 *
	 * @package woo-delivery-area-pro
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WDAP_Model_Collection extends FlipperCode_Model_Base {


		/**
		 * Intialize Backup object.
		 */
		 public $validations = array(
		   'wdap_collection_title' => array( 'req' => 'Please Enter Collection Title', WDAP_TEXT_DOMAIN),
		  );
		  

		function __construct() {
            $this->table = WDAP_TBL_FORM;
   			$this->unique = 'id';
		}

			/**
			 * Admin menu for Settings Operation
			 *
			 * @return array Admin menu navigation(s).
			 */
		function navigation() {
			return array(
			'wdap_manage_settings' => __( 'How It Works', WDAP_TEXT_DOMAIN ),
			'wdap_add_collection' => __( 'Add Collection', WDAP_TEXT_DOMAIN ),
			'wdap_manage_collection' => __( 'Manage Collection', WDAP_TEXT_DOMAIN )
			);
		}

			/**
			 * Install table associated with Rule entity.
			 *
			 * @return string SQL query to install post_widget_rules table.
			 */
		public function install() {
			
			global $wpdb;
			$sql = 'CREATE TABLE ' . $wpdb->prefix . 'wdap_collection (
				 id int(10) unsigned AUTO_INCREMENT ,
				 title varchar(200) NOT NULL,
				 applyon varchar(100) NOT NULL,
				 chooseproducts LONGTEXT NOT NULL,
				 selectedcategories LONGTEXT NOT NULL,
				 assignploygons LONGTEXT NOT NULL,
				 wdap_map_region varchar(100) NOT NULL,
				 wdap_map_region_value LONGTEXT NOT NULL,
				PRIMARY KEY  (id)
				)';

			return $sql;
		}

   	  /**
	   * Get Rule(s)
	   *
	   * @param  array $where  Conditional statement.
	   * @return array         Array of Rule object(s).
	   */
		public function fetch( $where = array() ) {

		   $objects = $this->get( $this->table, $where );
		   if ( isset( $objects ) ) {
		    return $objects;
		   }
		}

			/**
			 * Add or Edit Operation.
			 */
		function save() {
			 $this->errors = $_POST['polygon_submission_error'];
			 if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}
			if ( isset( $_POST['entityID'] ) ) {
				$entityID = intval( wp_unslash( $_POST['entityID'] ) );
				}
			if ( $entityID > 0 ) {
			    $response['success'] = __( 'Updated successfully.',WDAP_TEXT_DOMAIN );
			   }else {
			    $response['success'] = __( 'Saved successfully.',WDAP_TEXT_DOMAIN );
			   }
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
