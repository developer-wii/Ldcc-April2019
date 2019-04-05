<?php
/**
 * Description of customWooFormHandler
 *
 * @author Andrew
 */
class customWooFormHandler extends WC_Form_Handler
{

    private static $_customWooFormHandle = null;


    public static function init()
    {
        add_action( 'wp_loaded', array( __CLASS__, 'custom_cart_update' ), 1 );
    }
    
    public static function customWooFormHandle()
    {
        if(is_null(self::$_customWooFormHandle))
        {
            self::$_customWooFormHandle = new self;
        }
        
        return self::$_customWooFormHandle;
    }
    
    public static function custom_cart_update()
    {

        if(isset($_POST['co-review-update-qty']))
        {
                WC()->cart->init();
                $cart_updated = false;
                $cart_totals  = isset( $_POST['quantity'] ) ? $_POST['quantity'] : '';

                //var_dump(WC()->cart->init());
 
                if ( sizeof( WC()->cart->get_cart() ) > 0 && is_array( $cart_totals ) ) {
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {

                                $_product = $values['data'];

                                // Skip product if no updated quantity was posted
                                if ( ! isset( $cart_totals[ $cart_item_key ] ) || ! isset( $cart_totals[ $cart_item_key ]['qty'] ) ) {
                                        continue;
                                }

                                // Sanitize
                                $quantity = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( "/[^0-9\.]/", '', $cart_totals[ $cart_item_key ]['qty'] ) ), $cart_item_key );

                                if ( '' === $quantity || $quantity == $values['quantity'] )
                                        continue;

                                // Update cart validation
                                $passed_validation 	= apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $values, $quantity );


                                if ( $passed_validation ) {
                                        WC()->cart->set_quantity( $cart_item_key, $quantity, false );
                                        $cart_updated = true;
                                }

                        }
                }

                // Trigger action - let 3rd parties update the cart if they need to and update the $cart_updated variable
                $cart_updated = apply_filters( 'woocommerce_update_cart_action_cart_updated', $cart_updated );

                if ( $cart_updated ) {
                        // Recalc our totals
                        WC()->cart->calculate_totals();
                }

                if ( $cart_updated ) {
                       add_action( 'checkout_process_notices', 'checkout_basket_updated_message', 9 );
                }
		
                
        }
    }
    
}

customWooFormHandler::init();