<?php
/**
 * Email Styles
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg              = get_option( 'woocommerce_email_background_color' );
$body            = get_option( 'woocommerce_email_body_background_color' );
$base            = get_option( 'woocommerce_email_base_color' );
$base_text       = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text            = get_option( 'woocommerce_email_text_color' );

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
?>
#wrapper {
    background-color: <?php echo esc_attr( $bg ); ?>;
    margin: 0;
    padding: 70px 0 70px 0;
    -webkit-text-size-adjust: none !important;
    width: 100%;
}

#template_container {
    box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important;
    background-color: <?php echo esc_attr( $body ); ?>;
    border: 1px solid <?php echo esc_attr( $bg_darker_10 ); ?>;
    border-radius: 3px !important;
}

#template_header {
    background-color: <?php echo esc_attr( $base ); ?>;
    border-radius: 3px 3px 0 0 !important;
    color: <?php echo esc_attr( $base_text ); ?>;
    border-bottom: 0;
    font-weight: bold;
    line-height: 100%;
    vertical-align: middle;
    font-family: Calibri, Verdana, "Times New Roman", sans-serif;
}

#email-header {
    width:80% !important;
}
#order-conf {
    padding:10px 20px 20px 0;
}
#order-conf, #order-conf h3 {
    text-align:right !important;
}

#template_header h1 {
    color: #6c34a4;
}

#template_footer td {
    padding: 0;
    -webkit-border-radius: 6px;
}

#template_footer #credit {
    border:0;
    color: <?php echo esc_attr( $base_lighter_40 ); ?>;
    font-family: Arial;
    font-size:12px;
    line-height:125%;
    text-align:center;
    padding: 0 48px 48px 48px;
}

#body_content {
    background-color: <?php echo esc_attr( $body ); ?>;
}

#body_content table td {
    padding: 20px;
}

#body_content table td td {
    padding: 12px;
}

#body_content table td th {
    padding: 12px;
}

#body_content p {
    margin: 0px;
    color: #1c1c1c !important;
}

#body_content_inner {
    color: <?php echo esc_attr( $text_lighter_20 ); ?>;
    font-family: Calibri, Verdana, "Times New Roman", sans-serif;
    font-size: 14px;
    line-height: 150%;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h1 {
    color: #6c34a4;
    display: block;
    font-family: Calibri, Verdana, "Times New Roman", sans-serif;
    font-size: 28px;
    font-weight: 300;
    line-height: 150%;
    margin: 0;
    padding: 36px 48px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
    -webkit-font-smoothing: antialiased;
}

h2 {
    color: #6c34a4;
    display: block;
    font-family: Calibri, Verdana, "Times New Roman", sans-serif;
    font-size: 22px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
    color: #6c34a4;
    display: block;
    font-family: Calibri, Verdana, "Times New Roman", sans-serif;
    font-size: 18px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

strong {
    color: #6c34a4;
    font-family: Calibri, Verdana, "Times New Roman", sans-serif;
    font-weight: bold;
    line-height: 130%;
}

a {
    color: #1c1c1c;
    font-weight: normal;
    text-decoration: none;
    font-family: Calibri, Verdana, "Times New Roman", sans-serif;
}

img {
    border: none;
    display: inline;
    font-size: 14px;
    font-weight: bold;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
}

#email-footer-section {
    text-align:center;
    border-bottom:1px solid #dddddd;
}
#email-footer-section a {
    font-weight:bold;
    color:#6c34a4 !important;
}

#share-social {
    text-align:center;  
}
#share-social h3 {
    width:100%;text-align:center !important;
    padding-bottom:10px;
    border-bottom:1px solid #dddddd;
}
#share-social img {
    margin:5px 10px 25px 10px;
}


<?php
