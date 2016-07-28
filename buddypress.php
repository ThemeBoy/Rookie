<?php
/**
 * The template for displaying all BuddyPress pages.
 *
 * @package Rookie
 */

get_header(); ?>

	<div id="primary" class="content-area content-area-full-width">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post();

				get_template_part( 'content', 'notitle' );
			
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
