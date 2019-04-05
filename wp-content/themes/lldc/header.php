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
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/font-awesome/css/font-awesome.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap-theme.css" type="text/css" />
	<!--link rel="stylesheet" href="<?php //echo esc_url( get_template_directory_uri() ); ?>/css/style_custom_html.css" type="text/css" /-->
	
	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
	<!--<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/bootstrap.js"></script>-->
	<!--<script type="text/javascript" src="<?php// echo esc_url( get_template_directory_uri() ); ?>/js/jQuery.scrollSpeed.js"></script>-->
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
						
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
					    </button>
					</div>
					<div class="header-right">
						<div class="right-top clearfix">
							<p class="phone">
								<?php
								$phone = get_option('theme_mods_lldc');
								$phonenum=$phone['footer_text_block'];
								echo '<a href="tel:'.$phonenum.'">'.$phonenum.'<a>';
								?>	
							</p>
							<div class="top-links">
								<?php 
								wp_nav_menu( array(  'container'=> false, 'menu' => 'main_menu', 'theme_location' => 'main_menu', 'menu_class' => 'clearfix', ) );
								?>

							</div>
						</div>
						<div class="collapse navbar-collapse desk-nav pull-right" id="bs-example-navbar-collapse-1">
							 
								<!--li class="active"><a href="http://112.196.54.36/LDCC/"><figure><img src="<?php //echo esc_url( get_template_directory_uri() ); ?>/images/home-icon.png" /></figure></a><p class="phone">(+44) 00208 577 271</p></li-->
								<?php 
								wp_nav_menu( array( 'menu' => 'main_menu-1', 'theme_location' => 'main_menu', 'menu_class' => 'nav navbar-nav', ) );
								?>
							 
							 <div class="top-links">
								<?php 
								wp_nav_menu( array( 'menu' => 'main_menu', 'theme_location' => 'main_menu', 'menu_class' => 'clearfix', ) );
								?>
							</div>
							<!--p class="question"><a href="#">Have a question?</a></p-->
						</div>
					</div>
				</nav>
		</header>

	<div id="content" class="site-content">
  