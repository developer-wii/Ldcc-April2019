<?php

// Button
add_shortcode('button', 'shortcode_button');
function shortcode_button($atts, $content = null) {
    extract(shortcode_atts(array(
        'colour' => '',
        'linkid' => '',
        'link' => '',
        'text' => '',
        'type' => ''
                    ), $atts));

    $out = '';
    if ($link != '') {
        $out .= '<a href="' . $link . '" class="button btn-' . $type . '">' . $text . '</a>';
    } else {
        $out .= '<a href="' . get_permalink($linkid) . '" class="button btn-' . $type . '">' . $text . '</a>';
    }

    return $out;
}

add_shortcode('show_top_level_categories', function($atts, $content = null)
{
    global $category_ID;
    extract(shortcode_atts(array(
        'numshow' => -1
    ), $atts));
            
    $taxonomy     = 'product_cat';
    $orderby      = 'name';  
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no  
    $title        = '';  
    $empty        = 0;

    $args = array(
      'taxonomy'     => $taxonomy,
      'orderby'      => $orderby,
      'show_count'   => $show_count,
      'pad_counts'   => $pad_counts,
      'hierarchical' => $hierarchical,
      'title_li'     => $title,
      'hide_empty'   => $empty
    );

    $out = '';
    
    $all_categories = get_categories($args);
    
    $out .= '<section class="section-full">';
    $out .= '<div class="row relative">';
    $out .= '<ul class="top-level-categories">';
        foreach ($all_categories as $cat) 
        {
            if($cat->category_parent == 0) 
            {
               $menuLinkClass = str_replace(" ", "-", $cat->name);
               $menuLinkClass = str_replace("(", "", $menuLinkClass);
               $menuLinkClass = str_replace(")", "", $menuLinkClass);
               $menuLinkClass = str_replace("&amp;", "", $menuLinkClass);
               $menuLinkClass = str_replace("&", "", $menuLinkClass);
               $menuLinkClass = str_replace("--", "-", $menuLinkClass);
               
               $productCategoryLink = get_term_link($cat);
               if($category_ID == $cat->term_id)
               {
                   $out .= '<li class="active-cat '.strtolower($menuLinkClass).'"><span>'.$cat->name.'</span>';  
               }
               else
               {
                   $out .= '<li class="'.strtolower($menuLinkClass).'"><span>'.$cat->name.'</span>';
               }
               
                $out .= '<a href="'.$productCategoryLink.'">'.$cat->name.'</a>'; 
               $out .= '</li>';
            }
        }
    $out .= '</ul>';
    
    /* RESPONSIVE 768 CAROUSEL MENU */
    
        $out .= '<div class="carousel-message">Slide for more options</div>';
        $out .= '<ul id="categories-menu-carousel" class="categories-carousel">';
        foreach ($all_categories as $catCarousel) 
        {
            if($catCarousel->category_parent == 0) 
            {
               $menuCarouselLinkClass = str_replace(" ", "-", $catCarousel->name);
               $menuCarouselLinkClass = str_replace("(", "", $menuCarouselLinkClass);
               $menuCarouselLinkClass = str_replace(")", "", $menuCarouselLinkClass);
               $menuCarouselLinkClass = str_replace("&amp;", "", $menuCarouselLinkClass);
               $menuCarouselLinkClass = str_replace("&", "", $menuCarouselLinkClass);
               $menuCarouselLinkClass = str_replace("--", "-", $menuCarouselLinkClass);
               
               $productCategoryLink = get_term_link($catCarousel);
               if($category_ID == $catCarousel->term_id)
               {
                   $out .= '<li class="active-cat '.strtolower($menuCarouselLinkClass).'"><span>'.$catCarousel->name.'</span>';  
               }
               else
               {
                   $out .= '<li class="'.strtolower($menuCarouselLinkClass).'"><span>'.$catCarousel->name.'</span>';
               }
               
                $out .= '<a href="'.$productCategoryLink.'">'.$catCarousel->name.'</a>'; 
               $out .= '</li>';
            }
        }
        $out .= '</ul>';
    
    $out .= '</div>';
    $out .= '</section>';

    return $out;
});

