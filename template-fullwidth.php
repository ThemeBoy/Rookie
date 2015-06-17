<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Full Width
 *
 * @package Rookie
 */

if ( isset( $full_content_width ) ) $content_width = $full_content_width;
get_header(); ?>

	<div id="primary" class="content-area content-area-full-width">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
