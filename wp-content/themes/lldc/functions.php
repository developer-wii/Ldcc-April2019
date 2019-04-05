<?php
/**
 * LLDC functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package LLDC
 */

if ( ! function_exists( 'lldc_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function lldc_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on LLDC, use a find and replace
		 * to change 'lldc' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'lldc', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'lldc' ),
			'menu-2' => esc_html__( 'Dirver Menu', 'lldc' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'lldc_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'lldc_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function lldc_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'lldc_content_width', 640 );
}
add_action( 'after_setup_theme', 'lldc_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function lldc_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'lldc' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'lldc' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'lldc_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function lldc_scripts() {
	wp_enqueue_style( 'lldc-style', get_stylesheet_uri() );

	wp_enqueue_script( 'lldc-JsBarcode-js', get_template_directory_uri() . '/js/JsBarcode.all.min.js', array(), '20151215', true );

//	wp_enqueue_script( 'lldc-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	//wp_enqueue_script( 'lldc-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'lldc_scripts' );

/*
	Enqueue scripts and styles admin side.
*/

function lldc_admin_scripts() {

	// admin custom css
	wp_enqueue_style( 'lldc-admin-custom-css', get_template_directory_uri() . '/css/admin_custom_css.css');


	//wp_enqueue_script( 'lldc-JQuery', get_template_directory_uri() . '/js/jquery.js', array(), '20151215', true );
	// barcode generator.
	wp_enqueue_script( 'lldc-JsBarcode-js', get_template_directory_uri() . '/js/JsBarcode.all.min.js', array(), '20151215', true );
	wp_enqueue_script( 'lldc-admin-custom-js', get_template_directory_uri() . '/js/admin_custom.js', array(), '20151215', true );
}
add_action( 'admin_enqueue_scripts', 'lldc_admin_scripts' );



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
require_once( get_template_directory() . '/inc/class-order_product.php');
require_once( get_template_directory() . '/inc/class-registrations.php');
require_once( get_template_directory() . '/inc/class-shop_control_panel.php');

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


function headertopmenu() {
	register_sidebar(
		array (
			'name' => __( 'Header Top', 'headertopmenu' ),
			'id' => 'sidebar-2',
			'description' => __( 'headertopmenu', 'your-theme-domain' ),
			'before_widget' => '',
			'after_widget' => "",
			'before_title' => '',
			'after_title' => '',
		)
	);
}
add_action( 'widgets_init', 'headertopmenu' );
function sidebarsocialicons() {
	register_sidebar(
		array (
			'name' => __( 'Social Icons', 'sidebaricon' ),
			'id' => 'sidebar-3',
			'description' => __( 'sidebarsocialicons', 'your-theme-domain' ),
			'before_widget' => '',
			'after_widget' => "",
			'before_title' => '',
			'after_title' => '',
		)
	);

	 register_sidebar(
		array (
			'name' => __( 'Footer Copyright', 'sidebaricon' ),
			'id' => 'footer-copyright',
			'description' => __( 'sidebarsocialicons', 'your-theme-domain' ),
			'before_widget' => '',
			'after_widget' => "",
			'before_title' => '',
			'after_title' => '',
		)
	);

}
add_action( 'widgets_init', 'sidebarsocialicons' );
add_action( 'customize_register', 'genesischild_register_theme_customizer' );

add_action( 'customize_register', 'genesischild_register_theme_customizer' );
function genesischild_register_theme_customizer( $wp_customize ) {
	// Create custom panel.
	$wp_customize->add_panel( 'text_blocks', array(
		'priority'       => 500,
		'theme_supports' => '',
		'title'          => __( 'Phone Number', 'lldc' ),
		'description'    => __( 'Set editable text for certain content.', 'lldc' ),
	) );
	$wp_customize->add_section( 'custom_footer_text' , array(
		'title'    => __('Change Phone Number','lldc'),
		'panel'    => 'text_blocks',
		'priority' => 10
	) );
	// Add setting
	$wp_customize->add_setting( 'footer_text_block', array(
		 'default'           => __( 'default text', 'lldc' ),
		 'sanitize_callback' => 'sanitize_text'
	) );
	// Add control
	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize,
		'custom_footer_text',
			array(
				'label'    => __( 'Phone Number', 'lldc' ),
				'section'  => 'custom_footer_text',
				'settings' => 'footer_text_block',
				'type'     => 'text'
			)
		)
	);
	 // Sanitize text
	function sanitize_text( $text ) {
		return sanitize_text_field( $text );
	}
}

