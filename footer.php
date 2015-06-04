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
		<?php
		$options = get_option( 'themeboy', array() );
		if ( array_key_exists( 'logo_url', $options ) && ! empty( $options['logo_url'] ) ) {
			$logo = $options['logo_url'];
			$logo = esc_url( $logo );
			?>
			<div class="footer-logo"><img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>"></div>
		<?php } ?>

		<div id="quaternary" class="footer-widgets" role="complementary">
			<?php for ( $i = 1; $i <= 3; $i++ ) { ?>
				<div class="footer-widget-region"><?php dynamic_sidebar( sprintf( 'footer-%d', $i ) ); ?></div>
			<?php } ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<div class="site-info">
	<?php rookie_footer(); ?>
</div><!-- .site-info -->

<?php wp_footer(); ?>

</body>
</html>
