<?php
session_start();
global $postResponse, $loadOrderScreen, $validationResponse;
//do_action('woocommerce_init');
/**
 * Wordpress LDCC
 *
 * @author Andy Burns
 * 
 */

@ini_set( 'upload_max_size' , '1024M' );
@ini_set( 'post_max_size', '1024M');
@ini_set( 'max_execution_time', '300' );


require_once TEMPLATEPATH .'/classes/customWooFormHandler.php';

/* Include Pagination */
require_once 'includes/pagination.php';

/* Include Widgets */
require_once 'includes/widgets.php';

/* Include Shortcodes */
require_once 'includes/shortcodes.php';

/* Include Custom Classes */
require_once 'classes/postcodeLookUp.php';

if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(745, 375, true);
}

if (function_exists('add_image_size')) {
    add_image_size('logo', 339, 68, true);
    add_image_size('main-banner', 1600, 590, true);
    add_image_size('in-your-area', 1600, 590, true);
    add_image_size('checkout-pay-logo', 183, 60, true);
    add_image_size('services-images', 600, 153, array('center', 'top'));
    add_image_size('four-steps-banner', 799, 560, true);
    add_image_size('icon-four-steps', 41, 42, true);
    add_image_size('recent-testimonials', 490, 560 , true);
    add_image_size('page-banner', 1320, 401 , true);
    add_image_size('services-item', 340, 177 , array('center', 'center'));
}

//remove_filter('the_content', 'wpautop');
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/**
 * register_sidebar()
 *
 * @desc Registers the markup to display in and around a widget
 */
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => __('General', 'ldcc'),
        'id' => 'general',
        'description' => __('Appears on all pages', 'ldcc'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widge-ttitle">',
        'after_title' => '</h3>',
    ));
}

/* WIDGETS */
require_once(TEMPLATEPATH . '/widgets/custom-sidebar-basket.php');
require_once(TEMPLATEPATH . '/widgets/shop-opening-times.php');

// LIMIT WORDS
function limit_words($string, $word_limit) {
    $words = explode(" ", $string);
    return implode(" ", array_splice($words, 0, $word_limit));
}

// ADMIN LOGO
function change_login_logo() {
    echo '<style type="text/css">h1 a { width:240px !important;background-image:url(' . get_bloginfo('template_directory') . '/images/admin-logo.png) !important; background-size:auto !important; }</style>';
}
add_action('login_head', 'change_login_logo');

add_filter( 'query_vars', 'add_query_vars', 10, 1 );
function add_query_vars($vars) {   
    $vars[] = 's';
    $vars[] = 'collection-date';
    $vars[] = 'dropoff-date';
    return $vars;
}

/* WP ADMIN REMOVE TABS */
function remove_admin_menus()
{

  if (function_exists('remove_menu_page'))
  { 
    remove_menu_page('edit-comments.php'); // Comments
  }
}
add_action('admin_menu', 'remove_admin_menus'); 

/*
function custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;
     
    return array(
        'index.php', // Dashboard
        'separator1', // First separator
        'edit.php?post_type=shop_order', // Pages
        'edit.php?post_type=product', // Pages
        'edit.php?post_type=page', // Pages
        'edit.php?post_type=our-services', // Pages
        'edit.php?post_type=testimonials', // Pages

        'separator2', // Second separator
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'options-general.php', // Settings
        'edit.php', // Posts
        'edit-comments.php', // Comments
        'link-manager.php', // Links
        'upload.php', // Media
        'separator-last', // Last separator
    );
}
add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
add_filter('menu_order', 'custom_menu_order');
*/

/* 
 * Postcode Lookup 
 */
if(isset($_POST['pc-check']))
{
    $postcode = esc_sql(trim($_POST['pcode']));
    if(!empty($postcode))
    {
        $postcodeLookUp = new postcodeLookUp($postcode);
        if($postcodeLookUp->checkIfPostcodeDeliver($postcode) == true)
        {
            $loadOrderScreen = true;
            add_action('init', 'taxonomyRedirect');
        }
        else if($postcodeLookUp->checkIfPostcodeDeliver($postcode) == false)
        {
            $loadOrderScreen = true;
        }
        else
        {
            $loadOrderScreen = false;
        }
    }
    else
    {
        $postResponse = '<div class="error">Please enter a postcode.</div>';
    }
}

/*
 * Get a random single testimonials
 */
function getRandomTestimonial()
{
    global $wpdb;
    $queryRandom = "SELECT * FROM wp_posts WHERE post_type = 'testimonials' AND post_status = 'publish' ORDER BY RAND() DESC LIMIT 1 ";
    $getRandom = $wpdb->get_results($queryRandom);
    if(count($getRandom))
    {
        $testimonialAuthorRole = get_field('author_role', $getRandom[0]->ID);
        
        $randTestimonial = array(
            'rt_title' => $getRandom[0]->post_title,
            'rt_content' => $getRandom[0]->post_content,
            'rt_author_role' => $testimonialAuthorRole
        );
        return $randTestimonial;
    }
    return null;
}

/*
 * Output times of collection and dropofss
 */
function generateCollectionAndDropoffs($type)
{
    $out = '';
    
    //Require Instance of customWoocheckout Class
    require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout;

    switch($type)
    {
        case 'collection':
            $out .= '<ul class="timeofday" id="collection-tod">';
            $timesSel = $customWooCheckout->getSessionValue('collect-time');
        break;
        case 'dropoff':
            $out .= '<ul class="timeofday" id="dropoff-tod">';
            $timesSel = $customWooCheckout->getSessionValue('drop-time');
        break;
    }
    
    $out .= '<li class="tod-choose'; 
    if($timesSel == '8am-9am') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="8am-9am" data-type="'.$type.'" class="modal-time-selector">8am-9am</span></li>';
    
    $out .= '<li class="tod-choose';
    if($timesSel == '10am-11am') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="10am-11am" data-type="'.$type.'" class="modal-time-selector">10am-11am</span></li>';
    
    $out .= '<li class="tod-choose';
    if($timesSel == '11am-12pm') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="11am-12pm" data-type="'.$type.'" class="modal-time-selector">11am-12pm</span></li>';
    
    $out .= '<li class="tod-choose';
    if($timesSel == '12pm-1pm') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="12pm-1pm" data-type="'.$type.'" class="modal-time-selector">12pm-1pm</span></li>';
    
    $out .= '<li class="tod-choose';
    if($timesSel == '2pm-3pm') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="2pm-3pm" data-type="'.$type.'" class="modal-time-selector">2pm-3pm</span></li>';
    
    $out .= '<li class="tod-choose';
    if($timesSel == '3pm-4pm') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="3pm-4pm" data-type="'.$type.'" class="modal-time-selector">3pm-4pm</span></li>';
    
    $out .= '<li class="tod-choose';
    if($timesSel == '5pm-6pm') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="5pm-6pm" data-type="'.$type.'" class="modal-time-selector">5pm-6pm</span></li>';
    
    $out .= '<li class="last tod-choose';
    if($timesSel == '6pm-7pm') {$out .= ' active-time';}
    $out .= '"><span onClick="setChosenTime(this);" data-time="6pm-7pm" data-type="'.$type.'" class="modal-time-selector">6pm-7pm</span></li>';

    $out .= '</ul>';
    
    return $out;
}

