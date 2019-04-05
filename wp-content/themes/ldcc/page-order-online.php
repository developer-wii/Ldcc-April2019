<?php
get_header();
?>

<section role="content" id="content" class="content">
        <?php while ( have_posts() ) : the_post(); ?>
        <?php echo the_content(); ?>
        <?php endwhile; // end of the loop. ?>
</section>

<aside role="sidebar" class="sidebar" id="sidebar">
    <?php get_sidebar(); ?>
</aside>

<?php
get_footer();
?>