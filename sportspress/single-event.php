<?php
/**
 * The template for displaying SportsPress team pages.
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

			<?php while ( have_posts() ) : the_post(); ?>

        <?php
          // If team names are being displayed, don't output the event title
          if ( 'yes' === get_option( 'sportspress_event_show_logos', 'yes' ) && 'yes' === get_option( 'sportspress_event_logos_show_team_names', 'yes' ) ) {
            get_template_part( 'content', 'notitle' );
          } else {
            get_template_part( 'content', 'page' );
          }

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
