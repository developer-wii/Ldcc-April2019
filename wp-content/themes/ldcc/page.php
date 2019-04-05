<?php
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

<section role="content" id="content" class="content page-standard">
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
        
      <div class="container-block">  
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
      </div>
        
        
</div>
<?php
if(is_active_sidebar('general'))
{
?>
    <aside role="sidebar" class="sidebar small-12 medium-4 large-4 page-sidebar-widgets" id="sidebar">
        <?php get_sidebar('general'); ?>
    </aside>
<?php
}
?>
</div>
</section>

<?php 
if(!is_page(6))
{
    echo do_shortcode('[sub_page_bottom_shortcode]');
}
?>


<?php
  get_footer();
?>
