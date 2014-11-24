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

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

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
		'default-color' => 'ffffff',
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

	// Load our main stylesheet.
	wp_enqueue_style( 'rookie-style', get_stylesheet_uri() );

	wp_enqueue_script( 'rookie-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'rookie-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rookie_scripts' );

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