/*
 * Save loggedin customer details from modal
 */
if(isset($_POST['save-user-details']))
{
    
    if(is_user_logged_in())
    {
        $user = wp_get_current_user();
        
        $billingFirstName = esc_sql(trim($_POST['billing_first_name']));
        $billingLastName = esc_sql(trim($_POST['billing_last_name']));
        $billingEmail = esc_sql(trim($_POST['billing_email']));
        $billingPhone = esc_sql(trim($_POST['billing_phone']));
        $billingAddressOne = esc_sql(trim($_POST['billing_address_1']));
        $billingAddressTwo = esc_sql(trim($_POST['billing_address_2']));
        $billingCity = esc_sql(trim($_POST['billing_city']));
        $billingPostcode = esc_sql(trim($_POST['billing_postcode']));

        update_user_meta($user->data->ID, 'shipping_first_name', $billingFirstName);
        update_user_meta($user->data->ID, 'shipping_last_name', $billingLastName);
        update_user_meta($user->data->ID, 'shipping_address_1', $billingAddressOne);
        update_user_meta($user->data->ID, 'shipping_address_2', $billingAddressTwo);
        update_user_meta($user->data->ID, 'shipping_city', $billingCity);
        update_user_meta($user->data->ID, 'shipping_postcode', $billingPostcode);

        update_user_meta($user->data->ID, 'billing_email', $billingEmail);
        update_user_meta($user->data->ID, 'billing_phone', $billingPhone);
    }
    else
    {
        wp_redirect(get_permalink(7));
    }
}

/* Woo Specific */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'ldcc_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'ldcc_wrapper_end', 10);

//CHANGE PRIORITY OF YOAST SEO
add_filter( 'wpseo_metabox_prio', function() { return 'low';});

//NUMBER OF PRODICTS TO DISPLAY ON SHOP PAGE
add_filter('loop_shop_per_page', create_function('$cols', 'return -1;'));

function ldcc_wrapper_start() {
  echo '<section role="content" id="content" class="content">';
}

function ldcc_wrapper_end() {
  echo '</section>';
}

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

add_action('add_custom_add_basket', 'custom_add_cart');
function custom_add_cart()
{
    global $woocommerce, $product;
    if( class_exists('WC_Product_External') ) $customWooExternal = new WC_Product_External($product);

    echo '<span class="icon-add-basket add_to_basket product_type_simple" data-product-id="'.$product->id.'" href="#"><i class="fa fa-plus"></i></span>';
}

add_action('add_custom_remove_basket', 'custom_remove_cart');
function custom_remove_cart()
{
    global $woocommerce, $product;
    if( class_exists('WC_Product_External') ) $customWooExternal = new WC_Product_External($product);

    echo '<span class="icon-remove-basket remove_from_basket product_type_simple" data-product-id="'.$product->id.'" href="#"><i class="fa fa-minus"></i></span>';
}

add_action('add_custom_get_price', 'custom_get_product_price');
function custom_get_product_price()
{
    global $woocommerce, $product;
    if( class_exists('WC_Product') ) $customWooProduct = new WC_Product($product);

    if ( ! $customWooProduct->get_price_html() )
     return;

    echo $customWooProduct->get_price_html();
}

//Ajax callbacks
add_action( 'init', 'enqueuer_scripter' );
function enqueuer_scripter() 
{
   wp_register_script( "ajax_requests", get_bloginfo('template_directory').'/js/customajax.js', array('jquery') );
   wp_localize_script( 'ajax_requests', 'AjaxRequests', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'ajax_requests' );
}

/* Load basket Action */
add_action('wp_ajax_load_basket', 'load_basket');
add_action('wp_ajax_nopriv_load_basket', 'load_basket');

/* Add to basket action */
add_action('wp_ajax_add_basket', 'add_basket');
add_action('wp_ajax_nopriv_add_basket', 'add_basket');

/* Choose from date pickers */
add_action('wp_ajax_choose_dates', 'choose_dates');
add_action('wp_ajax_nopriv_choose_dates', 'choose_dates');

/* Choose time collection/dropoff */
add_action('wp_ajax_choose_times', 'choose_times');
add_action('wp_ajax_nopriv_choose_times', 'choose_times');

/* Remove product from basket */
add_action('wp_ajax_remove_product_from_basket', 'remove_product_from_basket');
add_action('wp_ajax_nopriv_remove_product_from_basket', 'remove_product_from_basket');

/* Remove product from sidebar basket */
add_action('wp_ajax_remove_product_from_basket_sidebar', 'remove_product_from_basket_sidebar');
add_action('wp_ajax_nopriv_remove_product_from_basket_sidebar', 'remove_product_from_basket_sidebar');

function remove_product_from_basket()
{
    global $woocommerce;
    //do_action('woocommerce_init');
    
    //$cart = new WC_Cart;
    
    $theProdToRemove = esc_sql(trim($_POST['product']));
    $theProdItemKey = $_POST['itemKey'];

    $cart_item_key = sanitize_text_field( $theProdItemKey );
    if ( $cart_item = $woocommerce->cart->get_cart_item( $cart_item_key ) ) 
    {
         $removeFromCart = $woocommerce->cart->remove_cart_item( $cart_item_key );

            $product = wc_get_product( $cart_item['product_id'] );
            $undo    = $woocommerce->cart->get_undo_url( $cart_item_key );
            
        $woocommerce->cart->calculate_totals();
        $cartTotals = $woocommerce->cart->get_cart_total();
    }
    
    $cartCountent = $woocommerce->cart->get_cart();
    if(count($cartCountent) < 1)
    {
        $response['empty'] = 'true';
    }
    else
    {
        $response['empty'] = '';
    }
    
    if($removeFromCart)
    {
        $response['output'] = 'success';
        $response['totals'] = $cartTotals;
    }
    else
    {
        $response['output'] = 'failed';
    }
    
    $result = json_encode($response);
    // response output
    header( "Content-Type: application/json" );
    echo $result;
    die();
}

