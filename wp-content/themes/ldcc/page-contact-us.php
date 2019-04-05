<?php
  get_header();
?>

<section role="content" id="content" class="content">
<div class="row">
    
    <div class="small-12 medium-8 large-8 columns right contact-us-page">
        <?php while ( have_posts() ) : the_post(); ?>
             <?php echo the_content(); ?>
        <?php endwhile; ?>
    </div>

    <aside role="sidebar" class="sidebar small-12 medium-4 large-4 columns left contact-sidebar" id="sidebar">
        <?php get_sidebar(); ?>
    </aside>

</div>
</section>

<?php
  get_footer();
?>
