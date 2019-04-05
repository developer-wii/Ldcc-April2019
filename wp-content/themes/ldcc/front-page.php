<?php
  get_header();
?>

<?php
$displayAsSlider = get_field('display_slider_images');
if($displayAsSlider == true)
{
    
    if(has_sub_field('main_banner'))
    {
        $mainBanner = get_field('main_banner');
        if(count($mainBanner) > 0)
        {
            echo '<section id="banner" class="main-banner">';
            echo '<ul class="example-orbit" data-orbit data-options="animation:slide;pause_on_hover:true;animation_speed:500;navigation_arrows:true;bullets:false;">';
            foreach((array)$mainBanner as $banner)
            {
                echo '<li>';
                $theBannerBanner = wp_get_attachment_image($banner['banner_image'], 'main-banner');
                echo ((!empty($banner['banner_location']))) ? '<a href="'.$banner['banner_location'].'">' : '';
                    echo $theBannerBanner;
                echo ((!empty($banner['banner_location']))) ? '</a>' : '';
                echo '</li>';
            }
            echo '</ul>';
            echo '</section>';
        }
    }
    
}
else
{
    if(has_sub_field('main_banner'))
    {
        $mainBannerStatic = get_field('main_banner');
        if(count($mainBannerStatic) > 0 && count($mainBannerStatic) < 2 )
        {
            echo '<section id="banner" class="static-banner">';
            
            foreach((array)$mainBannerStatic as $banner)
            {
                $theBannerBanner = wp_get_attachment_image_src($banner['banner_image'], 'main-banner');
              
                echo '<div class="static-main-banner" style="background-image:url('.$theBannerBanner[0].');">';
                    echo '<div class="small-12 medium-7 large-7 static-banner-contents right">';
                        echo $banner['display_banner_content'];
                    echo '</div>';    
                echo '</div>';
 
            }
            echo '</section>';
        }
    }
}
?>

<?php
$subBannerContent = get_field('in_your_area_content');
if(!empty($subBannerContent))
{
     $subBannerImage = get_field('background_image');
     $getBannerSubImg = wp_get_attachment_image_src($subBannerImage, 'in-your-area');

     if(empty($getBannerSubImg[0]))
     {
     ?>
    <section id="sub-banner" class="sub-banner" style="background-image:url(<?php echo bloginfo('template_url'); ?>/images/sub-banner-new.jpg);">
        <?php
     }
     else
     {
      ?>
      <section id="sub-banner" class="sub-banner" style="background-image:url(<?php echo $getBannerSubImg[0]; ?>);">
     <?php
     }
     ?>
          <div class="row">
          <div class="small-12 medium-5 large-5 medium-offset-1 columns sub-banner-contents">
              <?php echo $subBannerContent; ?>
          </div>  
          </div>
    </section>
    <?php
}
?>
        
<?php
$getOurServicesList = get_field('our_services_list');
if(count($getOurServicesList))
{
    $servicesTitle = get_field('services_title');
?>
<section id="discover-services" class="discover-our-services">
    <div class="row">
        <?php echo ((!empty($servicesTitle))) ? '<h2>'.$servicesTitle.'</h2>' : '<h2>Discover Our Services</h2>'; ?>
    <ul class="small-12 medium-12 large-12" data-equalizer>
        <?php
        $countServices = 1;
        foreach((array)$getOurServicesList as $service)
        {
            (($countServices%3 == 0)) ? $end = true : $end = false ; 
            if($end == true) 
            {
                echo '<li class="last small-12 medium-3 large-3" data-equalizer-watch>';
            }
            else
            {
                echo '<li class=" small-12 medium-3 large-3" data-equalizer-watch>';
            }
            
            $getFeatImagePost = get_the_post_thumbnail($service->ID, 'services-images');
            $getPostTitleWithSpan = get_field('homepage_post_title', $service->ID);
            ?>
             <h3><?php 
             if(!empty($getPostTitleWithSpan))
             {
                 echo '<a href="'.get_permalink($service->ID).'">'.$getPostTitleWithSpan.'</a>';
             }
             else 
             {
                 echo '<a href="'.get_permalink($service->ID).'">'.$service->post_title.'</a>';
             }
             ?></h3>
             <?php
             if(!empty($getFeatImagePost))
             {
                 echo $getFeatImagePost;
             }
             
             echo '<p>'.limit_words($service->post_content, 45).'...</p>';
             
             ?>
        
                <a href="<?php echo get_permalink($service->ID); ?>" class="button find-out-more">Find Out More</a>
            </li>
            <?php
            $countServices++;
        }
        ?>
    </ul>
    </div>
</section>
<?php
}
?>