function enqueue_cart_qty_ajax() {
  if(!(is_cart()))
  {
  //  wp_register_script( 'cart-qty-ajax-js', get_template_directory_uri() . '/js/cart-qty-ajax.js', array( 'jquery' ), '', true );
   // wp_localize_script( 'cart-qty-ajax-js', 'cart_qty_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
  //  wp_enqueue_script( 'cart-qty-ajax-js' );
   }
if(is_cart() ) { 		 
	//wp_dequeue_script('woocommerce');		
	wp_deregister_script('woocommerce');		
	wp_enqueue_script( 'woocommerce', get_template_directory_uri(). '/woocommerce/js/woocommerce.js' , array( 'jquery' ), false, true ); 
}
}
add_action('wp_enqueue_scripts', 'enqueue_cart_qty_ajax');

/* ajax cart quantity functionality code start*/
function ajax_qty_cart() {
  $db = new OrderProduct_Function();
	$response = $db->ajax_quantity_cart();
	echo $response;
//die();

}
add_action('wp_ajax_qty_cart', 'ajax_qty_cart');
add_action('wp_ajax_nopriv_qty_cart', 'ajax_qty_cart');
/* ajax cart quantity functionality code end*/


// WooCommerce Checkout Fields Hook
/* add_filter('woocommerce_checkout_fields','custom_wc_checkout_fields_no_label');

// Our hooked in function - $fields is passed via the filter!
// Action: remove label from $fields
function custom_wc_checkout_fields_no_label($fields) {
	$db = new OrderProduct_Function();
	$response = $db->remove_label_from_billingaddress($fields);
	return $response;
} */
/* change in woocommerce default address fields code start */
add_filter('woocommerce_default_address_fields', 'override_default_address_checkout_fields', 20, 1);
function override_default_address_checkout_fields( $address_fields ) {
	$db = new OrderProduct_Function();
	$response = $db->change_label_from_defaultaddress($address_fields);
	return $response;
}
/* change in woocommerce default address fields code end */

/* change in woocommerce checkout address fields code start */
add_filter('woocommerce_checkout_fields', 'update_woocommerce_checkout_fields',5,1);
function update_woocommerce_checkout_fields( $address_fields ) {
	$db = new OrderProduct_Function();
	$response = $db->change_label_from_checkoutaddress($address_fields);
	return $response;
}
/* change in woocommerce checkout address fields code start */

/* session start code*/
if(!is_page(21)){
function register_session(){
	if( !session_id() )
		session_start();
}
add_action('init','register_session');
}
/* session start code end*/
/* Update order meta for custom checkout fields code start*/

add_action('woocommerce_checkout_update_order_meta', 'customise_checkout_field_update_order_meta');

function customise_checkout_field_update_order_meta($order_id)
{
	if (!empty($_POST['datepicker_order'])) {
		update_post_meta($order_id, 'woo_custom_datepicker', sanitize_text_field($_POST['datepicker_order']));
	}

	if (!empty($_POST['contractor_deliverytime'])) {
		update_post_meta($order_id, 'woo_custom_time', $_POST['contractor_deliverytime']);
	}
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){

	$db = new OrderProduct_Function();
	$db->checkout_field_display_admin_order_meta($order);

}
/* Update order meta for custom checkout fields code end*/

// define the woocommerce_admin_order_data_after_order_details callback
function action_woocommerce_admin_order_data_after_order_details( $wccm_before_checkout ) {

	// adding and generating barcode here
	echo '<script> var bar_id = '.$wccm_before_checkout->id.'; </script>';
	$barcode = '<svg id="barcode"></svg>';
	echo "<table class='barcode_section'><tr>";
	echo '<p><td><strong>'.__('Order Barcode ').':</strong> </td><td>' . $barcode . '</td></p>';
	echo "<table></tr>";
}
// add the action
add_action( 'woocommerce_admin_order_data_after_order_details', 'action_woocommerce_admin_order_data_after_order_details', 10, 1 );


function store_zipcode() {
	$_SESSION['orderpincode']=$_POST['postcode'];
}
add_action('wp_ajax_store_zipcode', 'store_zipcode');
add_action('wp_ajax_nopriv_store_zipcode', 'store_zipcode');

add_action( 'woocommerce_after_checkout_form' , 'my_custom_checkout_field_postcode' );
function my_custom_checkout_field_postcode( ) {
   //var test= $_SESSION['orderpincode'];
   ?>
	<script>
		(function($) {
			var postcode_order = '<?php echo $_SESSION['
			orderpincode '];?>';
			$('#billing_postcode').val(postcode_order);
		})(jQuery);

	</script>
	<?php
}
/*login functionality code start*/
function custom_login(){
	if(isset($_POST['custom_login'])){
		$db = new DifferentUserRegistration_Function();
		echo $response = $db->logincustom();
}
}
add_action( 'after_setup_theme', 'custom_login' );
/*login functionality code end*/

