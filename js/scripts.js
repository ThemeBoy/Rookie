/**
 * scripts.js
 *
 * Custom scripts for the Rookie theme.
 */
( function( $ ) {
	$('.comment-metadata time').timeago();

	/**
	 * Prevent changing location when clicking on a parent item on the main navigation.
	 */
	var is_touch_device = !! ( 'ontouchstart' in window );
	if ( is_touch_device ) {
		$( '#site-navigation .menu-item-has-children > a' ).click( function( e ) {
			e.preventDefault();
		} );
	}
} )( jQuery );