<?php
$whyChooseTitle = get_field('why_choose_ldcc_title');
$whyChooseUsPoints = get_field('why_choose_points');
if(count($whyChooseUsPoints) > 0)
{
?>
    <section id="why-choose-ldcc" class="why-choose-us">
        <?php echo ((!empty($whyChooseTitle))) ? '<h2>'.$whyChooseTitle.'</h2>' : '<h2>Why choose the london dry cleaning company?</h2>'; ?>
        <div class="std-grey-background">
        <div class="row">
            <?php
            foreach($whyChooseUsPoints as $choosePoints)
            {
                echo '<div class="relative small-12 medium-4 large-4 columns left why-choose-points">';
                    echo '<div class="choose-us-heading">';
                        echo '<div class="logo-icons"></div>';
                        echo '<h4>'.$choosePoints['why_choose_title'].'</h4>';
                        echo $choosePoints['why_choose_content'];
                    echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    </section>
<?php
}      
?>

<?php
$fourStepsTitle = get_field('our_four_steps_title');
$fourStepsBanner = get_field('four_steps_banner_image');
$theFourSteps= get_field('four_steps');
if(count($theFourSteps) > 0)
{
?>
<section id="our-four-steps" class="our-four-steps">
    <?php ((!empty($fourStepsTitle))) ? '<h2>'.$fourStepsTitle.'</h2>' : '<h2>4 steps to exquisitely clean clothes</h2>'; ?>
    <div class="std-grey-background">
     
        <div class="left four-steps-image">
            <?php
            if(!empty($fourStepsBanner))
            {
                $fourStepsBanner = wp_get_attachment_image($fourStepsBanner, 'four-steps-banner');
                echo $fourStepsBanner;
            }
            ?>
        </div>
        <div class="small-12 medium-6 large-6 columns left four-steps">
            <?php
                echo '<ul class="row">';
                $countPoints = 1;
                    foreach($theFourSteps as $steps)
                    {
                       ($countPoints% 3 == 0) ? $classClear = 'clearpoint' : $classClear = '';
                        
                        $theStepIcon = wp_get_attachment_image($steps['four_steps_icon'], 'icon-four-steps');
                        echo '<li class="'.$classClear.' small-12 medium-4 large-4 columns left">';
                            if(!empty($theStepIcon)) {echo $theStepIcon;}
                            echo '<h4>'.$steps['four_steps_title'].'</h4>';
                            echo '<p>'.$steps['four_steps_content'].'</p>';
                        echo '</li>';
                        
                        $countPoints++;
                    }
                echo '</li>';
            ?>
        </div>

    </div>
</section>
<?php
}
?>

<section id="page-bottom" class="page-bottom">
    <div class="row"> 
        <div class="small-12 medium-6 large-6 columns left page-bottom-seperator">
            <?php do_shortcode('[front_page_random_testimonial]'); ?>
        </div>
        <div class="in-your-area-form-footer small-12 medium-5 large-5 columns left medium-offset-1">
             <?php do_shortcode('[front_page_inyourarea]'); ?>
        </div>
    </div>
</section>
        
        
<?php
  get_footer();
?>