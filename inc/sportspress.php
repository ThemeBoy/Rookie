<?php
/**
 * SportsPress Compatibility File
 * See: http://themeboy.com/sportspress/
 *
 * @package Rookie
 */

/**
 * Add theme support for SportsPress.
 */
function rookie_sportspress_setup() {
	add_theme_support( 'sportspress' );
}
add_action( 'after_setup_theme', 'rookie_sportspress_setup' );

function rookie_header_sponsors() {
	return '.site-branding';
}
add_filter( 'sportspress_header_sponsors_selector', 'rookie_header_sponsors' );