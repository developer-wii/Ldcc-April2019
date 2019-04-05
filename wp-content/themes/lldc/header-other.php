<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package LLDC
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/responsive.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/font-awesome/css/font-awesome.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap-theme.css" type="text/css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jQuery.scrollSpeed.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
	<script>
	//paste this code under head tag or in a seperate js file.
	// Wait for window load
	
	jQuery(window).load(function() {
		// Animate loader off screen
		jQuery(".se-pre-con").fadeOut("slow");;
	});
</script>
	<?php wp_head(); ?>
</head>
<script>
	$(function() {  

	jQuery.scrollSpeed(100, 800);

	});
</script>

<body <?php body_class(); ?>>
<div class="se-pre-con"></div>
<div id="page " class="site wrapper">
	<div class="top_header clearfix">
	<?php if(!is_user_logged_in()){ ?>
	<div class="header_login">
		<a href="<?php echo get_site_url() ?>/login">Login</a> | <a href="<?php echo get_site_url() ?>/user-registration">Create account</a>
	</div>
	<?php } ?>
			<div class="time_date">
				<?php
				global $woocommerce;
				if ( is_active_sidebar( 'sidebar-2' ) ) : 
				 dynamic_sidebar( 'sidebar-2' ); 
				 endif;	
				 ?>
				 <?php $countcart = WC()->cart->get_cart_contents_count(); ?>
				 <div class="countdynamic">
			<?php if($countcart==0){ ?><a class="header-cart-count" href="<?php echo get_site_url(); ?>/order"> <?php } else {?>
			<a class="header-cart-count" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
			<?php } ?>
			<i class="fa fa-shopping-cart" aria-hidden="true"></i>
			<span class="cart_header">
			<?php// _e('SHOPPING CART', 'woocommerce'); ?>
			<p><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woocommerce'), $woocommerce->cart->cart_contents_count);?> : <span><?php echo $woocommerce->cart->get_cart_total(); ?></span></p>
	</span>
</a>

				</div>
			</div>
		</div>
<header class="header">
				<nav class="navbar navbar-default clearfix">
					<div class="navbar-header">
						<div class="logo clearfix"><a class="navbar-brand" href="<?php echo get_site_url();?>"><?php echo get_custom_logo();?></a></div>
					</div>
					<div class="header-right order_cnfrmtion_hrd">
						<div class="new-account">
							<span><a href="<?php echo get_site_url(); ?>/user-registration">Add a new client account</a></span>
							<span><a href="<?php echo get_site_url(); ?>/contractor-registration">Add a new contracor account</a></span>
						</div>
						<?php
						$current_user = wp_get_current_user();
						?>
						<?php if(is_user_logged_in()){ ?>
						 <div class="dropdown user_profile">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="admin_hover">
							<?php echo $current_user->user_login; ?>
							<span class="glyphicon glyphicon-menu-down"></span><div id="usr_logout"><a href="<?php echo wp_logout_url();  ?>">Logout</a></div></button>
							
							<!--ul class="dropdown-menu">
							  <li><a href="#">HTML</a></li>
							  <li><a href="#">CSS</a></li>
							  <li><a href="#">JavaScript</a></li>
							</ul-->
						</div>
						<?php } ?>
					</div>
				</nav>
		</header>

	<div id="content" class="site-content">
  