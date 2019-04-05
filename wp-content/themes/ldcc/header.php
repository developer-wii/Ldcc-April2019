<?php
global $basketLoad, $validationResponse;
$theCurrPostId = $wp_query->post->ID;
$basketPages = array();
?>
<!doctype html>
<html class="no-js" lang="en">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php wp_title( '|', true, 'right' ); ?></title>
<!--[if lt IE 9]>
<script src="<?php bloginfo('template_directory'); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/app.css" />
<script src="<?php bloginfo('template_directory'); ?>/bower_components/foundation/js/vendor/modernizr.js" type="text/javascript"></script>
<link href="<?php bloginfo('template_directory'); ?>/animate.css" rel="stylesheet" />  
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/fonts/fontawesome/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.png" type="image/x-icon" />  

<?php
  wp_head();
?>

<script src="//use.typekit.net/uzs0vej.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74786966-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body <?php if(!is_front_page()) {body_class('not-front');} else {body_class();} ?>>

<?php
$logoId = get_field('logo','options');
$logoImage = wp_get_attachment_image_src($logoId, 'logo');
?>
    
    <div class="canvas-panel overlayp">
        <a href="<?php echo bloginfo('url'); ?>"><img src="<?php echo $logoImage[0]; ?>" class="canvas-logo" alt="London Dry Cleaning Company" /></a>
        <div class="close"><i class="fa fa-times"></i></div>
        <?php wp_nav_menu( array('menu' => 'Canvas Menu' )); ?>
        
        <?php
            $canvasTelephone = get_field('telephone_number', 'options');
            $canvasEmail = get_field('email_address', 'options');
        ?>
        
        <div class="canvas-block">
            <h5>Contact us by telephone</h5>
            <?php echo ((!empty($canvasTelephone))) ? '<p>'.$canvasTelephone.'</p>' : ''; ?>
        </div>
        <div class="canvas-block">
            <h5>Contact us by email</h5>
            <?php echo ((!empty($canvasEmail))) ? '<p>'.$canvasEmail.'</p>' : ''; ?>
        </div>
        
    </div> 
    
    <div class="wrapper relative">
        
    <header id="header" role="header" class="header relative">
        <div class="row">
            <div class="left">

                <a href="<?php echo bloginfo('url'); ?>"><img src="<?php echo $logoImage[0]; ?>" class="logo" alt="London Dry Cleaning Company" /></a>
            </div>
            <div class="right">
                <div class="get-in-touch show-for-medium-up">
                    <p class="evropa uppercase">Collection &amp; Delivery</p> 
                    <h3 class="uppercase purplefont">Get in touch</h3>
                    <div class="clearfix"></div>
                    
                    <div class="contacts-hidden">
                        <p class="evropa uppercase">Collection &amp; Delivery</p> 
                        <h3 class="uppercase purplefont">Get in touch</h3>
                        <div class="mouseenter-contacts">
                            <div class="contact-info-top">
                                
        <?php
            $fadeInTelephone = get_field('telephone_number', 'options');
            $fadeInEmail = get_field('email_address', 'options');
            
            $fadeInTelephoneNoSpace = str_replace(" ", "", $fadeInTelephone);
            
        ?>
                                
                                <h5 class="uppercase">Contact us by telephone</h5>
                                <h4><a href="tel:<?php echo ((!empty($fadeInTelephoneNoSpace))) ? $fadeInTelephoneNoSpace : ''; ?>"><?php echo ((!empty($fadeInTelephone))) ? $fadeInTelephone : ''; ?></a></h4>
                                <h5 class="uppercase">Contact us by email</h5>
                                <h4><a href="mailto:<?php echo ((!empty($fadeInEmail))) ? $fadeInEmail : ''; ?>?subject=Enquiry from LDCC website"><?php echo ((!empty($fadeInEmail))) ? $fadeInEmail : ''; ?></a></h4>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="canvas-menu-overlay relative">
                <div class="menu-button overlay">
                    <span class="menu-bars"><i class="fa fa-bars"></i></span>
                </div>
            </div>
            
        </div>
    </header>
    <nav role="navigation" id="navigation" class="navigation">
        <div class="row">
        <?php wp_nav_menu( array('menu' => 'Main Menu' )); ?>
        </div>
    </nav>
    <main id="body" class="container">
        
        
        
        