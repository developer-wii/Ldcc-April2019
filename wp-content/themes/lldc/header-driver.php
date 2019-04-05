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

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Driver Current Delivery Panel</title>
	<!--link rel="stylesheet" href="<?php //echo esc_url( get_template_directory_uri() ); ?>/css/style_custom_html.css" type="text/css" /-->
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/style.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/custom.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/responsive.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/font-awesome/css/font-awesome.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap-theme.css" type="text/css" />
	
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>

    <script>
		// paste this code under head tag or in a seperate js file.
		// Wait for window load
// 	jQuery(window).load(function() {
// 		// Animate loader off screen
// 		jQuery(".se-pre-con").fadeOut("slow");;
// 	});	
</script>
	<?php wp_head(); ?>
</head>
<script>
// 	$(function() {  

// 	jQuery.scrollSpeed(100, 800);

// 	});
</script>    
<body >
	<div class="se-pre-con"></div>
	<div class="wrapper header_wrapper">
		<header class="header new_header delivery_header">
			<nav class="navbar navbar-default clearfix delivery-default">
				<div class="navbar-header nav_header_left col-md-3 col-sm-3 col-xs-12">
                    <!--<div class="logo clearfix"><a class="navbar-brand" href="http://112.196.54.36/LDCC/"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/logo_new.png"/></a></div> -->
                    <div class="logo clearfix"><a class="navbar-brand" href="<?php echo get_site_url();?>"><?php echo get_custom_logo();?></a></div>


					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
							</button>
				</div>
				<div class="header-right delivery_head_right col-md-9 col-sm-9">
					<div class="collapse navbar-collapse desk-nav" id="bs-example-navbar-collapse-1">
						<div class="row">
							<div class="col-sm-12 col-xs-12 main_nav">
								<div class="col-md-9 col-sm-9 nav1">
									<div class="nav_collapse">
									    	<?php 
            								    wp_nav_menu( array( 'container'=> false, 'menu' => 'Driver Menu', 'theme_location' => 'menu-2', 'menu_class' => 'nav navbar-nav delivery_nav', ) );
            								   
            								?>
									</div>
								</div>
								<div class="col-md-3 col-sm-3 nav2">
									<div class="nav_collapse">
										<ul class="nav navbar-nav delivery_nav_right">
											<li><a href="tel:0207 794 9096" class="phone">Call Shop</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="header-right delivery_head_right call_shop col-md-2 col-sm-2">
							<ul class="nav navbar-nav delivery_nav_right">
								<li><a href="#" class="phone">Call Shop</a></li>
							</ul>
					  </div>    -->
			</nav>
		</header>

    <div id="content" class="site-content">
