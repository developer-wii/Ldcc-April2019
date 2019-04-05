<?php
/*
// EXTENDS RECENT POSTS
Class My_Recent_Posts_Widget extends WP_Widget_Recent_Posts {

	function widget($args, $instance) {
	
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
				
		if( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
			$number = 10;
					
		$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
		if( $r->have_posts() ) :
			
			echo $before_widget;
			if( $title ) echo $before_title . $title . $after_title; ?>

			<ul>
                        <?php while( $r->have_posts() ) : $r->the_post(); 				

                                echo '<li>';
                                $newsPhoto = wp_get_attachment_image(get_post_thumbnail_id(get_the_ID()), 'sidebar-news-image');
                                echo $newsPhoto;

                                echo '<h4><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h4>';
                                echo '<p>' . limit_words(get_the_excerpt(), 13) . '...</p>';

                                 echo '</li>';
                

                        endwhile; ?>
			</ul>
			 
			<?php
			echo $after_widget;
		
		wp_reset_postdata();
		
		endif;
	}
}
function my_recent_widget_registration() {
  unregister_widget('WP_Widget_Recent_Posts');
  register_widget('My_Recent_Posts_Widget');
}
add_action('widgets_init', 'my_recent_widget_registration');
 * 
 */
?>