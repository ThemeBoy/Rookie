<?php
/**
 * The template used for displaying homepage content.
 *
 * @package Rookie
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it. ?>
		<header class="entry-header">
			<?php the_post_thumbnail( 'large' ); ?>
		</header><!-- .entry-header -->
	<?php } ?>

	<div class="entry-content">
		<div class="homepage-widgets">
			<?php dynamic_sidebar( 'homepage-1' ); ?>
		</div>

		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
