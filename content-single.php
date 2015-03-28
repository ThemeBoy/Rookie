<?php
/**
 * @package Rookie
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! is_single() ) { ?><a href="<?php echo esc_url( get_permalink() ); ?>"><?php } ?>

	<header class="entry-header">
		<?php
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
			the_post_thumbnail( 'large' );
		} 
		?>

		<?php rookie_entry_meta(); ?>
		<?php rookie_entry_date(); ?>

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( ! is_single() ) { ?></a><?php } ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'rookie' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php rookie_entry_footer(); ?>
</article><!-- #post-## -->