/*view order by id ajax function in shop control page code start*/
function view_particular_order() {
	$order_id=$_POST['orderid'];
	$db = new ShopControlPanel_Function();
	echo $response = $db->vieworderbyid($order_id);
	//return $response;
	die();
}
add_action('wp_ajax_view_particular_order', 'view_particular_order');
add_action('wp_ajax_nopriv_view_particular_order', 'view_particular_order');
/*view order by id ajax function in shop control page code start*/

/*order status cahange and mail to customer : start*/
function update_order_status() {
	$order_id   = $_POST['orderid'];
	$status     = $_POST['status'];

	$db = new OrderProduct_Function();
	$db->order_status_change( $order_id , $status);

	$order = wc_get_order( $order_id );
	$to = $order->billing_email;
	$subject = 'User Order From London dry cleaning company.';
	$body = 'Hello <b>'.$order->billing_first_name.' '.$order->billing_last_name.'</b>, your order ( id #'.$order_id.') is on its way. Thank you.';
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $body, $headers );
}
add_action('wp_ajax_update_order_status', 'update_order_status');
add_action('wp_ajax_nopriv_update_order_status', 'update_order_status');
/*order status cahange and mail to customer : end*/

/*move order to trash by id ajax function in shop control page code start*/
function moveordertotrash() {
	$order_id=$_POST['orderid'];
	$db = new ShopControlPanel_Function();
	echo $response = $db->moveorderto_trash_byid($order_id);
	//return $response;
	die();
}
add_action('wp_ajax_moveordertotrash', 'moveordertotrash');
add_action('wp_ajax_nopriv_moveordertotrash', 'moveordertotrash');
/*move order to trash by id ajax function in shop control page code start*/

if(isset($_POST['user_registration'])){
	$db = new DifferentUserRegistration_Function();
	echo $response = $db->user_registration();
}

/*contractor custom logo folder code start*/
function contractor_custom_upload_dir($dir_data){
	$custom_dir = 'contractor-logos';
	return [
		'path' => $dir_data[ 'basedir' ] . '/' . $custom_dir,
		'url' => $dir_data[ 'url' ] . '/' . $custom_dir,
		'subdir' => '/' . $custom_dir,
		'basedir' => $dir_data[ 'error' ],
		'error' => $dir_data[ 'error' ],
	];
}
/*contractor custom logo folder code end*/

/*view order by id for contractor ajax function in contractor history page code start*/
function view_contractor_order() {
	$order_id=$_POST['orderid'];
	$db = new OrderProduct_Function();
	echo $response = $db->vieworderforcontractor($order_id);
	//return $response;
	die();
}
add_action('wp_ajax_view_contractor_order', 'view_contractor_order');
add_action('wp_ajax_nopriv_view_contractor_order', 'view_contractor_order');
/*view order by id for contractor ajax function in contractor history page code end*/

/*move contractor order to trash by id ajax function in shop control page code start*/
function move_contractor_order_totrash() {
	$order_id=$_POST['orderid'];
	$db = new OrderProduct_Function();
	echo $response = $db->move_contractor_order_totrash($order_id);
	//return $response;
	die();
}
add_action('wp_ajax_move_contractor_order_totrash', 'move_contractor_order_totrash');
add_action('wp_ajax_nopriv_move_contractor_order_totrash', 'move_contractor_order_totrash');
/*move contractor order to trash by id ajax function in shop control page code end*/

/*view user by id ajax function in admin panel page code start*/
function view_user() {
	$id=$_POST['id'];
	$role=$_POST['role'];
	$db = new OrderProduct_Function();
	echo $response = $db->viewuserbyid($id,$role);
	//return $response;
	die();
}
add_action('wp_ajax_view_user', 'view_user');
add_action('wp_ajax_nopriv_view_user', 'view_user');
/*view user by id ajax function in admin panel page code end*/


//The folloing is the code to apply discount coupon automatically when the quantity of products in cart is greater than 10
/*   add_action('woocommerce_before_cart_table', 'discount_when_quantity_greater_than_10');
function discount_when_quantity_greater_than_10() {
	$user = wp_get_current_user();
	global $woocommerce;
	global $total_qty;
	if ( in_array( 'student', (array) $user->roles ) ) {
		$coupon_code = 'student_discount';
		if (!$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ))) {
			$woocommerce->show_messages();
		}
	   // echo '<div class="woocommerce_message"><strong>The number of Product in your order is greater than 10 so a 10% Discount has been Applied!</strong></div>';
	}
} */