function choose_times()
{
    //Require Instance of customWoocheckout Class
    require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout;
    
    $theChosenTimes = esc_sql(trim($_POST['time']));
    $theChosenTypes = esc_sql(trim($_POST['type']));
    
    if(empty($theChosenTimes) || empty($theChosenTypes))
    {
        $response['output'] = 'failed';
    }
    else
    {
        
        $errorTimes = false;
        //Save time depending on type
        switch($theChosenTypes)
        {
            case 'collection':
               $customWooCheckout->setSessionValue('collect-time', $theChosenTimes); 
               $chosenTime = $customWooCheckout->getSessionValue('collect-time');
               $outPutLabel = 'Collection Time';
            break;
            case 'dropoff':
                $customWooCheckout->setSessionValue('drop-time', $theChosenTimes); 
                $chosenTime = $customWooCheckout->getSessionValue('drop-time');
                $outPutLabel = 'Dropoff Time';

               //Need to check the time of collection if the chosen date is the same
               $chosenCollectionDate = $customWooCheckout->getSessionValue('collection-picker');
               $chosenCollectionTime = $customWooCheckout->getSessionValue('collect-time');
               $chosenDropoffDate = $customWooCheckout->getSessionValue('dropoff-picker');
               $chosenDropoffTime = $customWooCheckout->getSessionValue('drop-time');

               if(!isset($chosenCollectionTime) || !isset($chosenCollectionDate)) 
               {
                    $response['output'] = 'failed';
                    $response['msg'] = 'Please choose a collection date/time before dropoff time.';
                    $errorTimes = true;
               }
               
               if(($chosenDropoffDate == $chosenCollectionDate) || ($chosenCollectionDate == $chosenDropoffDate))
               {
                    if(checkSessionTimesBasedOnDropOffTimes($chosenTime, $chosenCollectionTime) == true)
                    {
                        $response['output'] = 'failed';
                        $response['msg'] = 'Your dropoff time is before your collection time.';
                        $errorTimes = true;
                    }
               }
                
            break;
        }
        
            if(!empty($chosenTime) && $errorTimes == false)
            {
                $response['output'] = 'success';
                $responseTimes .= '<p class="para-time">'.$outPutLabel.': '.$chosenTime.'</p>';
                $response['times'] = $responseTimes;
                $response['fields'] = '<input type="hidden" name="'.$theChosenTypes.'-times" value="'.$chosenTime.'" />';
            }
            else
            {
                $response['output'] = 'failed';
            }
    }
    
    $result = json_encode($response);
    // response output
    header( "Content-Type: application/json" );
    echo $result;
    die();
}


function choose_dates()
{
    //Require Instance of customWoocheckout Class
    require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout;
    
    $chosenDate = esc_sql(trim($_POST['dates']));
    $table = esc_sql(trim($_POST['tab']));
    
    $errorDates = false;
    
    switch($table)
    {
        case 'collection-picker':
            $customWooCheckout->setSessionValue('collection-picker', $chosenDate);
            $chosenDate = $customWooCheckout->getSessionValue('collection-picker');
            $outPutLabel = 'Collection Date';
            
                if($chosenDate < date("Y-m-d"))
                {
                    $response['output'] = 'failed';
                    $response['msg'] = 'Please choose a date which corresponds with today or in the future!';
                    $errorDates = true;
                }
            
        break;
        case 'dropoff-picker':
            
            $validCollectionDate = $customWooCheckout->getSessionValue('collection-picker');
            $validCollectionTime = $customWooCheckout->getSessionValue('collect-time');
            if(empty($validCollectionDate) || empty($validCollectionTime))
            {
                    $response['output'] = 'failed';
                    $response['msg'] = 'Please choose a collection date and time.';
                    $errorDates = true;
            }
            
            $customWooCheckout->setSessionValue('dropoff-picker', $chosenDate);
            $chosenDate = $customWooCheckout->getSessionValue('dropoff-picker');
            $outPutLabel = 'Dropoff Date';
            
                if( ! ($chosenDate >= date("Y-m-d")) || !($chosenDate >= $validCollectionDate ))
                {
                    $response['output'] = 'failed';
                    $response['msg'] = 'Please choose a dropoff date and time that is after your collection date and time.';
                    $errorDates = true;
                }
                
               //Need to check the time of collection if the chosen date is the same
               $chosenCollectionDate = $customWooCheckout->getSessionValue('collection-picker');
               $chosenCollectionTime = $customWooCheckout->getSessionValue('collect-time');
               $chosenDropoffDate = $customWooCheckout->getSessionValue('dropoff-picker');
               $chosenDropoffTime = $customWooCheckout->getSessionValue('drop-time');

               if(($chosenDate == $chosenCollectionDate) || ($chosenCollectionDate == $chosenDate))
               {
                    if(checkSessionTimesBasedOnDropOffTimes($chosenDropoffTime, $chosenCollectionTime) == true)
                    {
                        $response['output'] = 'failed';
                        $response['msg'] = 'Your dropoff date/time is before your collection date/time.';
                        $errorDates = true;
                    }
               }
                
                
        break;
    }

    if(!empty($chosenDate) && $errorDates == false)
    {
        $response['output'] = 'success';
        
        $responseDate = '';
        $newDates = new DateTime($chosenDate);
        $responseDate .= '<p class="para-date">'.$outPutLabel.': '.$newDates->format('jS F Y').'</p>';
        
        $response['dates'] = $responseDate;
        $response['fields'] = '<input type="hidden" name="'.$table.'-section" value="'.$newDates->format('jS F Y').'" />';
        
        //This is needed for checkout only
        $response['codate'] = $newDates->format('jS F Y');
    }
    else
    {
        $response['output'] = 'failed';
    }

    $result = json_encode($response);
    // response output
    header( "Content-Type: application/json" );
    echo $result;
    die();
}

function remove_product_from_basket_sidebar()
{
    global $woocommeerce;
    $theProductToRemove = esc_sql(trim($_POST['product']));
    $cartItemKey = esc_sql(trim($_POST['itemKey']));
    
    $cart = WC()->instance()->cart;
    $cart_id = $cart->generate_cart_id($theProductToRemove);
    $cart_item_id = $cart->find_product_in_cart($cart_id);
    
    if($theProductToRemove == null)
    {
        $response['output'] = 'failed';
        $response['msg'] = 'Please choose a product to be removed.';
    }
    else if($cart->set_quantity($cart_item_id, 0) == true)
    {
             ob_start();
             $miniBasket = load_basket();
             $miniBasket = ob_get_clean();

             $response['output'] = 'success';
             $response['basket'] = $miniBasket;
    }
    else
    {
        $response['output'] = 'failed';
        $response['msg'] = 'Product cannot be removed at this time.';
    }
    
    $result = json_encode($response);
    // response output
    header( "Content-Type: application/json" );
    echo $result;
    die();
    
}