add_shortcode('grid', function($atts, $content = null)
{
    extract(shortcode_atts(array(
        'xs' => '12',
        'sm' => '12',
        'md' => '12',
        'lg' => '12',
        'offset' => '',
        'customclass' => ''
    ), $atts));
        
   $out = '';
   
   $out .= '<div class="row"><div class="small-'.$sm.' medium-'.$md.' large-'.$lg.' medium-offset-'.$offset.' columns '.$customclass.'">';
   $out .= do_shortcode($content);
   $out .= '</div></div>';

  return $out;    
});

add_shortcode('postcode_lookup_section', function($atts, $content = null)
{
    global $postResponse, $loadOrderScreen;
    extract(shortcode_atts(array(), $atts));
    
    $out = '';
    
    if($loadOrderScreen == true || isset($_SESSION['postcode']))
    {
    
        $postcodeLookUp = new postcodeLookUp($_SESSION['postcode']);
        if($postcodeLookUp->getPostcode() !== '')
        {
            $chosenPostcode = $postcodeLookUp->getPostcode();
        }
        
        $telPostcodeLookup = get_field('telephone_number', 'options');
        $telPostcodeLookupNoSpace = str_replace(" ", "", $telPostcodeLookup);
        
        if($postcodeLookUp->checkIfPostcodeDeliver($chosenPostcode) == true)
        {
            $out .= '<div class="row">';
                $out .= '<div class="small-12 medium-6 large-6 large-offset-3 medium-offset-3 columns postcode-success">';
                    $out .= '<h3>Your Post Code '.$chosenPostcode.' is covered! Fill in the below form to begin your order</h3>';
                    $out .= '<h1>Place Your Order</h1>';
                    
                    if(!empty($telPostcodeLookup))
                    {
                    $out .= '<p>Good news, we can collect/deliver to your area! You can now proceed to fill in the order form to place your order. If you have any questions, please call us on <a href="tel:'.$telPostcodeLookupNoSpace.'">'.$telPostcodeLookup.'</a>.</p>';
                    }
                    else
                    {
                        $out .= '<p>Good news, we can collect/deliver to your area! You can now proceed to fill in the order form to place your order. If you have any questions, please get in touch.</p>';
                    }
                $out .= '</div>';
            $out .= '</div>';

        }
        else if($postcodeLookUp->checkIfPostcodeDeliver($chosenPostcode) == false)
        {
            $postcodeLookUp->removeSessionPostcode();
            $out .= '<div class="row">';

                $out .= '<div class="small-12 medium-6 large-6 large-offset-1 columns postcode-failed">';
                    $out .= '<h2 class="uppercase">We\'re Sorry, This Area Isn\'t Covered</h2>';
                    $out .= '<p>Try another postcode for collection from another address, or call us to and we will be happy to assist you over the phone.</p>';
                    
                    $out .= '<a href="tel:02076025310" class="enquiry-telephone">+44 (0)207 602 5310</a>';
                    
                    $out .= '<a href="'.get_permalink(22).'" class="button">Make An Enquiry</a>';
                    
                $out .= '</div>';

               $out .= '<div class="small-12 medium-4 large-4 columns postcode-failed-form">';
               
                $out .= '<div class="row"><div class="small-12 medium-10 large-10 columns">';
                
                    $out .= '<h2 class="uppercase">free collection &amp; delivery</h2>';
                    
                    $out .= '<p>Are we in your area?</p>';
                
                    if(isset($postResponse) && !empty($postResponse)) {$out .= $postResponse;} 
                    $out .= '<form class="post-code-oo" name="postcode-check" action="'.get_permalink(10).'" method="post">';
                         $out .= '<input type="text" class="form-field" name="pcode" placeholder="Enter Your Postcode" />';
                         $out .= '<button type="submit" class="form-button" name="pc-check">Check</button>';
                    $out .= '</form>';
                $out .= '</div></div>';    
                    
               $out .= '</div>';

            $out .= '</div>';
        }
        else
        {
            
        }
    
    }
    else
    {

        $postcodeLookupIntro = get_field('postcode_lookup', 10);
        $postcodeLookupLabel = get_field('postcode_label', 10);

        $out .= '<h1>Order Online</h1>';
        
        $out .= '<div class="row">';
            $out .= '<div class="small-4 medium-4 large-4 columns intro-postcode-text">';
                   $out .= (!empty($postcodeLookupIntro)) ? $postcodeLookupIntro : '';
            $out .= '</div>';
            $out .= '<div class="small-1 medium-1 large-1 columns relative">';
                 $out .= '<div class="logo-icn"></div>';
            $out .= '</div>';
            $out .= '<div class="small-12 medium-5 large-5 columns post-code-form">';
                $out .= '<div class="row">';
                $out .= '<div class="small-12 medium-4 large-4 columns postcode-label">';
                 $out .= (!empty($postcodeLookupLabel)) ? '<p>'.$postcodeLookupLabel.'</p>': '<p>Are We In Your Area?</p>' ;
                 $out .= '</div>';
                $out .= '<div class="small-12 medium-8 large-8 columns form-cont-order-online">';
                    if(isset($postResponse) && !empty($postResponse)) {$out .= $postResponse;} 
                $out .= '<form class="post-code-oo" name="postcode-check" action="'.get_permalink(10).'" method="post">';
                     $out .= '<input type="text" class="form-field" name="pcode" placeholder="Enter Your Postcode" />';
                     $out .= '<button type="submit" class="form-button" name="pc-check">Check</button>';
                $out .= '</form>';

                 $out .= '</div>';
               $out .= '</div>';
            $out .= '</div>';
        $out .= '</div>';
    }
    
    return $out;
});

