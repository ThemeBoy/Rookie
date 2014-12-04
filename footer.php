<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Rookie
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="http://wordpress.org/"><?php printf( __( 'Proudly powered by %s', 'rookie' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<a href="http://themeboy.com/"><?php printf( __( 'Designed by %s', 'rookie' ), 'ThemeBoy' ); ?></a>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