function load_basket()
{
    global $woocommerce;
    ob_start();
    /*
     * Cart doesnt seem to load more then one item, this is due to the cart hash
     * May need to re calculate the total or something!!!!!!!
     */
    $cartCountent = WC()->cart->get_cart_contents_count();
    if($cartCountent > 0)
    {
    wc_print_notices();
    do_action('woocommerce_before_cart');
    do_action('woocommerce_before_cart_table'); ?>

    <form action="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" method="post">

    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="minibasket">
        <tr class="basket-header">
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>&nbsp;</th>
        </tr>
    <?php do_action('woocommerce_before_cart_contents'); ?>
    <?php
    //echo '<pre>';
    //var_dump(WC()->cart->get_cart());
    //echo '</pre>';
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) 
    {
    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) 
    {
     ?>
        <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

    <!--<td class="product-remove">
        <?php
        //echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url(WC()->cart->get_remove_url($cart_item_key)), __('Remove this item', 'woocommerce')), $cart_item_key);
        ?>
    </td>-->

    <td class="product-name">
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
    </td>

    <!--
    <td class="product-price">
    <?php
    //echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
    ?>
    </td>-->

                <td class="product-quantity">
                    <?php
                    /*
                    if ($_product->is_sold_individually()) {
                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                    } else {
                        $product_quantity = woocommerce_quantity_input(array(
                            'input_name' => "cart[{$cart_item_key}][qty]",
                            'input_value' => $cart_item['quantity'],
                            'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                            'min_value' => '0'
                                ), $_product, false);
                    }

                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
                     * 
                     */
                    echo $cart_item['quantity'];
                    ?>
                </td>

                <td class="product-subtotal">
            <?php
            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
            ?>
                </td>
                <td>
                    <span href="#" data-product-id="<?php echo $cart_item['product_id']; ?>" data-item-key="<?php echo $cart_item_key; ?>" class="remove-basket remove_sidebar_basket product_type_simple">
                        <i class="fa fa-times"></i>
                    </span>
                </td>
         </tr>
                <?php
            }
        }
        ?>
         
         <?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>
         <tr>
             <td colspan="4">&nbsp;</td>
         </tr>

         <tr class="totals">
             <td colspan="4">
                <p><?php _e( 'Total', 'woocommerce' ); ?>:
                    <span class="mini-total"><?php echo WC()->cart->get_cart_subtotal(); ?></span></p>
             </td>
         </tr>
         
          <tr class="proceed">
             <td colspan="4">
                <a href="#" class="smooth-scroll button proceed-order chevron sidebar-cart-scroll" data-target="collection">proceed to order</a>
             </td>
         </tr>
         <?php endif; ?>
         
        </table>
        </form>
     <?php
    }
    else
    {
       ?>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="empty-basket">
            <tr>
                <td>Your Basket Is Empty</td>
            </tr>
        </table>
        <?php
    }
    
    $miniBaskets = ob_get_clean();
    
    $response['output'] = 'success';
    $response['basket'] = $miniBaskets;
    
    $result = json_encode($response);
    // response output
    header( "Content-Type: application/json" );
    echo $result;
    die();
}

function add_basket()
{
    global $woocommerce;
    do_action('woocommerce_init');
    
    $productsIds = esc_sql(trim($_POST['product']));
    $productQuantities = esc_sql(trim($_POST['quantity']));
    
    if($productsIds == null && $productQuantities == null)
    {
        $response['output'] = 'failed';
        $response['msg'] = 'Please choose a product to be added.';
    }
    else
    {
        
        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $productsIds, $productQuantities );
	$product_status    = get_post_status( $productsIds );
        
        
		if ( $passed_validation )
                {
                   $variations = '';
                   if( $woocommerce->cart->add_to_cart( $productsIds, $productQuantities, 0, $variations ) && 'publish' === $product_status )
                   {

			do_action( 'woocommerce_ajax_added_to_cart', $productsIds );

			//if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
			//	wc_add_to_cart_message( $productsIds );
			//}
                        
                        //$miniBasket = get_refreshed_fragments();
                        ob_start();
                        $miniBasket = load_basket();
                        $miniBasket = ob_get_clean();
                        
                        $response['output'] = 'success';
                        $response['basket'] = $miniBasket;
                   }
                   else
                   {
                       $response['output'] = 'failed';
                       $response['msg'] = 'Product could not be added.';
                   }
                        
		} else {

                        $response['output'] = 'failed';
			$response['msg'] = 'Failed validation';
		}
        
    }
    
    $result = json_encode($response);
    // response output
    header( "Content-Type: application/json" );
    echo $result;
    die();
}


/**
* Get a refreshed cart fragment
*/
function get_refreshed_fragments() {

    // Get mini cart
    ob_start();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

    // Fragments and mini cart are returned
    $data = array(
            'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                            'div#sidebar-cart' => '' . $mini_cart . ''
                    )
            ),
            'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
    );

    return $data;
}


// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
}



