<?php
/**
 * The template for displaying all SportsPress event pages.
 *
 * @package Rookie
 */

if ( isset( $full_content_width ) ) $content_width = $full_content_width;
get_header(); ?>

	<div id="primary" class="content-area content-area-full-width">
		<main id="main" class="site-main" role="main">

			<?php if ( is_archive() && have_posts() ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php single_cat_title(); ?></h1>
					<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
				</header><!-- .page-header -->
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
