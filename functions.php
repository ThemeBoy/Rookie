<?php
/**
 * Rookie functions and definitions
 *
 * @package Rookie
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'rookie_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function rookie_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Rookie, use a find and replace
	 * to change 'rookie' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'rookie', get_template_directory() . '/languages' );

	// Declare SportsPress support.
	add_theme_support( 'sportspress' );

	// Declare WooCommerce support.
	add_theme_support( 'woocommerce' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add custom header support.
	$args = array(
		'default-image' => get_template_directory_uri() . '/images/header.jpg',
		'width'     	    	=> 1000,
		'height' 				=> 150,
		'flex-width' 			=> true,
		'flex-height' 			=> true,
		'header-text' 			=> true,
		'default-text-color' 	=> '222222',
	);
	add_theme_support( 'custom-header', $args );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'rookie' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'rookie_custom_background_args', array(
		'default-color' => 'e8e8e8',
		'default-image' => '',
	) ) );
}
endif; // rookie_setup
add_action( 'after_setup_theme', 'rookie_setup' );

/**
 * Disable default frontend SportsPress styles.
 */
function rookie_disable_sportspress_css() {
	return 'no';
}
add_filter( 'option_sportspress_enable_frontend_css', 'rookie_disable_sportspress_css' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function rookie_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'rookie' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Header', 'rookie' ),
		'id'            => 'header-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	$footer_widget_regions = apply_filters( 'rookie_footer_widget_regions', 4 );

	for ( $i = 1; $i <= intval( $footer_widget_regions ); $i++ ) {
		register_sidebar( array(
			'name' 				=> sprintf( __( 'Footer %d', 'rookie' ), $i ),
			'id' 				=> sprintf( 'footer-%d', $i ),
			'description' 		=> sprintf( __( 'Widgetized Footer Region %d.', 'rookie' ), $i ),
			'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
			'after_widget' 		=> '</aside>',
			'before_title' 		=> '<h3>',
			'after_title' 		=> '</h3>'
			)
		);
	}
}
add_action( 'widgets_init', 'rookie_widgets_init' );

/**
 * Register Lato Google font for Rookie.
 *
 * @since Rookie 1.0
 *
 * @return string
 */
function rookie_lato_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'rookie' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Lato:400,700,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Register Oswald Google font for Rookie.
 *
 * @since Rookie 1.0
 *
 * @return string
 */
function rookie_oswald_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Oswald, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Oswald font: on or off', 'rookie' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Oswald:400,700' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles.
 */