add_shortcode('inyourarea_sub_banner', function($atts, $content = null)
{
    global $postResponse;
    extract(shortcode_atts(array(), $atts));
    $out ='';
    
    $formTitle = get_field('form_title');
    $formLabel = get_field('form_label');
    
    $out .= '<div class="row subpc-form">';
        $out .= '<div class="small-12 medium-5 large-5 columns">';
            $out .= ((!empty($formTitle))) ? '<h3>'.$formTitle.'</h3>' : '<h3>free collection &amp; delivery</h3>';
        $out .= '</div>';
        $out .= '<div class="small-12 medium-7 large-7 columns pc-form-cont">';
            $out .= '<form class="subbanner-postcode" name="postcode-check" action="'.get_permalink(10).'" method="post">';
                 $out .= ((!empty($formLabel))) ? '<label>'.$formLabel.'</label>' : '<label>Are We In Your Area?</label>';
                 $out .= '<input type="text" class="form-field" name="pcode" placeholder="Enter Your Postcode" />';
                 $out .= '<button type="submit" class="form-button" name="pc-check">Check</button>';
            $out .= '</form>';
        $out .= '</div>';
    $out .= '</div>';
    
   
   return $out; 
});

add_shortcode('get_latest_testimonials', function($atts, $content = null)
{
    global $postResponse;
    extract(shortcode_atts(array(), $atts));
    
    ob_start();
    
    $args = array(
        'posts_per_page' => 5,
        'post_type' => 'testimonials',
        'orderby' => 'date', 
        'order' => 'DESC',
    );
    $recentTestimonials = new WP_Query( $args );  
    
     echo '<div class="recent-testimonials">';        
        $recentTestimonialsCounter = 1;
        
        while ( $recentTestimonials->have_posts() )
        {
            echo '<div class="row testimonial-row">';
            
            (($recentTestimonialsCounter%2 == 0)) ? $leftOrRightTop = 'left' : $leftOrRightTop = 'right';
            
            $getAuthorRole = get_field('author_role', get_the_ID());
            ?>
            <div class="small-12 medium-2 large-2 <?php echo $leftOrRightTop; ?> testimonials-images">
                <div class="testimonial-icon"></div>
            <?php
            $recentTestimonials->the_post();
            if(has_post_thumbnail())
            {
                //echo '<a href="'.get_the_permalink().'">';
                $getFeatImagePost = wp_get_attachment_image(the_post_thumbnail(get_the_ID()), 'recent-testimonials');
                echo $getFeatImagePost;
                //echo '</a>';
            }
            ?>
            </div>
            <?php (($recentTestimonialsCounter%2 == 0)) ? $leftOrRightBot = 'right' : $leftOrRightBot = 'left'; ?>
            <div class="small-12 medium-10 large-10 <?php echo $leftOrRightBot; ?>">
                
                <div class="testimonials-content">
                 <?php the_content(); ?>  
                </div>
                <p class="testimonials-author">
                    <?php the_title(); 
                    if(!empty($getAuthorRole))
                    {
                        echo ',<br />'.$getAuthorRole.'';
                    }
                    ?>
                </p>
            </div>
            <?php
            echo '</div>';
            $recentTestimonialsCounter++;
        }
        
        echo '</div>';
        
    wp_reset_query();
    
    return ob_get_clean();
});

