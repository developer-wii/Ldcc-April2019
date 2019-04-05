<?php
/**
 * Parse Shortcode and display maps.
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 */

if ( isset( $options['product_id'] ) ) {
	 $product_id = $options['product_id'];
	 $map_div = 'product_avalibility'.$product_id.'';
}else{
	$map_div= 'product_avalibility';
	
} 
$dboptions= maybe_unserialize(get_option('wp-delivery-area-pro'));
$delivery_area = new WDAP_Delivery_Area();
$map_data = $delivery_area->get_all_zipcodes($product_id);
$random_id = uniqid();
$map_data['map_data']['product_id'] = $product_id;
$map_data['map_data']['map_id'] = $random_id;
$autosuggest='id="pac-input'.$random_id.'"';

$height=$width=$map_output='';
if(!empty($dboptions['wdap_googleapikey'])){

	if(!empty($options['from_tab'])){
		$map_data['map_data']['from_tab'] = $options['from_tab'];
		$height = isset($dboptions['wdap_map_height']) ? $dboptions['wdap_map_height'] : 700;
		$width  = isset($dboptions['wdap_map_width']) ? $dboptions['wdap_map_width'] : 750;
	}else{
		$height = isset($dboptions['shortcode_map_height']) ? $dboptions['shortcode_map_height'] : 500;
		$width  = isset($dboptions['shortcode_map_width']) ? $dboptions['shortcode_map_width'] : 500;
	}
	$placeholder_text = __('Find Your Location',WDAP_TEXT_DOMAIN);
	$placeholder = apply_filters('wdap_placeholder_search',$placeholder_text);
	$map_output.='<input '.$autosuggest.' class="controls pac-input" type="text" placeholder="'.$placeholder.'">';
	$map_data = json_encode($map_data['map_data']);
	$map_output .= '<div class="wdap-shortcode-container" >';
	if(!empty($dboptions['shortcode_map_title']) && empty($options['from_tab']) ){
		$map_output.='<h1 class="wdap-hero-title">'.$dboptions['shortcode_map_title'].'</h1>';
	}
	$map_output .= '<div class="wdap_map '.$map_div.'" style="width:'.$width.'px;margin-bottom:20px; height:'.$height.'px;" id="'.$random_id.'" ></div>';
	if(!empty($dboptions['shortcode_map_description']) && empty($options['from_tab']) ){
		$map_output.='<div class="wdap-shortcode-desc" ><span>'.$dboptions['shortcode_map_description'].'</span></div>';
	}
	$map_output .= '<script>jQuery(document).ready(function($) {';
	if($product_id){
	$map_output .= '
	var map'.$product_id.' = $("#'.$random_id.'").deliveryMap('.$map_data.').data("wdap_delivery_map");';
	}else{
	$map_output .= 'var map = $("#'.$random_id.'").deliveryMap('.$map_data.').data("wdap_delivery_map");';
	}
	$map_output .= '});

	</script>';
	$map_output .= '</div>';
}
return $map_output;

