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
	$content_width = 620; /* pixels */
}
if ( ! isset( $full_content_width ) ) {
	$full_content_width = 960; /* pixels */
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

	// Declare Mega Slider support.
	add_theme_support( 'mega-slider' );

	// Declare Social Sidebar support.
	add_theme_support( 'social-sidebar' );

	// Declare News Widget support.
	add_theme_support( 'news-widget' );

	// Declare WooCommerce support.
	add_theme_support( 'woocommerce' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add featured image support.
	add_theme_support( 'post-thumbnails' );

	// Add title tag support.
	add_theme_support( 'title-tag' );

	// Add custom header support.
	add_theme_support( 'custom-header', array(
		'default-image'          => '',
		'width'                  => 1000,
		'height'                 => 150,
		'flex-height'            => true,
		'flex-width'             => true,
		'uploads'                => true,
		'random-default'         => false,
		'header-text'            => true,
		'default-text-color'     => apply_filters( 'rookie_default_header_text_color', '222222' ),
	) );

	add_editor_style();

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

	// Add starter content.
	add_theme_support( 'starter-content', array(
		'widgets' => array(
			'footer-1' => array(
				'text_about',
			),
			'footer-2' => array(
				'text_business_info',
			),
			'footer-3' => array(
				'meta',
			),
		),

		'posts' => rookie_starter_content_posts(),

		'nav_menus' => array(
			'primary' => array(
				'name' => __( 'Primary Menu', 'rookie' ),
				'items' => array(
					'page_home',
					'page_blog',
				),
			),
		),

		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),
	) );
}
endif; // rookie_setup
add_action( 'after_setup_theme', 'rookie_setup' );

if ( ! function_exists( 'rookie_theme_starter_content' ) ):
function rookie_theme_starter_content( $content = array(), $config = array() ) {
	$calendars = (array) get_posts("post_type=sp_calendar&numberposts=1&fields=ids");
	$lists = (array) get_posts("post_type=sp_list&numberposts=1&fields=ids");
	$performance = (array) get_posts("post_type=sp_performance&numberposts=1&order=ASC");
	$tables = (array) get_posts("post_type=sp_table&numberposts=1&fields=ids");
	$columns = (array) get_posts("post_type=sp_column&numberposts=-1");

	// Sidebar Widgets
	$content['widgets']['sidebar-1'] = array(
		array( 'sportspress-countdown', array(
			'caption' => __( 'Countdown', 'rookie' ),
		) ),
		array( 'sportspress-event-calendar', array(
			'id' => reset( $calendars ),
			'show_all_events_link' => true,
		) ),
		array( 'sportspress-player-list', array(
			'caption' => __( 'Player List', 'rookie' ),
			'id' => reset( $lists ),
			'number' => 8,
			'columns' => array_merge( array( 'number' ), wp_list_pluck( $performance, 'post_name' ) ),
			'orderby' => 'number',
			'show_all_players_link' => true,
		) ),
	);

	// Homepage Widgets
	$content['widgets']['homepage-1'] = array(
		array( 'sportspress-event-blocks', array(
			'align' => 'left',
			'caption' => __( 'Fixtures', 'rookie' ),
			'status' => 'future',
			'number' => 3,
			'order' => 'ASC',
			'show_all_events_link' => false,
		) ),
		array( 'sportspress-event-blocks', array(
			'align' => 'right',
			'caption' => __( 'Results', 'rookie' ),
			'status' => 'publish',
			'number' => 3,
			'order' => 'DESC',
			'show_all_events_link' => false,
		) ),
		array( 'sportspress-league-table', array(
			'caption' => __( 'League Table', 'rookie' ),
			'id' => reset( $tables ),
			'number' => 10,
			'columns' => wp_list_pluck( $columns, 'post_name' ),
			'show_full_table_link' => true,
		) ),
		array( 'sportspress-player-gallery', array(
			'caption' => __( 'Player Gallery', 'rookie' ),
			'id' => reset( $lists ),
			'number' => 8,
			'columns' => 4,
			'orderby' => 'number',
			'show_all_players_link' => true,
		) ),
	);

	// Pages
	$content['posts']['home']['page_template'] = 'template-homepage.php';

	// Custom Menus
	$items = array(
		array(
			'type' => 'post_type',
			'object' => 'page',
			'object_id' => '{{fixtures-results}}',
		),
		array(
			'type' => 'post_type',
			'object' => 'page',
			'object_id' => '{{league-table}}',
		),
		array(
			'type' => 'post_type',
			'object' => 'page',
			'object_id' => '{{roster}}',
		),
	);
	array_splice( $content['nav_menus']['primary']['items'], 1, 0, $items );

	return apply_filters( 'rookie_theme_starter_content', $content );
}
endif;
add_filter( 'get_theme_starter_content', 'rookie_theme_starter_content', 10, 2 );

