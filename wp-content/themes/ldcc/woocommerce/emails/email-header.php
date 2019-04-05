<?php
/**
 * Email Header
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            	<tr>
                	<td align="center" valign="top">
						<div id="template_header_image">
	                		<?php
	                			if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
	                				echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
	                			}
	                		?>
						</div>
                    	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container">
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Header -->
                         
<table cellspacing="0" cellpadding="0" style="width:100%;border-bottom:none;" border="0">
    <thead>
        <tr>
            <td style="padding:20px 0 20px 20px;">
                <?php 
                $emailLogo = get_field('logo', 'options'); 
                $logoImage = wp_get_attachment_image_src($emailLogo, 'logo');
                ?>
                <a href="<?php echo get_bloginfo('url'); ?>">
                    <img src="<?php echo $logoImage[0]; ?>" alt="The London Dry Cleaning Company" id="email-header" />
                </a>
            </td>
            <td id="order-conf">
                <h3>Order Confirmation</h3>
                <a href="mailto:orders@londondrycleaningcompany.com">orders@londondrycleaningcompany.com</a><br />
                0208 774 7569
            </td>
        </tr>
        </thead>
</table>
                                    
                                    
                                    <!-- End Header -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                    	<tr>
                                            <td valign="top" id="body_content">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div id="body_content_inner">
