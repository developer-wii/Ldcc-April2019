<?php

if( class_exists('WC_Cart') )
{
    class customWooCart extends WC_Cart 
    {

        public function __construct()
        {
            //Construct our parent
            parent::__construct();
            //parent::init();
            do_action( 'woocommerce_init' );
            
            add_action( 'wp_loaded', array( $this, 'init' ) );

            $this->init();
            //$this->includeClasses();
            
        }
        
        public function init()
        {
            //$this->session  = new $session_class();
            //$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
        }
        
        public function removeSidebarItemFromBasket($cartItemKey)
        {
            if(parent::remove_cart_item($cartItemKey) == true)
            {
                return true;
            }
            return false;
        }
        
        public function includeClasses()
        {
            require_once ABSPATH . '/wp-content/plugins/woocommerce/includes/class-wc-session-handler.php' ;    
        }
        
        public function get_current_cart()
        {
            if ( ! did_action( 'wp_loaded' ) ) {
                    self::get_cart_from_session();
                    _doing_it_wrong( __FUNCTION__, __( 'Get cart should not be called before the wp_loaded action.', 'woocommerce' ), '2.3' );
            }
            return array_filter( (array) $this->cart_contents );
        }
    }
}
