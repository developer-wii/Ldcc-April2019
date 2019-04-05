<?php
  get_header();
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
    
    <div class="small-12 medium-12 large-12 columns left corporate-page">
        <?php while ( have_posts() ) : the_post(); ?>
             <?php echo the_content(); ?>
        <?php endwhile; ?>
    </div>
</div>
</section>


<?php
$corporateColumnOne = get_field('corporate_column_one');
$corporateColumnTwo = get_field('corporate_column_two');
$corporateColumnThree = get_field('corporate_column_three');
if(!empty($corporateColumnOne) && !empty($corporateColumnTwo) && !empty($corporateColumnThree))
{
?>
<section class="corporate-columns" id="corporate-columns">
        <div class="row">
            <div class="small-12 medium-4 large-4 columns left">
                <?php echo $corporateColumnOne; ?>
            </div>
            <div class="small-12 medium-4 large-4 columns left">
                 <?php echo $corporateColumnTwo; ?>   
            </div>
            <div class="small-12 medium-4 large-4 columns left">
                <?php echo $corporateColumnThree; ?>
            </div>
        </div>
    </section>
<?php
}
?>


<?php echo do_shortcode('[sub_page_bottom_shortcode]'); ?>


<?php
  get_footer();
?>