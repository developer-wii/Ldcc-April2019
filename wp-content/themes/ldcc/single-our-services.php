<?php
  get_header();
?>

<section role="content" id="content" class="content">
    <div class="row">
            <div class="small-12 medium-12 large-12 columns right single-our-services">

                <div class="job-single page-content post-<?php the_ID(); ?>">
                    <?php  
                    if (have_posts()) :  while (have_posts()) : the_post(); ?>

                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                    <?php
                        endwhile; 
                        else: ?>
                        <h1>Service Not Found</h1>
                        <p>Sorry, you may of followed an old link to this page.</p>
                      <?php endif; ?>
                 </div>

            </div>
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