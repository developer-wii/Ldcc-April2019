<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package LLDC
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header monthly-title">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title posttitle">', '</h1>' );
		else :
			//the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				lldc_posted_on();
				lldc_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="content-plan">

	<div class="col-md-2 col-sm-3 monthly-img">
	   <?php the_post_thumbnail( 'medium' ); ?>
	</div>
    
    <div class="col-md-10 col-sm-9 monthly-content">
    	<div class="entry-content">
			<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'lldc' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lldc' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->
    </div>
		
</div>
	<footer class="entry-footer">
		<?php lldc_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