// Show Admin bar
function remove_admin_bar()
{
	if(is_user_logged_in()){
		return true;
	}
}
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar

/* Woocommerce auto register guest users code start */
function wc_register_guests( $order_id ) {
	$db = new OrderProduct_Function();
	echo $response = $db->register_guest_users($order_id);
  // get all the order data
}
//add this newly created function to the thank you page
add_action( 'woocommerce_thankyou', 'wc_register_guests', 10, 1 );
/* Woocommerce auto register guest users code end */

/* change name of the email address code start*/
add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
function my_mail_from_name( $name ) {
	return "Lodondrycleaningcompany";
}
/* change name of the email address code end*/

/* Delete user - Admin panel code start*/
function deleteuser(){
	$userid=$_POST['userid'];
	wp_delete_user($userid);
}
add_action('wp_ajax_deleteuser', 'deleteuser');
add_action('wp_ajax_nopriv_deleteuser', 'deleteuser');
/* Delete user - Admin panel code end*/


/* adding

 class to the menu on active link */

function special_nav_class ($classes, $item) {
	if (in_array('current-menu-item', $classes) ){
		$classes[] = 'active ';
	}
	return $classes;
}
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);


/* filter driver menu :- remove menu list item for specific page */

function filter_wp_nav_menu_objects( $sorted_menu_items, $args ) {
	if(!is_page(160) && $args->menu == "Driver Menu"){
		array_pop($sorted_menu_items);
	}
	return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'filter_wp_nav_menu_objects', 10, 2 );


// adding new order status to the list of order status  code start
function register_custom_order_status() {


	$db = new OrderProduct_Function();

	$order_status_name      = "wc-out-delivery";
	$lable                  = "Out for delivery";
	$db->fn_register_post_status($order_status_name,$lable);

	$order_status_name      = "wc-pending-order";
	$lable                  = "Pending-order";
	$db->fn_register_post_status($order_status_name,$lable);

}
add_action( 'init', 'register_custom_order_status' );
// adding new order status to the list of order status code end


// Add to list of WC Order statuses code start
function add_custom_status_to_order_statuses( $order_statuses ) {

	$db = new OrderProduct_Function();
	$return = $db->add_custom_order_status_to_list_of_order_status($order_statuses);

	return $return;
}
add_filter( 'wc_order_statuses', 'add_custom_status_to_order_statuses' );
// Add to list of WC Order statuses code end


// ---------------------
// change status on order palace successfully code start

add_action( 'woocommerce_thankyou', 'change_status_thankyou_change_order_status' );

function change_status_thankyou_change_order_status( $order_id ){
	if( ! $order_id ) return;

	$order = wc_get_order( $order_id );
	$order->update_status( 'pending-order' );
}
// change status on order palace successfully code end

// woo cloud cron print return false code start
add_filter('xc_woo_cloud_cron_print_orders', __return_false);
// woo cloud cron print return false code end


// change avatav of user ( dispaly custom avatar ) code start
add_filter( 'get_avatar', 'slug_get_avatar', 10, 5 );
function slug_get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

	//If is email, try and find user ID
	if( ! is_numeric( $id_or_email ) && is_email( $id_or_email ) ){
		$user  =  get_user_by( 'email', $id_or_email );
		if( $user ){
			$id_or_email = $user->ID;
		}
	}

	//if not user ID, return
	if( ! is_numeric( $id_or_email ) ){
		return $avatar;
	}

	//Find URL of saved avatar in user meta
	$saved = get_user_meta( $id_or_email, 'contractor_logo', true );
	//check if it is a URL
	if( filter_var( $saved, FILTER_VALIDATE_URL ) ) {
		//return saved image
		// if (strpos ($_SERVER ['REQUEST_URI'] , 'wp-admin/profile.php' )) {
		return sprintf( '<img src="%s" alt="%s" height="%s" width="%s" />', esc_url( $saved ), esc_attr( $alt ), esc_attr( $size ), esc_attr( $size ) );
		//}

	}

	//return normal
	return $avatar;

}
// change avatav of user ( dispaly custom avatar ) code end