/* HANDLE THE CHECKOUT FORM */
/* This handles the woo checkout form we need to hook into the WooCheckout class and assign the correct post data variables. */
if(isset($_POST['custom-checkout']))
{
    global $woocommerce;
    do_action('woocommerce_init');
    
    require_once TEMPLATEPATH.'/classes/customWooCart.php';
    $customWooCart = new customWooCart;

    require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout;
    
    //Assign Woo Constant
    if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
            define( 'WOOCOMMERCE_CHECKOUT', true );
    }

    //If no items in cart redirect
    /*
    if ( sizeof( $customWooCart->get_current_cart() ) == 0 ) {
            wp_redirect( wc_get_page_permalink( 10 ) );
            exit;
    } 
     * 
     */
    $err = false;
    $validationResponse = false;
     
    switch(is_user_logged_in())
    {
        case false:
            $err = false;
            $validation = false;
            
            $billingFirstName = esc_sql(trim($_POST['billing_first_name']));
            $billingLastName = esc_sql(trim($_POST['billing_last_name']));
            $billingEmailAddress = esc_sql(trim($_POST['billing_email']));
            $billingPhoneNumber = esc_sql(trim($_POST['billing_phone']));
            //$billingMobileNumber = esc_sql(trim($_POST['billing_mobile_number']));
            $billingAddress = esc_sql(trim($_POST['billing_address_1']));
            $billingAddressTwo = esc_sql(trim($_POST['billing_address_2']));
            $billingCity = esc_sql(trim($_POST['billing_city']));
            $billingPostcode = esc_sql(trim($_POST['billing_postcode']));
            $orderComments = esc_sql(trim($_POST['order_comments']));
            
            $customWooCheckout->assignOurCheckoutSessionVars(array('billing_first_name' => $billingFirstName, 'billing_last_name' => $billingLastName, 'billing_email' => $billingEmailAddress, 'billing_phone' => $billingPhoneNumber, 'billing_address_1' => $billingAddress, 'billing_address_2' => $billingAddressTwo, 'billing_city' => $billingCity, 'billing_postcode' => $billingPostcode, 'order_comments' => $orderComments));
            
            if( empty($billingFirstName) || empty($billingLastName) || empty($billingEmailAddress) || empty($billingPhoneNumber) || empty($billingAddress) || empty($billingCity) || empty($billingPostcode) )
            {
                $err = true;
            }
            
            if (!filter_var($billingEmailAddress, FILTER_VALIDATE_EMAIL))
            {
                $validation = true;
                add_action( 'custom_process_notices', 'checkout_email_address_invalid', 9 );
            }

            if(strlen($billingPhoneNumber) < 4)
            {
                $validation = true;
                add_action( 'custom_process_notices', 'checkout_phone_number_invalid', 9 );
            }
            
            if( strlen($billingPostcode) < 4 )
            {
                $validation = true;
                add_action( 'custom_process_notices', 'checkout_postcode_invalid', 9 );
            }
            
            //array(4) { ["collection-picker"]=> string(10) "2015-06-12" ["drop-time"]=> string(8) "12pm-1pm" ["collect-time"]=> string(7) "5pm-6pm" ["dropoff-picker"]=> string(10) "2015-06-25" } 
            if(!isset($_SESSION['collection-picker']) || !isset($_SESSION['drop-time']) || !isset($_SESSION['collect-time']) || !isset($_SESSION['dropoff-picker']))
            {
                $validation = true;
                add_action( 'custom_process_notices', 'checkout_collectdeliver_invalid', 9 );
            }
            
            if(isset($_SESSION['collection-picker']) && isset($_SESSION['dropoff-picker']))
            {
                if($_SESSION['collection-picker'] < date("Y-m-d"))
                {
                    $validation = true;
                    add_action( 'custom_process_notices', 'checkout_collectdate_invalid', 9 );
                }

                if( ! ($_SESSION['dropoff-picker'] >= date("Y-m-d")) )
                {
                    $validation = true;
                    add_action( 'custom_process_notices', 'checkout_dropoffdate_invalid', 9 );
                }
                
               //Need to check the time of collection if the chosen date is the same
               $chosenCollectionDate = $_SESSION['collection-picker'];
               $chosenCollectionTime = $_SESSION['collect-time'];
               $chosenDropoffDate = $_SESSION['dropoff-picker'];
               $chosenDropoffTime = $_SESSION['drop-time'];

               if(($chosenDropoffDate == $chosenCollectionDate) || ($chosenCollectionDate == $chosenDropoffDate))
               {
                    if(checkSessionTimesBasedOnDropOffTimes($chosenDropoffTime, $chosenCollectionTime) == true)
                    {
                        $validation = true;
                        add_action( 'custom_process_notices', 'checkout_datelessthen_invalid', 9 );
                    }
               }
            }
            
            if($err == true)
            {
                    add_action( 'custom_process_notices', 'all_fields_are_required_to_checkout', 9 );
                    do_action('custom_checkout_process');
                    $validationResponse = true;
            }
            else if($validation == true)
            {
               do_action('custom_checkout_process');
               $validationResponse = true;
            }
            else
            {
                $validationResponse = false;
                $doProcessCustWooCheckout = processCustomWooCheckout($billingFirstName, $billingLastName, $billingEmailAddress, $billingPhoneNumber, $billingAddress, $billingAddressTwo, $billingCity, $billingPostcode, $orderComments);
            }
            break;
        case true:
           
            
            if(!isset($_SESSION['collection-picker']) || !isset($_SESSION['drop-time']) || !isset($_SESSION['collect-time']) || !isset($_SESSION['dropoff-picker']))
            {
                $validation = true;
                add_action( 'custom_process_notices', 'checkout_collectdeliver_invalid', 9 );
            }
            
            if(isset($_SESSION['collection-picker']) && isset($_SESSION['dropoff-picker']))
            {
                if($_SESSION['collection-picker'] < date("Y-m-d"))
                {
                    $validation = true;
                    add_action( 'custom_process_notices', 'checkout_collectdate_invalid', 9 );
                }

                if( ! ($_SESSION['dropoff-picker'] >= date("Y-m-d")) )
                {
                    $validation = true;
                    add_action( 'custom_process_notices', 'checkout_dropoffdate_invalid', 9 );
                }
                
               //Need to check the time of collection if the chosen date is the same
               $chosenCollectionDate = $_SESSION['collection-picker'];
               $chosenCollectionTime = $_SESSION['collect-time'];
               $chosenDropoffDate = $_SESSION['dropoff-picker'];
               $chosenDropoffTime = $_SESSION['drop-time'];

               if(($chosenDropoffDate == $chosenCollectionDate) || ($chosenCollectionDate == $chosenDropoffDate))
               {
                    if(checkSessionTimesBasedOnDropOffTimes($chosenDropoffTime, $chosenCollectionTime) == true)
                    {
                        $validation = true;
                        add_action( 'custom_process_notices', 'checkout_datelessthen_invalid', 9 );
                    }
               }
            }
            
            if($validation == true)
            {
               do_action('custom_checkout_process');
               $validationResponse = true;
            }
            else
            {
                $customWooCheckout->processUserCheckout();
            }

            break;
    }
}

function processCustomWooCheckout($billingFirstName, $billingLastName, $billingEmailAddress, $billingPhoneNumber, $billingAddress, $billingAddressTwo, $billingCity, $billingPostcode, $orderComments) {

    global $woocommerce;

    //Require Instance of customWoocheckout Class
    require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
    $custWooCheckout = new customWooCheckout;
    $checkoutArrys = array(
        'billing_first_name' => $billingFirstName,
        'billing_last_name' => $billingLastName,
        'billing_email' => $billingEmailAddress,
        'billing_phone' => $billingPhoneNumber,
        'billing_address_1' => $billingAddress,
        'billing_address_2' => $billingAddressTwo,
        'billing_city' => $billingCity,
        'billing_postcode' => $billingPostcode,
        'order_comments' => $orderComments,
    );
    $processCheckoutAssing = $custWooCheckout->assignOurCheckoutVars($checkoutArrys);
    if($processCheckoutAssing == true)
    {
        do_action('wp_loaded');
        
        $custWooCheckout->doProcessCheckout();
        //wp_redirect(get_permalink(6));
        //exit;
        
        //wc_add_notice('Process Error: Please refresh and try again.', 'error' );
        return true;
    }
    
    return false;
}