function rookie_scripts() {
	// Add fonts used in the main stylesheet.
	wp_enqueue_style( 'rookie-oswald', rookie_oswald_font_url(), array(), null );
	wp_enqueue_style( 'rookie-lato', rookie_lato_font_url(), array(), null );

	wp_enqueue_style( 'dashicons' );

	// Load our main stylesheet.
	wp_enqueue_style( 'rookie-style', get_stylesheet_uri() );

	// Custom colors
	add_action( 'wp_print_scripts', 'rookie_custom_colors', 30 );

	wp_enqueue_script( 'rookie-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'rookie-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rookie_scripts' );


/**
 * Enqueue scripts and styles.
 */
function rookie_custom_colors() {
	$custom = get_option( 'sportspress_custom_css', null );

	$align = get_option( 'sportspress_table_text_align', 'default' );
	$padding = get_option( 'sportspress_table_padding', null );

	$offset = get_option( 'sportspress_header_offset', '' );
	if ( $offset === '' ) {
		$template = get_option( 'template' );
		$offset = ( 'twentyfourteen' == $template ? 48 : 0 );
	}

	$colors = (array) get_option( 'sportspress_frontend_css_colors', array() );

	// Defaults
	if ( empty( $colors['primary'] ) ) $colors['primary'] = '#2b353e';
	if ( empty( $colors['accent'] ) ) $colors['accent'] = '#00a69c';
	if ( empty( $colors['background'] ) ) $colors['background'] = '#f4f4f4';
	if ( empty( $colors['text'] ) ) $colors['text'] = '#222222';
	if ( empty( $colors['heading'] ) ) $colors['heading'] = '#ffffff';
	if ( empty( $colors['link'] ) ) $colors['link'] = '#00a69c';

	// Calculate colors
	$colors['accent_dark'] = rookie_hex_darker( $colors['accent'], 30, true );
	$colors['highlight'] = rookie_hex_lighter( $colors['background'], 30, true );
	$colors['border'] = rookie_hex_darker( $colors['background'], 20, true );
	$colors['text_lighter'] = rookie_hex_mix( $colors['text'], $colors['background'] );
	$colors['heading_alpha'] = 'rgba(' . implode( ', ', rookie_rgb_from_hex( $colors['heading'] ) ) . ', 0.7)';
	$colors['link_hover'] = rookie_hex_darker( $colors['link'], 30, true );
	?>
	<style type="text/css"> /* SportsPress Frontend CSS */
	caption,
	.main-navigation,
	.sp-heading,
	.sp-table-caption,
	.sp-template-countdown .event-name,
	.sp-template-event-venue thead th,
	.sp-template-player-gallery .gallery-caption {
		background: <?php echo $colors['primary']; ?>; }
	blockquote:before,
	q:before {
		color: <?php echo $colors['accent']; ?>; }
	cite:before,
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"],
	.main-navigation a:hover,
	.sp-template-player-gallery .gallery-item strong {
		background: <?php echo $colors['accent']; ?>; }
	caption,
	.sp-table-caption,
	.sp-template-countdown .event-name,
	.sp-template-event-venue thead th {
		border-top-color: <?php echo $colors['accent']; ?>; }
	button:hover,
	input[type="button"]:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover,
	button:focus,
	input[type="button"]:focus,
	input[type="reset"]:focus,
	input[type="submit"]:focus,
	button:active,
	input[type="button"]:active,
	input[type="reset"]:active,
	input[type="submit"]:active {
		background: <?php echo $colors['accent_dark']; ?>; }
	pre,
	code,
	kbd,
	tt,
	var,
	table,
	.main-navigation li.page_item_has_children:hover a:hover,
	.entry-meta,
	.comment-content,
	.sp-view-all-link,
	.sp-template-countdown .event-venue,
	.sp-template-countdown .event-league,
	.sp-template-countdown time span,
	.woocommerce .woocommerce-breadcrumb,
	.woocommerce-page .woocommerce-breadcrumb {
		background: <?php echo $colors['background']; ?>; }
	.comment-content:after {
		border-right-color: <?php echo $colors['background']; ?>; }
	.site-content,
	.main-navigation li.page_item_has_children:hover > a,
	.main-navigation ul ul,
	.widget_calendar #today,
	.sp-highlight,
	.sp-template-event-calendar #today,
	.sp-template-event-blocks .event-title {
		background: <?php echo $colors['highlight']; ?>; }
	pre,
	code,
	kbd,
	tt,
	var,
	table,
	th,
	td,
	tbody td,
	th:first-child, td:first-child,
	th:last-child, td:last-child,
	input[type="text"],
	input[type="email"],
	input[type="url"],
	input[type="password"],
	input[type="search"],
	textarea,
	.entry-meta,
	.comment-metadata .edit-link,
	.comment-content,
	.sp-view-all-link,
	.sp-template-countdown .event-venue,
	.sp-template-countdown .event-league,
	.sp-template-countdown time span,
	.sp-template-countdown time span:first-child,
	.sp-template-event-blocks .event-title,
	.sp-footer-sponsors .sp-sponsors,
	.sp-template-tournament-bracket table,
	.sp-template-tournament-bracket thead th,
	.woocommerce .woocommerce-breadcrumb,
	.woocommerce-page .woocommerce-breadcrumb {
		border-color: <?php echo $colors['border']; ?>; }
	.comment-content:before {
		border-right-color: <?php echo $colors['border']; ?>; }
	}
	body,
	button,
	input,
	select,
	textarea,
	.site-title a,
	.site-title a:hover,
	.site-title a:focus,
	.site-title a:active,
	.site-description,
	.main-navigation li.page_item_has_children:hover > a,
	.main-navigation ul ul a,
	.main-navigation ul ul a:hover,
	.widget_recent_entries ul li:before,
	.widget_pages ul li:before,
	.widget_categories ul li:before,
	.widget_archive ul li:before,
	.widget_recent_comments ul li:before,
	.widget_nav_menu ul li:before,
	.widget_links ul li:before,
	.widget_meta ul li:before,
	.entry-title a,
	a .entry-title,
	.page-title a,
	a .page-title,
	.sp-template-event-venue .sp-table-caption,
	.sp-template-event-blocks .event-title,
	.sp-template-event-blocks .event-title a,
	.woocommerce ul.products li.product h3,
	.woocommerce-page ul.products li.product h3 {
		color: <?php echo $colors['text']; ?>; }
	.widget_recent_entries ul li a,
	.widget_pages ul li a,
	.widget_categories ul li a,
	.widget_archive ul li a,
	.widget_recent_comments ul li a,
	.widget_nav_menu ul li a,
	.widget_links ul li a,
	.widget_meta ul li a,
	.widget_calendar #prev a,
	.widget_calendar #next a,
	.comment-metadata a,
	.comment-body .reply a,
	.wp-caption-text,
	.sp-view-all-link,
	.sp-template-event-calendar #prev a,
	.sp-template-event-calendar #next a {
		color: <?php echo $colors['text_lighter']; ?>; }
	caption,
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"],
	.main-navigation .nav-menu > .current-menu-item > a,
	.main-navigation .nav-menu > .current-menu-parent > a,
	.main-navigation .nav-menu > .current-menu-ancestor > a,
	.main-navigation .nav-menu > .current_page_item > a,
	.main-navigation .nav-menu > .current_page_parent > a,
	.main-navigation .nav-menu > .current_page_ancestor > a,
	.main-navigation a:hover,
	.sp-template .gallery-caption,
	.sp-template .gallery-caption a,
	.sp-heading,
	.sp-table-caption,
	.sp-template-countdown .event-name,
	.sp-template-event-venue thead th,
	.sp-template-countdown .event-name a {
		color: <?php echo $colors['heading']; ?>; }
	.main-navigation a {
		color: <?php echo $colors['heading_alpha']; ?>; }
	a,
	.main-navigation ul ul .current-menu-item > a,
	.main-navigation ul ul .current-menu-parent > a,
	.main-navigation ul ul .current-menu-ancestor > a,
	.main-navigation ul ul .current_page_item > a,
	.main-navigation ul ul .current_page_parent > a,
	.main-navigation ul ul .current_page_ancestor > a,
	.widget_recent_entries ul li a:hover,
	.widget_pages ul li a:hover,
	.widget_categories ul li a:hover,
	.widget_archive ul li a:hover,
	.widget_recent_comments ul li a:hover,
	.widget_nav_menu ul li a:hover,
	.widget_links ul li a:hover,
	.widget_meta ul li a:hover,
	.widget_calendar #prev a:hover,
	.widget_calendar #next a:hover,
	.comment-metadata a:hover,
	.comment-body .reply a:hover,
	.sp-view-all-link:hover,
	.sp-template-event-calendar #prev a:hover,
	.sp-template-event-calendar #next a:hover {
		color: <?php echo $colors['link']; ?>; }
	a:hover {
		color: <?php echo $colors['link_hover']; ?>; }

	<?php do_action( 'sportspress_frontend_css', $colors ); ?>
	
	<?php if ( ! empty( $custom ) ) { ?>
	/* SportsPress Custom CSS */
	<?php echo $custom; ?>
	<?php } ?>
	</style>
	<?php
}

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Move SportsPress header sponsors selector.
 */