add_shortcode('footer_check_your_area', function($atts, $content = null)
{
    global $postResponse;
    extract(shortcode_atts(array(), $atts));
    $out ='';
    
    
    
    return $out;
});

add_shortcode('front_page_random_testimonial', function($atts, $content = null)
{
    extract(shortcode_atts(array(), $atts));
    
    $getRandomTestimonial = getRandomTestimonial();
    if(count($getRandomTestimonial))
    {
        echo '<div class="random-testimonial">';
            echo '<div class="testimonial-icon"></div>';
                echo $getRandomTestimonial['rt_content'];
            echo '<p class="random-author">'.$getRandomTestimonial['rt_title'].',<br />'.$getRandomTestimonial['rt_author_role'].'</p>';
        echo '</div>';
    }
});

add_shortcode('front_page_inyourarea', function($atts, $content = null)
{
    extract(shortcode_atts(array(), $atts));
    ?>
    <h3 class="uppercase">Free Collection<br />&amp; Delivery</h3>
    <p class="formlabel">Are We In Your Area?</p>
    <form class="page-bottom-post-code" name="postcode-check" action="<?php echo get_permalink(10); ?>" method="post">
        <input type="text" class="form-field" name="pcode" placeholder="Enter Your Postcode" />
        <button type="submit" class="form-button" name="pc-check">Check</button>
        <div class="clearfix"></div>
    </form>
    
    <div class="enquiry-page-bottom">
        <?php
        $enquiryIntro = get_field('enquiry_intro');
        $enquiryContactNumber = get_field('enquiry_contact_number');
        $enquiryContactSubject = get_field('enquiry_contact_subject');
        $enquiryButtonText = get_field('enquiry_button_text');
        $enquiryEmailAddress = get_field('enquiry_email_address');
        
        if(!empty($enquiryIntro)) { echo $enquiryIntro; }
        
        $newEnquiryContactNumber = str_replace(" ", "", $enquiryContactNumber);
        if(!empty($enquiryContactNumber))
        {
            ?>
            <div class="telephone-button">
                <a href="tel:<?php echo $newEnquiryContactNumber; ?>"><?php echo $enquiryContactNumber; ?></a>
            </div>
            <?php
        }
        ?>
        
        
        <?php
        if(!empty($enquiryEmailAddress))
        {
            echo '<a class="button" href="mailto:'.$enquiryEmailAddress.'?subject='.$enquiryContactSubject.'">';
            
            if(!empty($enquiryButtonText))
            {
                echo $enquiryButtonText;
            }
            else
            {
                echo 'Make An Enquiry';
            }
            
            echo '</a>';
        }
        ?>
        
    </div>
    
    <?php
});