/* 
 * COLUMNISE 
 */
function columnize($items, $columns = 3, $min_per_column = 2)
{

    if(empty($items)) return array();

    $result     = array();
    $count      = count($items);
    $min_count  = $min_per_column * $columns;

    if($count <= $min_count) {
        $columns = ceil($count / $min_per_column);
    } else {    
        $per_column = floor($count / $columns);
        $overflow   = count($items) % $columns;
    }

    for($column = 0; $column < $columns; $column++) {
        if($count <= $min_count) {
            $item_count = $min_per_column;
        } else {
            $item_count = $per_column;
            if($overflow > 0) {
                $item_count++;
                $overflow--;
            }
        }
        $result[$column] = array_slice($items, 0, $item_count);
        $items = array_slice($items, $item_count);
    }

    return $result;
}

/* 
 * Custom woo errors 
 */
function all_fields_are_required_to_checkout()
{
    wc_print_notice( __( 'Please complete all required fields to proceed with checkout.', 'woocommerce' ), 'error' );
}

function checkout_email_address_invalid()
{
    wc_print_notice( __( 'Your email address is not a valid format.', 'woocommerce' ), 'error' );
}

function checkout_phone_number_invalid()
{
    wc_print_notice( __( 'Your phone number is not a valid format.', 'woocommerce' ), 'error' );
}

function checkout_basket_updated_message()
{
    wc_print_notice( __( 'Your basket has been updated.', 'woocommerce' ), 'success' );
}

function checkout_postcode_invalid()
{
    wc_print_notice( __( 'Please provide your full postcode.', 'woocommerce' ), 'error' );
}

function checkout_collectdeliver_invalid()
{
    wc_print_notice( __( 'Please choose your collection and delivery options.', 'woocommerce' ), 'error' );
}

function checkout_collectdate_invalid()
{
    wc_print_notice( __( 'Collection Date: Please choose a date which corresponds with today or in the future!', 'woocommerce' ), 'error' );
}

function checkout_dropoffdate_invalid()
{
    wc_print_notice( __( 'Dropoff Date: Please choose todays date or a date after today', 'woocommerce' ), 'error' );
}

function checkout_datelessthen_invalid()
{
    wc_print_notice( __( 'Dropoff Time: Your dropoff time is before your collection time.', 'woocommerce' ), 'error' );
}

/*
 * Get woo customer details function
*/
function customWooGetUserDetails($user_id)
{
    $wooDetails = array(
        'shipping_first_name' => get_user_meta( $user_id, 'shipping_first_name', true ),
        'shipping_last_name' => get_user_meta( $user_id, 'shipping_last_name', true ),
        'shipping_company' => get_user_meta( $user_id, 'shipping_company', true ),
        'shipping_address_1' => get_user_meta( $user_id, 'shipping_address_1', true ),
        'shipping_address_2' => get_user_meta( $user_id, 'shipping_address_2', true ),
        'shipping_city' => get_user_meta( $user_id, 'shipping_city', true ),
        'shipping_state' => get_user_meta( $user_id, 'shipping_state', true ),
        'shipping_postcode' => get_user_meta( $user_id, 'shipping_postcode', true ),
        'shipping_country' => get_user_meta( $user_id, 'shipping_country', true ),
        
    );
    if(count($wooDetails))
    {
        return $wooDetails;
    }
    
    return;
}

/*
 * 
 */

/*
 * Get user sidebar for checkout
 */
function showUserDetailsSidebarByUser()
{
    if(is_user_logged_in())
    {
        $user = wp_get_current_user();
        $currCustomer = customWooGetUserDetails($user->data->ID);
    } 
?>
<h4>Your Address & Details</h4>
    <div class="account-main-details border-spacer relative">
    <?php
    echo ((!empty($currCustomer['shipping_first_name']))) ? '<p>'.$currCustomer['shipping_first_name'].' '.$currCustomer['shipping_last_name'].'</p>' : '';
    echo ((!empty($currCustomer['shipping_address_1']))) ? '<p>'.$currCustomer['shipping_address_1'].'</p>' : '';
    echo ((!empty($currCustomer['shipping_address_2']))) ? '<p>'.$currCustomer['shipping_address_2'].'</p>' : '';
    echo ((!empty($currCustomer['shipping_city']))) ? '<p>'.$currCustomer['shipping_city'].'</p>' : '';
    echo ((!empty($currCustomer['shipping_state']))) ? '<p>'.$currCustomer['shipping_state'].'</p>' : '';
    echo ((!empty($currCustomer['shipping_postcode']))) ? '<p>'.$currCustomer['shipping_postcode'].'</p>' : '';
    echo ((!empty($currCustomer['shipping_country']))) ? '<p>'.$currCustomer['shipping_country'].'</p>' : '';
    ?>
       <a class="editdetailsReveal edit-setting-button" href="#" data-reveal-id="editdetailsModal">Edit Details</a>
    </div>
    <?php
    //Get Collection and delivery details
    require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout;
    $userCollectionDate = $customWooCheckout->getSessionValue('collection-picker');
    $userCollectionTime = $customWooCheckout->getSessionValue('collect-time');
    $userDropOffDate = $customWooCheckout->getSessionValue('dropoff-picker');
    $userDropOffTime = $customWooCheckout->getSessionValue('drop-time');
    
    if(!empty($userCollectionDate))
    {
        //Collection
        $date = new DateTime();
        $date = DateTime::createFromFormat('Y-m-d', $userCollectionDate);
        $output = $date->format('jS F, Y');
    }
    if(!empty($userDropOffDate))
    {
        //Dropoff
        $dateTwo = new DateTime();
        $dateTwo = DateTime::createFromFormat('Y-m-d', $userDropOffDate);
        $outputTwo = $dateTwo->format('jS F, Y');
    }
    
    if(!empty($output) && !empty($userCollectionTime))
    {
    ?>
    <div class="relative">
        <div class="row account-orderdates-details relative">
            <div class="small-12 medium-4 large-4 columns">
                <span class="label-title">Collection</span>
            </div>
            <div class="small-12 medium-8 large-8 columns collection-data-container">
                <?php echo '<p data-type="collection" class="co-collect-chosen-date">'.$output .'</p>'; ?>
                <?php echo '<p data-type="collection" id="collection" class="co-collect-chosen-time">Between '. $userCollectionTime .'</p>'; ?>
            </div>
            <a class="editCollectReveal edit-setting-button" href="#" data-reveal-id="editCollectionModal">Edit Collection</a>    
        </div>
    <?php
    }
    if(!empty($outputTwo) && !empty($userDropOffTime))
    {
    ?>
        <div class="row account-orderdates-details relative">
            <div class="small-12 medium-4 large-4 columns">
                <span class="label-title">Delivery</span>
            </div>
            <div class="small-12 medium-8 large-8 columns dropoff-data-container">
                <?php echo '<p data-type="dropoff" class="co-deliver-chosen-date">'.$outputTwo .'</p>'; ?>
                <?php echo '<p data-type="dropoff" class="co-deliver-chosen-time">Between '. $userDropOffTime .'</p>'; ?>
            </div>
            <a class="editDropReveal edit-setting-button" href="#" data-reveal-id="editDropoffModal">Edit Delivery</a>
        </div>
    </div>
    <?php
    }
}

