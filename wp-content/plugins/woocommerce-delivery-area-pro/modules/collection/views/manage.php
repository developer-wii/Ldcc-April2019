<?php
/**
 * Class WP_List_Table_Helper File
 *
 * @author Flipper Code <hello@flippercode.com>
 * @package woo-delivery-area-pro
 */

$form  = new WDAP_FORM();
echo $form->show_header();

if ( class_exists( 'WP_List_Table_Helper' ) and ! class_exists( 'WDAP_Collection_Listing' ) ) {

 /**
  * Class wpp_Rule_Table to display rules for manage.
  *
  * @author Flipper Code <hello@flippercode.com>
  * @package woo-delivery-area-pro
  */
 class WDAP_Collection_Listing extends WP_List_Table_Helper {
  /**
   * Intialize class constructor.
   *
   * @param array $tableinfo Rules Table Informaiton.
   */
	public function __construct( $tableinfo ) {

	   parent::__construct( $tableinfo );
	}
	  
	function column_chooseproducts($record) {

		 	if($record->applyon=='All Products')
		  	{
		  		$html='-';
		  		return $html;
		  	}else if($record->applyon=='Selected Products'){
			  	$record = unserialize($record->chooseproducts);
				  if(is_array($record)) {
					  $html='';
					foreach($record as $key=>$value){
						$html.='<div class="thumbanil_listing">';
						if(get_the_post_thumbnail($value,array(26,26))){
						$html.= get_the_post_thumbnail($value,array(26,26));
						}
						else{
						$html.=wc_placeholder_img(array(24,24) );
						}
						$html.= '<a href="'.get_the_permalink($value).'">';
						$html.= get_the_title($value);
						$html.='</a>';
						$html.='</div>';
					}
				  }
			  return $html;
		  	}else{
		  		return '-';
		  	}
	  }
	  function column_wdap_map_region($record) {
	  		if($record->wdap_map_region=='by_distance'){
	  			return __("By Distance",WDAP_TEXT_DOMAIN);
	  		}
	  		
		  return ucfirst($record->wdap_map_region);
	  }

	  function column_applyon($record) {

	  		if($record->applyon=='selected_categories'){
	  			
	  			return __("Selected Categories",WDAP_TEXT_DOMAIN);
	  		}
	  		
		  return $record->applyon;
	  }

 }
 
	 global $wpdb;
	 $columns = array(
		'title' 		=>__( 'Title', WDAP_TEXT_DOMAIN ),
		'applyon'		 =>__( 'Apply On', WDAP_TEXT_DOMAIN ),
		'chooseproducts' =>__( 'Selected Products',WDAP_TEXT_DOMAIN ), 
		'wdap_map_region' =>__( 'Applied Delivery Area Rule',WDAP_TEXT_DOMAIN ),
		 );


	 $sortable  = array( 'title' ,'applyon');
	 $tableinfo = array(
	 'table' => WDAP_TBL_FORM,
	 'textdomain' => WDAP_TEXT_DOMAIN,
	 'singular_label' => 'Collection',
	 'plural_label' => 'Collections',
	 'admin_listing_page_name' => 'wdap_manage_collection',
	 'admin_add_page_name' => 'wdap_add_collection',
	 'primary_col' => 'id',
	 'columns' => $columns,
	 'sortable' => $sortable,
	 'per_page' => 200,
	 'actions' => array( 'edit','delete' ),
	 'col_showing_links' => 'title',
	 );

	 return new WDAP_Collection_Listing( $tableinfo );
	 
}
?>