add_shortcode('sub_page_bottom_shortcode', function($atts, $content = null)
{
    extract(shortcode_atts(array(), $atts));
    ?>
<section id="padge-bottom-sc" class="padge-bottom-sc">
    
    <div class="row">
        
    <div class="small-12 medium-6 large-6 columns left page-bottom-seperator">

        <?php
        $makeEnquiryTitle = get_field('make_enquiry_title', 'options');
        $makeEnquiryContent = get_field('make_enquiry_content', 'options');
        $makeEnquiryEmailSubject = get_field('make_enquiry_email_subject', 'options');
        
        if(!empty($makeEnquiryTitle))
        {
            echo '<h3>'.$makeEnquiryTitle.'</h3>';
        }
        if(!empty($makeEnquiryContent))
        {
            echo $makeEnquiryContent;
        }
        
        // Get telephone
        $telephoneNumber = get_field('telephone_number', 'options');
        if(!empty($telephoneNumber))
        {
            $newTelephoneNumber = str_replace(" ", "", $telephoneNumber);
            echo '<div class="telephone-button"><a href="tel:'.$newTelephoneNumber.'">'.$telephoneNumber.'</a></div>';
        }
        
        $emailAddress = get_field('email_address', 'options');
        if(!empty($emailAddress))
        {
            echo '<a href="mailto:'.$emailAddress.'?subject='.$makeEnquiryEmailSubject.'" class="button">Make An Enquiry</a>';
        }
        ?>
    </div>    
        
    <div class="small-12 medium-4 large-4 columns medium-offset-1 left">
            
        <?php 
        $inYourAreaTitle = get_field('in_your_area_title', 'options');
        if(!empty($inYourAreaTitle))
        {
            echo '<h3 class="uppercase">'.$inYourAreaTitle.'</h3>';
        }
        else
        {
            echo '<h3 class="uppercase">Free Collection<br />&amp; Delivery</h3>';
        }
        ?>
        <p class="formlabel">Are We In Your Area?</p>
        <form class="page-bottom-post-code" name="postcode-check" action="<?php echo get_permalink(10); ?>" method="post">
            <input type="text" class="form-field" name="pcode" placeholder="Enter Your Postcode" />
            <button type="submit" class="form-button" name="pc-check">Check</button>
            <div class="clearfix"></div>
        </form>
    
    </div>
    </div>
</section>
    <?php
});

add_shortcode('show_services_blocks', function($atts, $content = null)
{
   extract(shortcode_atts(array(), $atts));
   $out = '';
   
    //Get all services
   if(has_sub_field('services_blocks', 12))
   {
    $theServicesSc = get_field('services_blocks', 12);

        if(count($theServicesSc))
        {
            //End the sourrounded divs
            $out .= '</div></div></div>';
            
            
            $out .= '<section id="our-services" class="our-services">';
             $out .= '<div class="row" data-equalizer>';

             $servCounter = 1;
             foreach($theServicesSc as $serv)
             {
                 (($servCounter%4 == 0)) ? $classServ = 'last-services' : $classServ = '' ;
                 $out .= '<div class="small-12 medium-4 large-4 columns left our-services-item '.$classServ.'" data-equalizer-watch>';
                 
                 $servBannerImage = wp_get_attachment_image($serv['banner_image'], 'services-item');
                 $out .= $servBannerImage;
                 $out .= '<h3>'.$serv['services_title'].'</h3>';
                 $out .= $serv['services_content'];
                 
                 $out .= '</div>';
                 $servCounter++;
             }

             $out .= '</div>';
            $out .= '</section>';


            //Recreate the sourrounding divs for structure
             //$out .= '<div class="row"><div class="small-12 medium-12 large-12 normal-page-content relative"><div class="row">';
       }
   }
   
   return $out;
});


add_shortcode('google_map', function($atts, $content = null)
{
   extract(shortcode_atts(array(), $atts));
   ob_start();
   
   $getMapsAddress = get_field('google_map_address');
   $getMapsLat = get_field('google_map_lat');
   $getMapsLng = get_field('google_map_lng');
   $google_map_pin_point_image = get_field('google_map_pin_point_image');
    ?>
     <div id="google-map">
        <div class="google-map" id="map" style="width:100%;height:487px;">
        <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
        <script type="text/javascript">
        var locations = [
         ["<?php echo $getMapsAddress; ?>", <?php echo $getMapsLat; ?>, <?php echo $getMapsLng; ?>],
        ];

        var map = new google.maps.Map(document.getElementById('map'), {
         zoom: 15,
         center: new google.maps.LatLng(<?php echo $getMapsLat; ?>, <?php echo $getMapsLng; ?>),
         mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var marker, i;

        <?php 
        if(!empty($google_map_pin_point_image))
        {
            ?>
            var image = new google.maps.MarkerImage('<?php echo $google_map_pin_point_image; ?>',
                   new google.maps.Size(79, 56),
                   new google.maps.Point(0,0),
                   new google.maps.Point(29, 52)
            );
            <?php
        }
        ?>

         for (i = 0; i < locations.length; i++) { 
           marker = new google.maps.Marker({
                 position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                 map: map,
                 icon: image,
                 animation: google.maps.Animation.DROP
           });
         }
        </script>

        </div>
    </div>
    <?php
    return ob_get_clean();
});

?>