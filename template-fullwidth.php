<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Full Width
 *
 * @package Rookie
 */

get_header(); ?>

	<div id="primary" class="content-area content-area-full-width">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				if ( in_array( get_post_type(), array( 'sp_player', 'sp_staff', 'sp_team' ) ) ) {
					get_template_part( 'content', 'nothumb' );
				} else {
					get_template_part( 'content', 'page' );
				}
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
