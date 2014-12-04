/**
 * scripts.js
 *
 * Custom scripts for the Rookie theme.
 */
( function( $ ) {
	$.timeago.settings.strings = timeago_strings;
	$('.comment-metadata time').timeago();
} )( jQuery );
