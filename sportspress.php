<?php
/**
 * The template for displaying all SportsPress pages.
 *
 * @package Rookie
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php if ( is_archive() && have_posts() ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php single_cat_title(); ?></h1>
					<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
				</header><!-- .page-header -->
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'nothumb' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
