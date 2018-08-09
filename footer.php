<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Rookie
 */
$options = get_option( 'themeboy', array() );
if ( array_key_exists( 'logo_url', $options ) && ! empty( $options['logo_url'] ) ) {
	$logo = $options['logo_url'];
	$logo = esc_url( $logo );
}
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-area">
			<div id="quaternary" class="footer-widgets" role="complementary">
				<?php for ( $i = 1; $i <= 3; $i++ ) { ?>
					<div class="footer-widget-region"><?php dynamic_sidebar( sprintf( 'footer-%d', $i ) ); ?></div>
				<?php } ?>
			</div>
		</div><!-- .footer-area -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<div class="site-info">
	<div class="info-area">
		<?php rookie_footer(); ?>
	</div><!-- .info-area -->
</div><!-- .site-info -->

<?php wp_footer(); ?>

</body>
</html>
