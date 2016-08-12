<?php
/**
 * The template for displaying all SportsPress pages.
 *
 * @package Rookie
 */

get_header(); ?>

	<div id="primary" class="content-area content-area-<?php echo rookie_get_sidebar_setting(); ?>-sidebar">
		<main id="main" class="site-main" role="main">

			<?php if ( is_archive() && have_posts() ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php single_cat_title(); ?></h1>
					<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
				</header><!-- .page-header -->
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post();
					
				get_template_part( 'content', 'page' );

				if ( is_archive() && 'sp_event' === get_post_type() ):

					sp_get_template( 'event-logos.php' );

				endif;
			
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