function rookie_header_sponsors() {
	return '.site-branding';
}
add_filter( 'sportspress_header_sponsors_selector', 'rookie_header_sponsors' );

/**
 * Helper functions
 */
if ( ! function_exists( 'rookie_rgb_from_hex' ) ) {
	function rookie_rgb_from_hex( $color ) {
		$color = str_replace( '#', '', $color );
		// Convert shorthand colors to full format, e.g. "FFF" -> "FFFFFF"
		$color = preg_replace( '~^(.)(.)(.)$~', '$1$1$2$2$3$3', $color );

		$rgb['r'] = hexdec( $color{0}.$color{1} );
		$rgb['g'] = hexdec( $color{2}.$color{3} );
		$rgb['b'] = hexdec( $color{4}.$color{5} );
		return $rgb;
	}
}

if ( ! function_exists( 'rookie_hex_darker' ) ) {
	function rookie_hex_darker( $color, $factor = 30, $absolute = false ) {
		$base = rookie_rgb_from_hex( $color );
		$color = '#';

		foreach ($base as $k => $v) :
	    	if ( $absolute ) {
	    		$amount = $factor;
	    	} else {
		        $amount = $v / 100;
		        $amount = round($amount * $factor);
		    }
	        $new_decimal = max( $v - $amount, 0 );

	        $new_hex_component = dechex($new_decimal);
	        if(strlen($new_hex_component) < 2) :
	        	$new_hex_component = "0" . $new_hex_component;
	        endif;
	        $color .= $new_hex_component;
		endforeach;

		return $color;
	}
}

if ( ! function_exists( 'rookie_hex_lighter' ) ) {
	function rookie_hex_lighter( $color, $factor = 30, $absolute = false ) {
		$base = rookie_rgb_from_hex( $color );
		$color = '#';

	    foreach ($base as $k => $v) :
	    	if ( $absolute ) {
	    		$amount = $factor;
	    	} else {
		        $amount = 255 - $v;
		        $amount = $amount / 100;
		        $amount = round($amount * $factor);
		    }
	        $new_decimal = min( $v + $amount, 255 );

	        $new_hex_component = dechex($new_decimal);
	        if(strlen($new_hex_component) < 2) :
	        	$new_hex_component = "0" . $new_hex_component;
	        endif;
	        $color .= $new_hex_component;
	   	endforeach;

	   	return $color;
	}
}

if ( ! function_exists( 'rookie_hex_mix' ) ) {
	function rookie_hex_mix( $x, $y ) {
		$rgbx = rookie_rgb_from_hex( $x );
		$rgby = rookie_rgb_from_hex( $y );
		$r = dechex( ( $rgbx['r'] + $rgby['r'] ) / 2 );
		$g = dechex( ( $rgbx['g'] + $rgby['g'] ) / 2 );
		$b = dechex( ( $rgbx['b'] + $rgby['b'] ) / 2 );
		return '#' . $r . $g . $b;
	}
}