<?php

  /**
  *@desc A single blog post See page.php is for a page layout.
  */

  get_header();
?>
  <div class="blog-bar"></div>
<?php  
  if (have_posts()) : while (have_posts()) : the_post();
  ?>

<div class="content-area">

<div class="post-<?php the_ID(); ?> single-blog-post">
    <div class="row">
        
        <div class="grid_12 author-post single-posts">
            
            
        <?php ($counter % 3 == 0) ? $class='first-post' : $class=''; ?>
        <?php $featuredImage = wp_get_attachment_image( get_post_thumbnail_id($post->ID), 'blog-single-image');  
        if($featuredImage) {$postClass = 'featured-image';}
        ?>
            
                <div class="post <?php echo $class .' '. $postClass; ?> ">
                                      
                        <?php 
                        if($featuredImage) {
                        ?>
                    <div class="aligncenter">
                        <?php echo $featuredImage; ?>
                    </div>
                        <?php
                        }
                        ?>
                        
                        <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php the_content(); ?>

                    </div>

        <?php $counter++; ?>

	<?php

            //comments_template();

            endwhile; else: ?>
            <p>Sorry, no posts matched your criteria.</p>
          <?php
            endif;
            ?>

        </div>   
            
  
       <div class="clear"></div>  
   
      </div>
     </div>
    
    </div>
  
  <?php
  get_footer();

?>