/*
 * Get user sidebar for checkout by posted variables
 */
function showUserDetailsSidebarByPost($getPost)
{
    //Post varaible data
    $billingFirstName = esc_sql(trim($getPost['billing_first_name']));
    $billingLastName = esc_sql(trim($getPost['billing_last_name']));
    $billingEmailAddress = esc_sql(trim($getPost['billing_email']));
    $billingPhoneNumber = esc_sql(trim($getPost['billing_phone']));
    $billingAddress = esc_sql(trim($getPost['billing_address_1']));
    $billingAddressTwo = esc_sql(trim($getPost['billing_address_2']));
    $billingCity = esc_sql(trim($getPost['billing_city']));
    $billingPostcode = esc_sql(trim($getPost['billing_postcode']));
    $orderComments = esc_sql(trim($getPost['order_comments']));
    ?>

<h4>Your Address & Details</h4>
    <div class="account-main-details relative">
    <?php
    echo ((!empty($billingFirstName))) ? '<p>'.$billingFirstName.' '.$billingLastName.'</p>' : '';
    echo ((!empty($billingAddress))) ? '<p>'.$billingAddress.'</p>' : '';
    echo ((!empty($billingAddressTwo))) ? '<p>'.$billingAddressTwo.'</p>' : '';
    echo ((!empty($billingCity))) ? '<p>'.$billingCity.'</p>' : '';
    echo ((!empty($billingPostcode))) ? '<p>'.$billingPostcode.'</p>' : '';
    ?>
       
    </div>
    
    <div class="account-contact-details relative">
    <?php
    if(!empty($billingPhoneNumber))
    {
        ?>
        <div class="row">
            <div class="small-12 medium-4 large-4 columns">
                <span class="label-title">Phone</span>
            </div>
            <div class="small-12 medium-8 large-8 columns">
                <?php 
                echo $billingPhoneNumber; ?>
            </div>
        </div>
        <?php
    }
    if(!empty($billingEmailAddress))
    {
       ?>
        <div class="row">
            <div class="small-12 medium-4 large-4 columns">
                <span class="label-title">Email</span>
            </div>
            <div class="small-12 medium-8 large-8 columns">
                <?php echo $billingEmailAddress; ?>
            </div>
        </div>
        <?php
    }
    ?>
        
    <a class="editdetailsReveal edit-setting-button" href="#" data-reveal-id="editdetailsModal">Edit Details</a>
    </div>
    <?php
    //Get Collection and delivery details
    require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout;
    $userCollectionDate = $customWooCheckout->getSessionValue('collection-picker');
    $userCollectionTime = $customWooCheckout->getSessionValue('collect-time');
    $userDropOffDate = $customWooCheckout->getSessionValue('dropoff-picker');
    $userDropOffTime = $customWooCheckout->getSessionValue('drop-time');
    
    if(!empty($userCollectionDate))
    {
        //Collection
        $date = new DateTime();
        $date = DateTime::createFromFormat('Y-m-d', $userCollectionDate);
        $output = $date->format('jS F, Y');
    }
    if(!empty($userDropOffDate))
    {
        //Dropoff
        $dateTwo = new DateTime();
        $dateTwo = DateTime::createFromFormat('Y-m-d', $userDropOffDate);
        $outputTwo = $dateTwo->format('jS F, Y');
    }
    
    if(!empty($output) && !empty($userCollectionTime))
    {
    ?>
    <div class="relative">
        <div class="row account-orderdates-details relative">
            <div class="small-12 medium-4 large-4 columns">
                <span class="label-title">Collection</span>
            </div>
            <div class="small-12 medium-8 large-8 columns collection-data-container">
                <?php echo '<p data-type="collection" class="co-collect-chosen-date">'.$output .'</p>'; ?>
                <?php echo '<p data-type="collection" id="collection" class="co-collect-chosen-time">Between '. $userCollectionTime .'</p>'; ?>
            </div>
            <a class="editCollectReveal edit-setting-button" href="#" data-reveal-id="editCollectionModal">Edit Collection</a>    
        </div>
    <?php
    }
    if(!empty($outputTwo) && !empty($userDropOffTime))
    {
    ?>
        <div class="row account-orderdates-details relative">
            <div class="small-12 medium-4 large-4 columns">
                <span class="label-title">Delivery</span>
            </div>
            <div class="small-12 medium-8 large-8 columns dropoff-data-container">
                <?php echo '<p data-type="dropoff" class="co-deliver-chosen-date">'.$outputTwo .'</p>'; ?>
                <?php echo '<p data-type="dropoff" class="co-deliver-chosen-time">Between '. $userDropOffTime .'</p>'; ?>
            </div>
            <a class="editDropReveal edit-setting-button" href="#" data-reveal-id="editDropoffModal">Edit Delivery</a>
        </div>
    </div>
    <?php
    }
}

/*
 * Just display the session details with no posted data and not loggedin.
 */
function showSessionSelectedDetails()
{
    return;
}

