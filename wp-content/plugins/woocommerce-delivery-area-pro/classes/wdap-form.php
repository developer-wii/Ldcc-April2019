<?php
if ( ! class_exists( 'WDAP_FORM' ) ) {

	class WDAP_FORM extends FlipperCode_HTML_Markup{

		function __construct($options = array()) {
			
			$productInfo = array('productName' => __('Woocommerce Delivery Area Pro',WDAP_TEXT_DOMAIN),
                        'productSlug' => __('wdap_view_overview',WDAP_TEXT_DOMAIN),   
                        'productTagLine' => __('A woocommerce extention that allows users for checking shipping availablity of woocommerce products by zip code.',WDAP_TEXT_DOMAIN),
                        'productTextDomain' => WDAP_TEXT_DOMAIN,
                        'productIconImage' => WDAP_URL.'core/core-assets/images/wp-poet.png',
                        'productVersion' => WDAP_VERSION,
                        'docURL' => 'https://www.flippercode.com/woocommerce-delivery-area-pro/',
                        'videoURL' => 'https://www.youtube.com/watch?v=0x1gbCgn5b8&list=PLlCp-8jiD3p3skgYCjyW2ooRi62SY8fq6',
                        'demoURL' => 'https://www.flippercode.com/woocommerce-delivery-area-pro/',
                        'productImagePath' => WDAP_URL.'core/core-assets/product-images/',
                        'productSaleURL' => 'https://codecanyon.net/item/woo-delivery-area-pro/19476751',
						'multisiteLicence' => 'https://codecanyon.net/item/woo-delivery-area-pro/19476751?license=extended&open_purchase_for_item_id=19476751&purchasable=source'
			);
    
			$productInfo = array_merge($productInfo, $options);
			parent::__construct($productInfo);

		}

	}
	
}
