<?php
/**
 * Template Name: About LDCC Template
 */
  get_header();
  $hasSideBar = false;
  if(is_active_sidebar('general'))
  {
      $hasSideBar = true;
  }
  
  $showTitleIcon = get_field('show_title_icon');
?>

<?php 
$pageBannerImage = get_field('page_banner_image');
if(!empty($pageBannerImage))
{
    $thePageBannerImage = wp_get_attachment_image($pageBannerImage, 'page-banner');
?>
<section id="page-banner" class="page-banner">
    <?php echo $thePageBannerImage; ?>
</section>
<?php
}
?>



<section role="content" id="content" class="content">
<div class="row">
<?php 
if($hasSideBar == true)
{
    echo '<div class="small-12 medium-8 large-8">';
}
else
{
   echo '<div class="small-12 medium-12 large-12 normal-page-content relative">';
}
?>
    <?php while ( have_posts() ) : the_post(); 
    ?>
        
        <?php 
        $theGeneralPageTitle = get_the_title();
            echo '<h1>'.$theGeneralPageTitle.'</h1>';
            if($showTitleIcon == true)
            {
                echo '<div class="show-title-icon"></div>';
            }
        ?>
        
    <?php echo the_content(); ?>
    <?php endwhile; // end of the loop. ?>
    
</div></div>
<?php
//Contents for about us page

$aboutColumnOne = get_field('about_column_one');
$aboutColumnTwo = get_field('about_column_two');
$aboutColumnThree = get_field('about_column_three');
if(!empty($aboutColumnOne) && !empty($aboutColumnTwo) && !empty($aboutColumnThree))
{
    ?>
    <section id="about-us-columns" class="about-us-columns">
        <div class="row">
            <div class="small-12 medium-4 large-4 columns left">
                <?php echo $aboutColumnOne; ?>
            </div>
            <div class="small-12 medium-4 large-4 columns left">
                 <?php echo $aboutColumnTwo; ?>
            </div>
            <div class="small-12 medium-4 large-4 columns left">
                 <?php echo $aboutColumnThree; ?>
            </div>
        </div>
    </section>
    <?php
}
?>  
    
<?php
$subAboutColumnOne = get_field('sub_about_block_one');
$subAboutColumnTwo = get_field('sub_about_block_two');
if(!empty($subAboutColumnOne) && !empty($subAboutColumnTwo))
{
?>
<section class="subsection-content" id="sub-about-sections">
   <div class="row">
       <div class="small-12 medium-5 large-5 columns left sub-section-contents">
           <?php echo $subAboutColumnOne; ?>
       </div>
       <div class="small-12 medium-5 large-5 medium-offset-1 columns left sub-section-contents">
           <?php echo $subAboutColumnTwo; ?>
       </div>
   </div>
</section>
<?php
}
?>
    
<?php
if(is_active_sidebar('general'))
{
?>
    <aside role="sidebar" class="sidebar small-12 medium-4 large-4" id="sidebar">
        <?php get_sidebar('general'); ?>
    </aside>
<?php
}
?>
</section>


<?php
$getOurServicesList = get_field('select_core_services');
if(count($getOurServicesList))
{
    $servicesTitle = get_field('core_services_title');
?>
<section id="discover-services" class="discover-our-services">
    <div class="row">
        <?php echo ((!empty($servicesTitle))) ? '<h2>'.$servicesTitle.'</h2>' : '<h2>Our Core Services</h2>'; ?>
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
             
             echo '<p>'.$service->post_content.'</p>';
             
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

<?php echo do_shortcode('[sub_page_bottom_shortcode]'); ?>

<?php
  get_footer();
?>