if ( ! function_exists( 'rookie_get_search_form' ) ):
function rookie_get_search_form( $form ) {
	//return $untranslated_text;
	$form = str_replace( 'value="' . esc_attr_x( 'Search', 'submit button' ) . '"', 'value="&#61817;" title="' . esc_attr_x( 'Search', 'submit button' ) . '"', $form );
	return $form;
}
add_filter( 'get_search_form', 'rookie_get_search_form' );
endif;

/**
 * Render title in head for backwards compatibility.
 */
if ( ! function_exists( '_wp_render_title_tag' ) ):
function rookie_render_title() {
	?>
	<title><?php wp_title( '-', true, 'right' ); ?></title>
	<?php
}
add_action( 'wp_head', 'rookie_render_title' );
endif;

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'rookie_widgets_init' ) ):
function rookie_widgets_init() {
	$sidebar = rookie_get_sidebar_setting();
	
	if ( in_array( $sidebar, array( 'left', 'right' ) ) ) {
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'rookie' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
	} else if ( 'double' === $sidebar ) {
		register_sidebar( array(
			'name'          => sprintf( __( 'Sidebar %d', 'rookie' ), 1 ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );

		register_sidebar( array(
			'name'          => sprintf( __( 'Sidebar %d', 'rookie' ), 2 ),
			'id'            => 'sidebar-2',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
	}

	register_sidebar( array(
		'name'          => __( 'Header', 'rookie' ),
		'id'            => 'header-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Homepage', 'rookie' ),
		'id'            => 'homepage-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar( array(
			'name' 				=> sprintf( __( 'Footer %d', 'rookie' ), $i ),
			'id' 				=> sprintf( 'footer-%d', $i ),
			'description' 		=> sprintf( __( 'Widgetized Footer Region %d.', 'rookie' ), $i ),
			'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
			'after_widget' 		=> '</aside>',
			'before_title' 		=> '<h3 class="widget-title">',
			'after_title' 		=> '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'rookie_widgets_init' );
endif;

/**
 * Call Mega Slider action before content.
 */
if ( ! function_exists( 'rookie_mega_slider' ) ):
function rookie_mega_slider() {
	if ( ! is_front_page() ) return;
	do_action( 'mega_slider' );
}
add_action( 'rookie_before_template', 'rookie_mega_slider' );
endif;

/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'rookie_scripts' ) ):
function rookie_scripts() {
	// Load icon font.
	wp_enqueue_style( 'dashicons' );

	// Load web fonts.
	wp_enqueue_style( 'rookie-lato', add_query_arg( array( 'family' => 'Lato:400,700,400italic,700italic', 'subset' => 'latin-ext' ), "//fonts.googleapis.com/css", array(), null ) );
	wp_enqueue_style( 'rookie-oswald', add_query_arg( array( 'family' => 'Oswald:400,700', 'subset' => 'latin-ext' ), "//fonts.googleapis.com/css", array(), null ) );

	// Load our framework stylesheet.
	wp_enqueue_style( 'rookie-framework-style', get_template_directory_uri() . '/framework.css' );

	// Load RTL framework stylesheet if needed.
	if ( is_rtl() ) {
		wp_enqueue_style( 'rookie-framework-rtl-style', get_template_directory_uri() . '/framework-rtl.css' );
	}

	// Load our main stylesheet.
	wp_enqueue_style( 'rookie-style', get_stylesheet_uri() );

	// Custom colors
	add_action( 'wp_print_scripts', 'rookie_custom_colors', 30 );

	wp_enqueue_script( 'rookie-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'rookie-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	rookie_enqueue_timeago();

	wp_enqueue_script( 'rookie-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery', 'jquery-timeago' ), '0.9', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rookie_scripts' );
endif;

/**
 * Enqueue customize scripts.
 */
if ( ! function_exists( 'rookie_customize_scripts' ) ):
function rookie_customize_scripts() {
	$screen = get_current_screen();
	if ( 'customize' == $screen->id ) {
		wp_enqueue_script( 'rookie-customize-panel', get_template_directory_uri() . '/js/customize-panel.js', array( 'jquery' ), '1.3.2', true );
	} elseif ( 'appearance_page_rookie' == $screen->id ) {
		wp_enqueue_style( 'rookie-admin', get_template_directory_uri() . '/admin.css');
	}
}
add_action( 'admin_enqueue_scripts', 'rookie_customize_scripts' );
endif;

/**
 * Enqueue jQuery timeago if locale available.
 */
if ( ! function_exists( 'rookie_enqueue_timeago' ) ):
function rookie_enqueue_timeago() {
	$locale = get_locale();
	$locale = str_replace( '_', '-', $locale );
	$file = '/js/locales/jquery.timeago.' . $locale . '.js';

	// Check if locale exists with country code
	if ( ! is_readable( get_template_directory() . $file ) ) {
		$locale = substr( $locale, 0, 2 );
		$file = '/js/locales/jquery.timeago.' . $locale . '.js';

		// Check if locale exists without country code
		if ( ! is_readable( get_template_directory() . $file ) ) {
			return;
		}
	}

	// Enqueue script
	wp_enqueue_script( 'jquery-timeago', get_template_directory_uri() . '/js/jquery.timeago.js', array( 'jquery' ), '1.4.1', true );

	// Enqueue locale
	wp_enqueue_script( 'jquery-timeago-' . $locale, get_template_directory_uri() . $file, array( 'jquery', 'jquery-timeago' ), '1.4.1', true );
}
endif;

/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'rookie_custom_colors' ) ):
function rookie_custom_colors() {

	/*
	 * Get color options set via Customizer.
	 * @see rookie_customize_register()
	 */
	$colors = (array) get_option( 'themeboy', array() );
	$colors = array_map( 'esc_attr', $colors );
	
	// Get layout options
	if ( empty( $colors['content_width'] ) ) {
		$width = 1000;
	} else {
		$width = rookie_sanitize_content_width( $colors['content_width'] );
	}
	
	global $content_width;

	if ( empty( $colors['sidebar'] ) ) {
		$sidebar = '';
	} else {
		$sidebar = $colors['sidebar'];
	}

	if ( 'no' == $sidebar || is_page_template( 'template-fullwidth.php' ) ) {
		$content_width = $width - 40;
	} elseif ( 'double' === $sidebar )  {
		$content_width = $width * .52 - 40;
	} else {
		$content_width = $width * .66 - 40;
	}

	?>
	<style type="text/css"> /* Rookie Custom Layout */
	@media screen and (min-width: 1025px) {
		.site-header, .site-content, .site-footer, .site-info {
			width: <?php echo $width; ?>px; }
	}
	</style>
	<?php

	// Return if colors not customized
	if ( ! isset( $colors['customize'] ) ) {
		$enabled = get_option( 'sportspress_enable_frontend_css', 'no' );
		if ( 'yes' !== $enabled ) return;
	} elseif ( ! $colors['customize'] ) {
		return;
	}

	$colors['sponsors_background'] = get_option( 'sportspress_footer_sponsors_css_background', '#f4f4f4' );

	// Defaults
	if ( empty( $colors['primary'] ) ) $colors['primary'] = '#2b353e';
	if ( empty( $colors['background'] ) ) $colors['background'] = '#f4f4f4';
	if ( empty( $colors['content'] ) ) $colors['content'] = '#222222';
	if ( empty( $colors['text'] ) ) $colors['text'] = '#222222';
	if ( empty( $colors['heading'] ) ) $colors['heading'] = '#ffffff';
	if ( empty( $colors['link'] ) ) $colors['link'] = '#00a69c';
	if ( empty( $colors['content_background'] ) ) $colors['content_background'] = '#ffffff';

	// Calculate colors
	$colors['highlight'] = rookie_hex_lighter( $colors['background'], 30, true );
	$colors['border'] = rookie_hex_darker( $colors['background'], 20, true );
	$colors['text_lighter'] = rookie_hex_mix( $colors['text'], $colors['background'] );
	$colors['heading_alpha'] = 'rgba(' . implode( ', ', rookie_rgb_from_hex( $colors['heading'] ) ) . ', 0.7)';
	$colors['link_dark'] = rookie_hex_darker( $colors['link'], 30, true );
	$colors['link_hover'] = rookie_hex_darker( $colors['link'], 30, true );
	$colors['sponsors_border'] = rookie_hex_darker( $colors['sponsors_background'], 20, true );
	$colors['content_border'] = rookie_hex_darker( $colors['content_background'], 31, true );

	?>
	<style type="text/css"> /* Rookie Custom Colors */
	.site-content,
	.main-navigation .nav-menu > .menu-item-has-children:hover > a,
	.main-navigation li.menu-item-has-children:hover a,
	.main-navigation ul ul { background: <?php echo $colors['content_background']; ?>; }
	pre,
	code,
	kbd,
	tt,
	var,
	table,
	.main-navigation li.menu-item-has-children:hover a:hover,
	.main-navigation ul ul li.page_item_has_children:hover > a,
	.entry-footer-links,
	.comment-content,
	.sp-table-wrapper .dataTables_paginate,
	.sp-event-staff,
	.sp-template-countdown .event-name,
	.sp-template-countdown .event-venue,
	.sp-template-countdown .event-league,
	.sp-template-countdown time span,
	.sp-template-details dl,
	.mega-slider__row,
	.woocommerce .woocommerce-breadcrumb,
	.woocommerce-page .woocommerce-breadcrumb,
	.opta-widget-container form {
		background: <?php echo $colors['background']; ?>; }
	.comment-content:after {
		border-right-color: <?php echo $colors['background']; ?>; }
	.widget_calendar #today,
	.sp-highlight,
	.sp-template-event-calendar #today,
	.sp-template-event-blocks .event-title,
	.mega-slider__row:hover {
		background: <?php echo $colors['highlight']; ?>; }
	.sp-tournament-bracket .sp-team .sp-team-name:before {
		border-left-color: <?php echo $colors['highlight']; ?>;
		border-right-color: <?php echo $colors['highlight']; ?>; }
	.sp-tournament-bracket .sp-event {
		border-color: <?php echo $colors['highlight']; ?> !important; }
	caption,
	.main-navigation,
	.site-footer,
	.sp-heading,
	.sp-table-caption,
	.sp-template-gallery .gallery-caption,
	.sp-template-event-logos .sp-team-result,
	.sp-statistic-bar,
	.opta-widget-container h2 {
		background: <?php echo $colors['primary']; ?>; }
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
	.entry-footer-links,
	.comment-metadata .edit-link,
	.comment-content,
	.sp-table-wrapper .dataTables_paginate,
	.sp-event-staff,
	.sp-template-countdown .event-name,
	.sp-template-countdown .event-venue,
	.sp-template-countdown .event-league,
	.sp-template-countdown time span,
	.sp-template-countdown time span:first-child,
	.sp-template-event-blocks .event-title,
	.sp-template-details dl,
	.sp-template-tournament-bracket table,
	.sp-template-tournament-bracket thead th,
	.mega-slider_row,
	.woocommerce .woocommerce-breadcrumb,
	.woocommerce-page .woocommerce-breadcrumb,
	.opta-widget-container form {
		border-color: <?php echo $colors['border']; ?>; }
	.comment-content:before {
		border-right-color: <?php echo $colors['border']; ?>; }
	.sp-tab-menu {
		border-bottom-color: <?php echo $colors['content_border']; ?>; }
	body,
	button,
	input,
	select,
	textarea,
	.main-navigation .nav-menu > .menu-item-has-children:hover > a,
	.main-navigation ul ul a,
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
	.entry-title a:hover,
	a:hover .entry-title,
	.page-title a:hover,
	a:hover .page-title:hover,
	.woocommerce ul.products li.product h3,
	.woocommerce-page ul.products li.product h3 {
		color: <?php echo $colors['content']; ?>; }
	pre,
	code,
	kbd,
	tt,
	var,
	table,
	.main-navigation li.menu-item-has-children:hover a:hover,
	.main-navigation ul ul li.page_item_has_children:hover > a,
	.entry-meta,
	.entry-footer-links,
	.comment-content,
	.sp-data-table,
	.site-footer .sp-data-table,
	.sp-table-wrapper .dataTables_paginate,
	.sp-template,
	.sp-template-countdown .event-venue,
	.sp-template-countdown .event-league,
	.sp-template-countdown .event-name a,
	.sp-template-countdown time span,
	.sp-template-details dl,
	.sp-template-event-blocks .event-title,
	.sp-template-event-blocks .event-title a,
	.sp-tournament-bracket .sp-event .sp-event-date,
	.mega-slider,
	.woocommerce .woocommerce-breadcrumb,
	.woocommerce-page .woocommerce-breadcrumb {
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
	.nav-links a,
	.comment-metadata a,
	.comment-body .reply a,
	.wp-caption-text,
	.sp-view-all-link,
	.sp-template-event-calendar #prev a,
	.sp-template-event-calendar #next a,
	.sp-template-tournament-bracket .sp-event-venue,
	.woocommerce .woocommerce-breadcrumb,
	.woocommerce-page .woocommerce-breadcrumb,
	.woocommerce .woocommerce-breadcrumb a,
	.woocommerce-page .woocommerce-breadcrumb a {
		color: <?php echo $colors['text_lighter']; ?>; }
	caption,
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"],
	.main-navigation .nav-menu > li:hover > a,
	.main-navigation.toggled .menu-toggle,
	.site-footer,
	.sp-template .gallery-caption,
	.sp-template .gallery-caption a,
	.sp-heading,
	.sp-heading:hover,
	.sp-heading a:hover,
	.sp-table-caption,
	.sp-template-event-logos .sp-team-result,
	.sp-template-tournament-bracket .sp-result,
	.single-sp_player .entry-header .entry-title strong {
		color: <?php echo $colors['heading']; ?>; }
	.main-navigation a,
	.main-navigation .menu-toggle {
		color: <?php echo $colors['heading_alpha']; ?>; }
	a,
	blockquote:before,
	q:before,
	.main-navigation ul ul .current-menu-item > a,
	.main-navigation ul ul .current-menu-parent > a,
	.main-navigation ul ul .current-menu-ancestor > a,
	.main-navigation ul ul .current_page_item > a,
	.main-navigation ul ul .current_page_parent > a,
	.main-navigation ul ul .current_page_ancestor > a,
	.main-navigation li.menu-item-has-children:hover ul .current-menu-item > a:hover,
	.main-navigation li.menu-item-has-children:hover ul .current-menu-parent > a:hover,
	.main-navigation li.menu-item-has-children:hover ul .current-menu-ancestor > a:hover,
	.main-navigation li.menu-item-has-children:hover ul .current_page_item > a:hover,
	.main-navigation li.menu-item-has-children:hover ul .current_page_parent > a:hover,
	.main-navigation li.menu-item-has-children:hover ul .current_page_ancestor > a:hover,
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
	.nav-links a:hover,
	.sticky .entry-title:before,
	.comment-metadata a:hover,
	.comment-body .reply a:hover,
	.sp-view-all-link:hover,
	.sp-template-event-calendar #prev a:hover,
	.sp-template-event-calendar #next a:hover,
	.single-sp_staff .entry-header .entry-title strong,
	.sp-message {
		color: <?php echo $colors['link']; ?>; }
	cite:before,
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"],
	.main-navigation .nav-menu > li:hover > a,
	.main-navigation .search-form .search-submit:hover,
	.nav-links .meta-nav,
	.entry-footer a,
	.sp-template-player-gallery .gallery-item strong,
	.sp-template-tournament-bracket .sp-result,
	.single-sp_player .entry-header .entry-title strong,
	.sp-statistic-bar-fill,
	.mega-slider__row--active,
	.mega-slider__row--active:hover {
		background: <?php echo $colors['link']; ?>; }
	.sp-message {
		border-color: <?php echo $colors['link']; ?>; }
	caption,
	.sp-table-caption,
	.opta-widget-container h2 {
		border-top-color: <?php echo $colors['link']; ?>; }
	.sp-tab-menu-item-active a {
		border-bottom-color: <?php echo $colors['link']; ?>; }
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
	input[type="submit"]:active,
	.entry-footer a:hover,
	.nav-links a:hover .meta-nav,
	.sp-template-tournament-bracket .sp-event-title:hover .sp-result {
		background: <?php echo $colors['link_dark']; ?>; }
	.widget_search .search-submit {
		border-color: <?php echo $colors['link_dark']; ?>; }
	a:hover {
		color: <?php echo $colors['link_hover']; ?>; }
	.sp-template-event-logos {
		color: inherit; }
	.sp-footer-sponsors .sp-sponsors {
		border-color: <?php echo $colors['sponsors_border']; ?>; }
	@media screen and (max-width: 600px) {
		.main-navigation .nav-menu > li:hover > a,
		.main-navigation ul ul li.page_item_has_children:hover > a {
			color: <?php echo $colors['heading']; ?>;
			background: transparent; }
		.main-navigation .nav-menu li a:hover,
		.main-navigation .search-form .search-submit {
			color: <?php echo $colors['heading']; ?>;
			background: <?php echo $colors['link']; ?>; }
		.main-navigation .nav-menu > .menu-item-has-children:hover > a,
		.main-navigation li.menu-item-has-children:hover a {
			background: transparent; }
		.main-navigation ul ul {
			background: rgba(0, 0, 0, 0.1); }
		.main-navigation .nav-menu > .menu-item-has-children:hover > a:hover,
		.main-navigation li.menu-item-has-children:hover a:hover {
			background: <?php echo $colors['link']; ?>;
			color: #fff;
		}
		.main-navigation ul ul a,
		.main-navigation .nav-menu > .menu-item-has-children:hover > a {
			color: <?php echo $colors['heading_alpha']; ?>; }
		.main-navigation .nav-menu > .current-menu-item > a,
		.main-navigation .nav-menu > .current-menu-parent > a,
		.main-navigation .nav-menu > .current-menu-ancestor > a,
		.main-navigation .nav-menu > .current_page_item > a,
		.main-navigation .nav-menu > .current_page_parent > a,
		.main-navigation .nav-menu > .current_page_ancestor > a,
		.main-navigation .nav-menu > .current-menu-item:hover > a,
		.main-navigation .nav-menu > .current-menu-parent:hover > a,
		.main-navigation .nav-menu > .current-menu-ancestor:hover > a,
		.main-navigation .nav-menu > .current_page_item:hover > a,
		.main-navigation .nav-menu > .current_page_parent:hover > a,
		.main-navigation .nav-menu > .current_page_ancestor:hover > a,
		.main-navigation ul ul .current-menu-parent > a,
		.main-navigation ul ul .current-menu-ancestor > a,
		.main-navigation ul ul .current_page_parent > a,
		.main-navigation ul ul .current_page_ancestor > a,
		.main-navigation li.menu-item-has-children:hover ul .current-menu-item > a:hover,
		.main-navigation li.menu-item-has-children:hover ul .current-menu-parent > a:hover,
		.main-navigation li.menu-item-has-children:hover ul .current-menu-ancestor > a:hover,
		.main-navigation li.menu-item-has-children:hover ul .current_page_item > a:hover,
		.main-navigation li.menu-item-has-children:hover ul .current_page_parent > a:hover,
		.main-navigation li.menu-item-has-children:hover ul .current_page_ancestor > a:hover {
			color: #fff;
		}
	}
	@media screen and (min-width: 601px) {
		.content-area,
		.widecolumn {
			box-shadow: 1px 0 0 <?php echo $colors['content_border']; ?>;
		}
		.widget-area {
			box-shadow: inset 1px 0 0 <?php echo $colors['content_border']; ?>; }
		.widget-area-left {
			box-shadow: inset -1px 0 0 <?php echo $colors['content_border']; ?>; }
		.rtl .content-area,
		.rtl .widecolumn {
			box-shadow: -1px 0 0 <?php echo $colors['content_border']; ?>;
		}

		.rtl .widget-area,
		.rtl .widget-area-left {
			box-shadow: inset -1px 0 0 <?php echo $colors['content_border']; ?>; }
		.rtl .widget-area-right {
			box-shadow: inset 1px 0 0 <?php echo $colors['content_border']; ?>; }
	}
	@media screen and (max-width: 1199px) {
		.social-sidebar {
			box-shadow: inset 0 1px 0 <?php echo $colors['content_border']; ?>; }
	}

	<?php do_action( 'sportspress_frontend_css', $colors ); ?>

	</style>
	<?php
}
endif;

if ( is_admin() ):
	require_once get_template_directory() . '/inc/admin.php';
endif;

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
* Include the TGMPA class.
*/
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

/**
 * Move SportsPress header sponsors selector.
 */
if ( ! function_exists( 'rookie_header_sponsors' ) ):
function rookie_header_sponsors() {
	return '.site-branding hgroup';
}
add_filter( 'sportspress_header_sponsors_selector', 'rookie_header_sponsors' );
endif;

/**
 * Display footer elements
 */
if ( ! function_exists( 'rookie_footer' ) ):
function rookie_footer() {
	rookie_footer_copyright();
	rookie_footer_credit();
}
endif;

/**
 * Display footer copyright notice
 */
if ( ! function_exists( 'rookie_footer_copyright' ) ):
function rookie_footer_copyright() {
	?>
	<div class="site-copyright">
		<?php echo apply_filters( 'rookie_footer_copyright', sprintf( _x( '&copy; %1$s %2$s', 'copyright info', 'rookie' ), date( 'Y' ), get_bloginfo( 'name' ) ) ); ?>
	</div><!-- .site-copyright -->
	<?php
}
endif;

/**
 * Display footer credit
 */
if ( ! function_exists( 'rookie_footer_credit' ) ):
function rookie_footer_credit() {
	?>
	<div class="site-credit">
		<?php echo apply_filters( 'rookie_footer_credit', '<a href="http://themeboy.com/">' . sprintf( __( 'Designed by %s', 'rookie' ), 'ThemeBoy' ) . '</a>' ); ?>
	</div><!-- .site-info -->
	<?php
}
endif;

function rookie_register_required_plugins() {
	$plugins = array(
		array(
			'name'      => 'SportsPress',
			'slug'      => 'sportspress',
			'required'  => true,
			'is_callable' => array( 'SportsPress', 'instance' ),
		),
	);

	$config = array(
		'id' => 'rookie',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'is_automatic' => true,
		'message'      => '',
		'strings'      => array(
			'nag_type' => 'updated'
		)
	);

	$plugins = apply_filters( 'rookie_required_plugins', $plugins );

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'rookie_register_required_plugins' );

/**
 * Disable default gallery style
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Helper functions
 */

/**
 * Sanitizes a hex color. Identical to core's sanitize_hex_color(), which is not available on the wp_head hook.
 *
 * Returns either '', a 3 or 6 digit hex color (with #), or null.
 * For sanitizing values without a #, see sanitize_hex_color_no_hash().
 */
if ( ! function_exists( 'rookie_sanitize_hex_color' ) ) {
    function rookie_sanitize_hex_color( $color ) {
        if ( '' === $color )
            return '';

        // 3 or 6 hex digits, or the empty string.
        if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
            return $color;

        return null;
    }
}

/**
 * Sanitizes a checkbox option. Defaults to 'no'.
 */
if ( ! function_exists( 'rookie_sanitize_checkbox' ) ) {
    function rookie_sanitize_checkbox( $value ) {
    	return true == $value;
    }
}

/**
 * Sanitizes a radio option. Defaults to setting default from customize API.
 */
if ( ! function_exists( 'rookie_sanitize_choices' ) ) {
    function rookie_sanitize_choices( $value, $setting ) {
    	global $wp_customize;
    	
    	$control = $wp_customize->get_control( $setting->id );
    	
    	return $value;

    	if ( array_key_exists( $value, $control->choices ) ) {
	        return $value;
	    } else {
        	return $setting->default;
    	}
    }
}

/**
 * Sanitizes content width option. Defaults to 1000.
 */
if ( ! function_exists( 'rookie_sanitize_content_width' ) ) {
    function rookie_sanitize_content_width( $value ) {
    	$value = absint( $value );
    	if ( 500 > $value ) {
    		$value = 1000;
    	}
    	return round( $value, -1 );
    }
}

/**
 * Sanitizes a header image style option. Defaults to first element in options array.
 */
if ( ! function_exists( 'rookie_sanitize_header_image_style' ) ) {
    function rookie_sanitize_header_image_style( $value ) {
		$style_options = apply_filters( 'rookie_header_image_style_options', array(
	        'background' => __( 'Background', 'rookie' ),
	        'image' => __( 'Image', 'rookie' ),
	    ) );
		
		// Return given value if it's a valid option
		if ( array_key_exists( $value, $style_options ) ) {
			return $value;
		}
	
		// Otherwise, return the first valid option
		reset( $style_options );
		$value = key( $style_options );
		return $value;
    }
}

/**
 * Define pages for starter content.
 */
if ( ! function_exists( 'rookie_starter_content_posts' ) ) {
	function rookie_starter_content_posts() {
		$posts = array(
			'home',
			'blog',
		);

		if ( class_exists( 'SportsPress' ) ) {
			$tables = (array) get_posts("post_type=sp_table&numberposts=1&fields=ids");
			$calendars = (array) get_posts("post_type=sp_calendar&numberposts=1&fields=ids");
			$lists = (array) get_posts("post_type=sp_list&numberposts=1&fields=ids");

			$posts['fixtures-results'] = array(
				'post_type' => 'page',
				'post_title' => __( 'Fixtures & Results', 'rookie' ),
				'post_content' => wp_strip_all_tags( get_post_field( 'post_content', reset( $calendars ) ) ) .
					'[event_blocks title="' . __( 'Fixtures', 'rookie' ) . '" status="future" date="" order="ASC" number="3" show_all_events_link="0" align="left"]' .
					'[event_blocks title="' . __( 'Results', 'rookie' ) . '" status="publish" date="" order="DESC" number="3" show_all_events_link="0" align="right"]' .
					'[event_calendar show_all_events_link="0"]' .
					'[event_list ' . reset( $calendars ) . ' title="Event List" columns="event,teams,time" number="5" show_all_events_link="1"]',
			);
			$posts['league-table'] = array(
				'post_type' => 'page',
				'post_title' => __( 'League Table', 'rookie' ),
				'post_content' => wp_strip_all_tags( get_post_field( 'post_content', reset( $tables ) ) ) .
					'[league_table ' . reset( $tables ) . ']',
			);
			$posts['roster'] = array(
				'post_type' => 'page',
				'post_title' => __( 'Roster', 'rookie' ),
				'post_content' => wp_strip_all_tags( get_post_field( 'post_content', reset( $lists ) ) ) .
					'[player_gallery ' . reset( $lists ) . ' orderby="number" show_all_players_link="0"]',
			);
			$posts['home']['post_content'] = '';
		} else {
			$tgmpa = new TGM_Plugin_Activation();
			$tgmpa->init();
			if ( isset( $tgmpa->strings['notice_cannot_install_activate'] ) ) {
				$posts['home']['post_content'] = wp_kses_post( $tgmpa->strings['notice_cannot_install_activate'] );
			}
		}

		return $posts;
	}
}

if ( ! function_exists( 'rookie_get_sidebar_setting' ) ) {
    function rookie_get_sidebar_setting() {
		// Get theme options
		$options = (array) get_option( 'themeboy', array() );
		$options = array_map( 'esc_attr', $options );

		// Apply default setting
		if ( empty( $options['sidebar'] ) ) {
		    $options['sidebar'] = is_rtl() ? 'left' : 'right';
		}
		
		return $options['sidebar'];
	}
}

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
		$r = str_pad( dechex( ( $rgbx['r'] + $rgby['r'] ) / 2 ), 2, '0', STR_PAD_LEFT );
		$g = str_pad( dechex( ( $rgbx['g'] + $rgby['g'] ) / 2 ), 2, '0', STR_PAD_LEFT );
		$b = str_pad( dechex( ( $rgbx['b'] + $rgby['b'] ) / 2 ), 2, '0', STR_PAD_LEFT );
		return '#' . $r . $g . $b;
	}
}

/**
 * Detect the brightness of a hex color
 * Adapted from http://www.webmasterworld.com/forum88/9769.htm
 */
if ( ! function_exists( 'rookie_hex_brightness' ) ) {
	function rookie_hex_brightness( $color = 'ffffff' ) {
		$color = str_replace( '#', '', $color );
		$rgb = rookie_rgb_from_hex( $color );

		return ( ( $rgb['r'] * 0.299 ) + ( $rgb['g'] * 0.587 ) + ( $rgb['b'] * 0.114 ) );
	}
}