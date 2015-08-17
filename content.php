<?php
/**
 * @package Rookie
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( get_the_post_thumbnail() ) { ?>
		<a class="article-thumbnail" href="<?php echo esc_url( get_permalink() ); ?>">
			<?php the_post_thumbnail( 'thumbnail' ); ?>
		</a>
	<?php } ?>

	<div class="single-article">
		<header class="article-header">
			<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="article-details">
					<?php do_action( 'rookie_before_article_details' ); ?>
					<?php rookie_entry_date(); ?>
					<?php do_action( 'rookie_article_details' ); ?>
				</div>
			<?php endif; ?>
		</header><!-- .article-header -->

		<div class="entry-content article-content">
			<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'rookie' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );
			?>

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