/*
* Update product quantities handler
*/
/*
if(isset($_POST['co-review-update-qty']))
{
    global $woocommerce;
    $woocommerce->init();
    do_action('wp_loaded');
    
    //echo '<pre>';
    //var_dump($woocommerce->cart->get_cart());die;
    //echo '</pre>';
    
    $cartInstance = WC()->instance()->cart;
    if ( ! defined( 'WOOCOMMERCE_CART' ) ) {
    define( 'WOOCOMMERCE_CART', true );
}
    $cartInstance->calculate_totals();
    
    $cartQuantities = isset( $_POST['quantity'] ) ? $_POST['quantity'] : '';
    
    //var_dump($woocommerce->cart->get_cart());die;
    $showWooNotice = false;
    if ( sizeof($cartInstance->get_cart()) > 0 && is_array($cartQuantities))
    {
        foreach ( $cartInstance->get_cart() as $cart_item_key => $values )
        {
   
            // Skip product if no updated quantity was posted
            if ( ! isset( $cartQuantities[ $cart_item_key ] ) || ! isset( $cartQuantities[ $cart_item_key ]['qty'] ) ) {
                    continue;
            }
            
            //var_dump($values);
            // Sanitize
	    $quantity = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( "/[^0-9\.]/", '', $cartQuantities[ $cart_item_key ]['qty'] ) ), $cart_item_key );
            
            if ( '' === $quantity || $quantity == $values['quantity'] )
                  continue;

            // Update cart validation
            $passed_validation 	= apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $values, $quantity );

            if ( $passed_validation )
            { 
                $doUpdateQty = $cartInstance->set_quantity( $cart_item_key, $quantity, false );
                $cart_updated = true;
                if($doUpdateQty == true)
                {
                    
                   // Trigger action - let 3rd parties update the cart if they need to and update the $cart_updated variable
                   $cart_updated = apply_filters( 'woocommerce_update_cart_action_cart_updated', $cart_updated );

                   $refreshCartInstance = $cartInstance->calculate_totals();
                   if($refreshCartInstance)
                   {
                       $cart_updated = true;   
                   }
                    $showWooNotice = true;
                }

            }
        }


    }
    
    if($showWooNotice == true)
    {
        add_action( 'checkout_process_notices', 'checkout_basket_updated_message', 9 );
    }
    
}
 * 
 */

add_action('woocommerce_checkout_update_order_meta','custom_woo_order_details', 1, 2);
if(!function_exists('custom_woo_order_details'))
{
  function custom_woo_order_details($orderId, $postedDataArray)
  {
        global $woocommerce, $wpdb;
        
        require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
        $customWooCheckout = new customWooCheckout();
        
        $cwcDataCollectionDate = $customWooCheckout->getSessionValue('collection-picker');
        $cwcDataCollectionTime = $customWooCheckout->getSessionValue('collect-time');
        $cwcDataDropoffDate = $customWooCheckout->getSessionValue('dropoff-picker');
        $cwcDataDropoffTime = $customWooCheckout->getSessionValue('drop-time');

        $odtData = array(
            'odt_order_id' => $orderId, 
            'odt_collect_date' => $cwcDataCollectionDate,
            'odt_collect_time' => $cwcDataCollectionTime,
            'odt_dropoff_date' => $cwcDataDropoffDate,
            'odt_dropoff_time' => $cwcDataDropoffTime
        );
        $customWooCheckout->setOrderODTData($odtData);
        
        //We now need to remove session data
        $customWooCheckout->removeSessionbyKey('collection-picker');
        $customWooCheckout->removeSessionbyKey('drop-time');
        $customWooCheckout->removeSessionbyKey('collect-time');
        $customWooCheckout->removeSessionbyKey('dropoff-picker');
  }
}

/* Order Admin */
add_action( 'add_meta_boxes', 'add_meta_boxes' );
function add_meta_boxes()
{
    add_meta_box( 
        'woocommerce-order-my-custom', 
        __( 'Collection &amp; Delivery' ), 
        'order_collect_delivery', 
        'shop_order', 
        'side', 
        'default' 
    );
}
function order_collect_delivery()
{ 
    $postid = get_the_ID();
    //echo 'Below are the chosen collection and delivery details for this order.';
   
    require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout();
    $orderODTData = $customWooCheckout->getOrderODTData($postid);
    //var_dump($orderODTData);

    if(is_array($orderODTData))
    {
       ?>
        <ul class="order_details">
            <li class="oc-collect-date">
                    Collection Date:
                    <strong><?php echo $orderODTData[0]->odt_collect_date; ?></strong>
            </li>
            <li class="oc-collect-time">
                    Collection Time:
                    <strong><?php echo $orderODTData[0]->odt_collect_time; ?></strong>
            </li>
            <li class="oc-drop-date">
                    Dropoff Date:
                    <strong><?php echo $orderODTData[0]->odt_dropoff_date; ?></strong>
            </li>
            <li class="oc-drop-time">
                    Dropoff Time:
                    <strong><?php echo $orderODTData[0]->odt_dropoff_time; ?></strong>
            </li>
    </ul>
    <div class="clear"></div>
        <?php
    }
}

function getPostcodeSessionDetails()
{
    require_once TEMPLATEPATH .'/classes/postcodeLookUp.php';
    $postcodeLookUp = new postcodeLookUp();
    $chosenSessPostcode = $postcodeLookUp->getPostcode();
    if(!empty($chosenSessPostcode))
    {
        return $chosenSessPostcode;
    }
    return null;
}

function taxonomyRedirect()
{
    $cpt = 'product';
    $tax = 'product_cat';
    $ourTermRedirect = get_term_link(6, $tax);
    wp_redirect($ourTermRedirect);
    exit;
}

function checkSessionTimesBasedOnDropOffTimes($theChosenTimes, $chosenCollectionTime)
{
    $timePickerDates = array(
        0 => '8am-9am',
        1 => '10am-11am',
        2 => '11am-12pm',
        3 => '12pm-1pm',
        4 => '2pm-3pm',
        5 => '3pm-4pm',
        6 => '5pm-6pm',
        7 => '6pm-7pm'
    );
    
    $chosenKey = array_search($theChosenTimes, $timePickerDates);
    $collectionKey = array_search($chosenCollectionTime, $timePickerDates);
    
    if($chosenKey <= $collectionKey)
    {
        return true;
    }
    return false;
}

function getPostedValueByKey($valueKey)
{
    if(isset($valueKey))
    {
        if(isset($_POST[$valueKey]))
        {
            return $_POST[$valueKey];
        }
        else if(isset($_SESSION['sessposted'][$valueKey])) 
        {
            return $_SESSION['sessposted'][$valueKey];
        }
        else
        {
            return;
        }
    }
    return;
}

function flagSectionIfCompleted($flagType)
{
    require_once TEMPLATEPATH.'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout();
    
    $cwcDataCollectionDate = $customWooCheckout->getSessionValue('collection-picker');
    $cwcDataCollectionTime = $customWooCheckout->getSessionValue('collect-time');
    $cwcDataDropoffDate = $customWooCheckout->getSessionValue('dropoff-picker');
    $cwcDataDropoffTime = $customWooCheckout->getSessionValue('drop-time');
    
    switch($flagType)
    {
        case 'collection':

            if(isset($cwcDataCollectionDate) && !empty($cwcDataCollectionDate) 
                    && isset($cwcDataCollectionTime) && !empty($cwcDataCollectionTime))
            {
                return true;
            }
            
        break;
        case 'dropoff':
            
           if(isset($cwcDataDropoffDate) && !empty($cwcDataDropoffDate) 
                   && isset($cwcDataDropoffTime) && !empty($cwcDataDropoffTime))
            {
                return true;
            }
            
        break;
    }
    
    return false;
}