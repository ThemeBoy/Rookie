<?php
/**
 * @package Rookie
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! is_single() ) { ?><a href="<?php echo esc_url( get_permalink() ); ?>"><?php } ?>

	<?php if ( has_post_thumbnail() ) { ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail( 'large' ); ?>
		</div>
	<?php } ?>

	<div class="single-entry">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title single-entry-title">', '</h1>' ); ?>

			<div class="entry-details">
				<?php do_action( 'rookie_before_entry_details' ); ?>
				<?php rookie_entry_meta(); ?>
				<?php rookie_entry_date(); ?>
				<?php do_action( 'rookie_entry_details' ); ?>
			</div>
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
	</div>
</article><!-- #post-## -->