//custom post type
function create_posttype() {


	register_post_type( 'Alter & repairs',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Alter & repairs' ),
				'singular_name' => __( 'Alter & repairs' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'alter-repairs'),
			'supports' => array( 'title', 'author', 'thumbnail','headway-seo'),

		)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

function spyr_coupon_redeem_handler() {

	// Get the value of the coupon code
	$code = $_REQUEST['coupon_code'];

	// Check coupon code to make sure is not empty
	if( empty( $code ) || !isset( $code ) ) {
		// Build our response
		$response = array(
			'result'    => 'error',
			'message'   => 'Code text field can not be empty.'
		);

		header( 'Content-Type: application/json' );
		echo json_encode( $response );

		// Always exit when doing ajax
		exit();
	}

	// Create an instance of WC_Coupon with our code
	$coupon = new WC_Coupon( $code );

	// Check coupon to make determine if its valid or not
	if( ! $coupon->id && ! isset( $coupon->id ) ) {
		// Build our response
		$response = array(
			'result'    => 'error',
			'message'   => 'Invalid code entered. Please try again.'
		);

		header( 'Content-Type: application/json' );
		echo json_encode( $response );

		// Always exit when doing ajax
		exit();

	} else {
		// Coupon must be valid so we must
		// populate the cart with the attached products
		foreach( $coupon->product_ids as $prod_id ) {
			WC()->cart->add_to_cart( $prod_id );
		}

		// Build our response
		$response = array(
			'result'    => 'success',
			'href'      => WC()->cart->get_cart_url()
		);

		header( 'Content-Type: application/json' );
		echo json_encode( $response );

		// Always exit when doing ajax
		exit();
	}
}

add_action( 'wp_ajax_spyr_coupon_redeem_handler', 'spyr_coupon_redeem_handler' );
add_action( 'wp_ajax_nopriv_spyr_coupon_redeem_handler', 'spyr_coupon_redeem_handler' );

add_action( 'init', 'testimonial' );
function testimonial() {
  register_post_type( 'testimonial',
	array(
	  'labels' => array(
		'name' => __( 'Testimonial' ),
		'singular_name' => __( 'Testimonial' )
	  ),
	  'public' => true,
	  'has_archive' => true,
	  'rewrite' => array('slug' => 'testimonial'),
	  'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ,'headway-seo'),
	)
  );
}

add_action( 'init', 'tailoringservices' );
function tailoringservices() {
  register_post_type( 'Tailoring Services',
	array(
	  'labels' => array(
		'name' => __( 'Tailoring Services' ),
		'singular_name' => __( 'Tailoring Services' )
	  ),
	  'public' => true,
	  'has_archive' => true,
	  'rewrite' => array('slug' => 'tailoringservices'),
	  'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ,'headway-seo'),
	)
  );
}


add_filter( 'woocommerce_product_tabs', 'woo_remove_tabs', 98 );
function woo_remove_tabs( $tabs ){
    if(is_product()){
      unset( $tabs['avalibility_map'] ); // Remove the avalibility_map tab
	  //unset($tabs['reviews']);
      }
  return $tabs;
 }
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
function my_custom_action(){
	global $product;
	$productname=$product->name;
	echo '<h1>'.$productname.'</h1>';
}
add_action( 'woocommerce_single_product_summary', 'my_custom_action', 1 );

/* add_filter( 'woocommerce_add_to_cart_fragments', 'iconic_cart_count_fragments', 10, 1 );

function iconic_cart_count_fragments( $fragments ) {
    
    $fragments['div.header-cart-count'] = '<div class="header-cart-count">' . WC()->cart->get_cart_contents_count() . '</div>';
    
    return $fragments;
    
} */

add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment',10,1);

function woocommerce_header_add_to_cart_fragment( $fragments ) 
{
    global $woocommerce;
    ob_start(); ?>

		<span> 
			<a class="header-cart-count" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
			<i class="fa fa-shopping-cart" aria-hidden="true"></i> 
				 <span class="cart_header">
			<?php //_e('SHOPPING CART', 'woocommerce'); ?>
		
			<p><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woocommerce'), $woocommerce->cart->cart_contents_count);?> : <span><?php echo $woocommerce->cart->get_cart_total(); ?></span></p></span>
		   </a>                       
			<!--p>0 Items : <span>$0.00</span></p-->
		</span>
    <?php
    $fragments['a.header-cart-count'] = ob_get_clean();
    return $fragments;
}

/** custom add to cart by ajax code start  */

add_action('wp_ajax_cusotm_add_to_cart', 'cusotm_add_to_cart_myajax');
add_action('wp_ajax_nopriv_cusotm_add_to_cart', 'cusotm_add_to_cart_myajax');

function cusotm_add_to_cart_myajax() {		
		global $product,$woocommerce;

    $product_id = $_POST['product_id'];
    // Avoid using the global $woocommerce object
		// WC()->cart->add_to_cart($product_id);
		WC()->cart->add_to_cart( intval($product_id), 1, 0, array(), array() );
		
		$product_data = wc_get_product( $product_id );
		echo $product_data->get_title();
    die();
}

/** custom add to cart by ajax code end  */