<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header(); ?>

<div class="blog-bar"></div>

<div class="content-area blog-index">

    <div class="row">
        <div class="grid_12">
        <h1>Search results for '<?php the_search_query() ?>'</h1>
                        	
        <p>We found <?php echo $wp_query->found_posts; ?> results matching your query.</p>
        
        </div>
    </div>
    
    <div class="row">
                
    
        
        <?php if (have_posts()) : ?>

        <?php $counter = 1; 
        $columnOne = '<div class="grid_4">';
        $columnTwo = '<div class="grid_4">';
        $columnThree = '<div class="grid_4">';
        ?>
        <?php while (have_posts()) : the_post(); ?>
    
        <?php //($counter %3 == 0) ? $class='first-post' : $class=''; ?>

        <?php
        switch($counter)
        {
            case '1':
                
$columnOne .= '<div class="individual-post wow " data-wow-delay="0.2s"><div class="post '.$class.'">';
    $featuredImage = wp_get_attachment_image( get_post_thumbnail_id($post->ID), 'blog-index' ); 
    if($featuredImage) {
    $columnOne .= $featuredImage; 
    }
$columnOne .= '<h3 class="title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3><p>'.limit_words(get_the_excerpt(), 27).'</p><a class="button" href="'.get_the_permalink().'">Read More</a></div></div>';
                    
            break;
            case '2':

$columnTwo .= '<div class="individual-post wow " data-wow-delay="0.2s"><div class="post '.$class.'">';
    $featuredImage = wp_get_attachment_image( get_post_thumbnail_id($post->ID), 'blog-index' ); 
    if($featuredImage) {
    $columnTwo .= $featuredImage; 
    }
$columnTwo .= '<h3 class="title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3><p>'.limit_words(get_the_excerpt(), 27).'</p><a class="button" href="'.get_the_permalink().'">Read More</a></div></div>';
                
            break;
            case '3':
                
$columnThree .= '<div class="individual-post wow " data-wow-delay="0.2s"><div class="post '.$class.'">';
    $featuredImage = wp_get_attachment_image( get_post_thumbnail_id($post->ID), 'blog-index' ); 
    if($featuredImage) {
    $columnThree .= $featuredImage; 
    }
$columnThree .= '<h3 class="title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3><p>'.limit_words(get_the_excerpt(), 27).'</p><a class="button" href="'.get_the_permalink().'">Read More</a></div></div>';
                
            break;
        }
        
        ?>
        
        <?php $counter++; 
        if($counter == 4) {$counter = 0;}
        ?>
    <?php endwhile; ?>
    <?php
        $columnOne .= '</div>';
        $columnTwo .= '</div>';
        $columnThree .= '</div>';   
        
        echo $columnOne . $columnTwo . $columnThree;
        
        ?>
<?php else : ?>
        
        <div class="grid_12">
            <div class="first-post last-post">
                <h1>Sorry, No Results Found!</h1>
                <h3 class="grey">Unfortunately, we have no posts matching that criteria. Please try searching again...</h3>
            </div>
        </div>
        
<?php endif; ?>
           
       
 
<div class="clear"></div>

  <div class="grid_9 offset_3">
      <?php if (function_exists("pagination")) { pagination($pages = '', $range = 2); } ?>  
  </div>
        
        
</div>
</div>


<?php echo do_shortcode('[social_connect_bar dobreak="no"]'); ?>

<?php
get_footer();
?>