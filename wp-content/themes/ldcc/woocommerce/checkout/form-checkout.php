<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="small-12 medium-7 large-7 left your-order-basket">

    <h4>Your Order</h4>
    
    <?php
    //Get current basekt
    $cartCountent = WC()->cart->get_cart_contents_count();
    if($cartCountent > 0)
    {
    ?>
    
        <div class="woo-notices">
        <?php 
        do_action('checkout_process_notices');
        ?>
    </div>
    
    <form name="checkout-products" action="<?php echo get_permalink(6); ?>" method="post" class="checkout-review-basket">

    <table width="100%" cellspacing="0" cellpadding="0" class="products-add-basket">
    <tr class="tabheadings">
        <th class="text-left-align item-name" data-label="Item" class="head-item">Item</th>
        <th data-label="Price" class="head-price">Price</th>
        <th data-label="Quantity" class="head-qunatity">Quantity</th>
        <th data-label="Add To Basket" class="head-add-item">Remove</th>
    </tr>
            
    <?php
   $counterProduct = 0;

   foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) 
   {
    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
    
    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) 
    {
     ?>
    
        <tr <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?> class="tr_<?php echo $counterProduct; ?> relative">
            <td class="product-name text-left-align">
                <div class="product-titles">
                <?php
                if (!$_product->is_visible()) {
                    echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key) . '&nbsp;';
                } else {
                    //echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s </a>', esc_url($_product->get_permalink($cart_item)), $_product->get_title()), $cart_item, $cart_item_key);
                    echo apply_filters('woocommerce_cart_item_name', sprintf('%s', $_product->get_title()), $cart_item, $cart_item_key);
                }

                // Meta data
                echo WC()->cart->get_item_data($cart_item);

                // Backorder notification
                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                    echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>';
                }
                ?>
                </div>
            </td>
            <td class="cell-item-price">
                <div class="product-prices">
            <?php
            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
            ?>
                </div>
            </td>
            <td class="quantity-cell">
                <div class="product-quantities">
                <select id="qty" name="quantity[<?php echo $cart_item_key; ?>][qty]" class="qty">
                    <?php 
                    $maxTotalQty = 20;
                    for($q=1;$q <= $maxTotalQty;$q++)
                    {
                        if($cart_item['quantity'] == $q)
                        {
                            echo '<option selected="selected" value="'.$q.'">'.$q.'</option>';
                        }
                        else
                        {
                            echo '<option value="'.$q.'">'.$q.'</option>';
                        }
                    }
                    ?>
                </select>
                </div>
            </td>
            <td class="add-basket-cell" align="center" verticle-align="middle">
                <div class="product-actions">
                <span class="icon-remove-basket remove_from_basket product_type_simple" data-tr-id="<?php echo $counterProduct; ?>" data-cart-key="<?php echo $cart_item_key; ?>" data-product-id="<?php echo $product_id; ?>" href="#"><i class="fa fa-minus"></i></span>
             </td>
            </div>
        </tr>

     <?php
    }
    $counterProduct++;
   }
    ?>
        
        <?php 
        if ( sizeof( WC()->cart->get_cart() ) > 0 )
        {
        ?>
         <tr class="spacer">
             <td class="text-left-align">
                 <?php wp_nonce_field( 'woocommerce-cart' ); ?>
                 <button type="submit" name="co-review-update-qty" class="button update-cart">Update Quantities</button>
             </td>
         </tr>

         <tr class="totals">
             <td colspan="4">
                <p class="basket-view-totals"><?php _e( 'Total', 'woocommerce' ); ?>:
                    <span class="mini-total"><?php echo WC()->cart->get_cart_subtotal(); ?></span></p>
             </td>
         </tr>
         <?php
        }
        ?>
        
    </table>
        
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_first_name'])); ?>" name="billing_first_name" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_last_name'])); ?>" name="billing_last_name" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_email'])); ?>" name="billing_email" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_phone'])); ?>" name="billing_phone" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_mobile_number'])); ?>" name="billing_mobile_number" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_address_1'])); ?>" name="billing_address_1" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_address_2'])); ?>" name="billing_address_2" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_city'])); ?>" name="billing_city" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['billing_postcode'])); ?>" name="billing_postcode" />
        <input type="hidden" value="<?php echo esc_sql(trim($_POST['order_comments'])); ?>" name="order_comments" />
        
        </form>
    <?php
    }
    else
    {
        echo '<p>It seems your basket is currently empty.</p>';
    }
    ?>
    
</div>


    <?php
    // filter hook for include new pages inside the payment method
    $get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data" id="checkout-form-data">
  
    <div class="small-12 medium-4 large-4 medium-offset-1 right your-order-details">

    <?php
    $showTheCheckoutFormData = false;
    if( !isset($_POST['billing_first_name']) || !isset($_POST['billing_last_name']) || !isset($_POST['billing_email']) || !isset($_POST['billing_phone']) || !isset($_POST['billing_address_1']) || !isset($_POST['billing_city']) || !isset($_POST['billing_postcode']) )
    {
        $showTheCheckoutFormData = true;
    }
    else
    {
        $showTheCheckoutFormData = false;
        
        //Display all available data from POST and sessions ie (collection/delivery)
    }

    //do_action( 'woocommerce_before_checkout_form', $checkout );

    // If checkout registration is disabled and not logged in, the user cannot checkout
    if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
            echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
            return;
    }


    ?>     
            <?php 
            if ( sizeof( $checkout->checkout_fields ) > 0 )
            { 
            ?>

                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <div class="col2-set" id="customer_details" <?php if($showTheCheckoutFormData == false || is_user_logged_in()) { echo 'style="display:none !important;"'; } ?>>
                            <div class="col-1">
                                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>

                            <div class="col-2">
                                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            </div>
                    </div>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php 
            } 
            ?>

        <?php
        if($showTheCheckoutFormData == false || is_user_logged_in())
        {
             //Show full sidebar

            ?>
            <div class="row">
                <div class="small-12 medium-12 large-12 order-selected-details">
                    <?php 
                    //Get user details based on logged in or true state
                    switch(is_user_logged_in())
                    {
                        case true:
                            showUserDetailsSidebarByUser();
                        break;
                        case false:
                            showUserDetailsSidebarByPost($_POST);
                            //showUserDetailsSidebarByPost($_SESSION['co-data']);
                        break;
                        default:
                        break;
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        else
        {
            //Just show form above along with collection and droppoff dates
            ?>
            <div class="row">
                <div class="small-12 medium-12 large-12">
                   <?php
                   showSessionSelectedDetails();
                   ?>
                </div>
            </div>
            <?php
        }
        ?>
        
        
    </div>
        
    
    <div class="row">
    <div class="small-12 medium-12 large-12 left checkout-payment-section">

        <h2>Choose payment method</h2>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

        <div class="row">
            <div class="small-12 medium-12 large-12 left">
                <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>
            </div>
        </div>   

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

    </div>
    </div>

</form>
  
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php
/*
 * Get page modals
 */
require_once TEMPLATEPATH.'/includes/partials/edit-details-modal.php';
require_once TEMPLATEPATH.'/includes/partials/edit-collection.php';
require_once TEMPLATEPATH.'/includes/partials/edit-dropoff.php';
?>