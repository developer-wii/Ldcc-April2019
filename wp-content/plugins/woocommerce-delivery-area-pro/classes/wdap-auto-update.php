<?php
/**
 * Auto Update notification Class File.
 * @author flippercode
 * @package Updates
 * @version 1.0.0
 */
if( !class_exists('WDAP_Auto_Update') and class_exists ('Flippercode_Product_Auto_Update') ) {
	
	class WDAP_Auto_Update extends Flippercode_Product_Auto_Update{
		
		function __construct() { $this->wsq_current_version = WDAP_VERSION; parent::__construct(); }
	}
	return new WDAP_Auto_Update();
	
} 

