<?php
/**
 * Rookie Theme Customizer
 *
 * @package Rookie
 */

if ( ! function_exists( 'rookie_customize_register' ) ) :
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function rookie_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport            = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport     = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport    = 'postMessage';

    /**
     * Logo Image
     */
    $wp_customize->add_setting( 'themeboy[logo_url]', array(
        'sanitize_callback' => 'esc_url',
        'capability'    => 'edit_theme_options',
        'type'          => 'option',
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'themeboy_logo_url', array(
        'label'     => __('Logo', 'rookie'),
        'section'   => 'title_tagline',
        'settings' => 'themeboy[logo_url]',
    )));

    /**
     * Navigation Menu Search
     */
    $wp_customize->add_setting( 'themeboy[nav_menu_search]', array(
        'default'       => 'yes',
        'sanitize_callback' => 'rookie_sanitize_checkbox',
        'capability'    => 'edit_theme_options',
        'type'          => 'option',
    ) );
    $wp_customize->add_control( 'themeboy_nav_menu_search', array(
        'label'     => __('Display Search Form', 'rookie'),
        'section'   => 'title_tagline',
        'settings'  => 'themeboy[nav_menu_search]',
        'type'      => 'checkbox',
        'std'       => 'yes'
    ) );

    /**
     * Content Text Color
     */
    $wp_customize->add_setting( 'themeboy[content]', array(
        'default'           => apply_filters( 'rookie_default_content_color', '#222222' ),
        'sanitize_callback' => 'rookie_sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'              => 'option',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'themeboy_content', array(
        'label'    => __('Content Text Color', 'rookie'),
        'section'  => 'colors',
        'settings' => 'themeboy[content]',
    ) ) );

    /**
     * Content Background Color
     */
    $wp_customize->add_setting( 'themeboy[content_background]', array(
        'default'           => apply_filters( 'rookie_default_content_background_color', '#ffffff' ),
        'sanitize_callback' => 'rookie_sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'              => 'option',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'themeboy_content_background', array(
        'label'    => __('Content Background Color', 'rookie'),
        'section'  => 'colors',
        'settings' => 'themeboy[content_background]',
    ) ) );

    /**
     * Customize colors
     */
    $wp_customize->add_setting( 'themeboy[customize]', array(
        'default'       => ( 'yes' == get_option( 'sportspress_enable_frontend_css', 'no' ) ),
        'sanitize_callback' => 'rookie_sanitize_checkbox',
        'capability'    => 'edit_theme_options',
        'type'          => 'option',
        'transport'   => 'refresh',
    ) );

    $wp_customize->add_control( 'themeboy_customize', array(
        'label'     => __( 'Customize', 'rookie' ),
        'section'   => 'colors',
        'settings'  => 'themeboy[customize]',
        'type'      => 'checkbox',
        'std'       => 'no'
    ) );

    /**
     * Primary Color
     */
    $wp_customize->add_setting( 'themeboy[primary]', array(
        'default'           => apply_filters( 'rookie_default_primary_color', '#2b353e' ),
        'sanitize_callback' => 'rookie_sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'              => 'option',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'themeboy_primary', array(
        'label'    => __('Primary Color', 'rookie'),
        'section'  => 'colors',
        'settings' => 'themeboy[primary]',
    ) ) );

    /**
     * Link Color
     */
    $wp_customize->add_setting( 'themeboy[link]', array(
        'default'           => apply_filters( 'rookie_default_link_color', '#00a69c' ),
        'sanitize_callback' => 'rookie_sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'              => 'option',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'themeboy_link', array(
        'label'    => __('Link Color', 'rookie'),
        'section'  => 'colors',
        'settings' => 'themeboy[link]',
    ) ) );

    /**
     * Text Color
     */
    $wp_customize->add_setting( 'themeboy[text]', array(
        'default'           => apply_filters( 'rookie_default_text_color', '#222222' ),
        'sanitize_callback' => 'rookie_sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'              => 'option',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'themeboy_text', array(
        'label'    => __('Text Color', 'rookie'),
        'section'  => 'colors',
        'settings' => 'themeboy[text]',
    ) ) );

    /**
     * Widget Background Color
     */
    $wp_customize->add_setting( 'themeboy[background]', array(
        'default'           => apply_filters( 'rookie_default_background_color', '#f4f4f4' ),
        'sanitize_callback' => 'rookie_sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'              => 'option',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'themeboy_background', array(
        'label'    => __('Widget Background Color', 'rookie'),
        'section'  => 'colors',
        'settings' => 'themeboy[background]',
    ) ) );

    /**
     * Widget Heading Color
     */
    $wp_customize->add_setting( 'themeboy[heading]', array(
        'default'           => apply_filters( 'rookie_default_heading_color', '#ffffff' ),
        'sanitize_callback' => 'rookie_sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'              => 'option',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'themeboy_heading', array(
        'label'    => __('Widget Heading Color', 'rookie'),
        'section'  => 'colors',
        'settings' => 'themeboy[heading]',
    ) ) );

    /*
     * Header Image Style
     */
    $options = apply_filters( 'rookie_header_image_style_options', array(
        'background' => __( 'Background', 'rookie' ),
        'image' => __( 'Image', 'rookie' ),
    ) );
    
    if ( sizeof( $options ) > 1 ) {
        $wp_customize->add_setting( 'themeboy[header_image_style]', array(
            'default'           => 'background',
            'sanitize_callback' => 'rookie_sanitize_header_image_style',
            'capability'        => 'edit_theme_options',
            'type'              => 'option',
        ) );

        $wp_customize->add_control( 'themeboy_header_image_style', array(
            'label'     => __( 'Style', 'rookie' ),
            'section'   => 'header_image',
            'settings'  => 'themeboy[header_image_style]',
            'type'      => 'select',
            'choices'   => $options,
        ) );
    }
    
    /*
     * Posts Section
     */
    $wp_customize->add_section( 'rookie_posts' , array(
        'title'      => __( 'Posts', 'rookie' ),
    ) );

    /**
     * Display Post Date
     */
    $wp_customize->add_setting( 'themeboy[show_post_date]', array(
        'default'       => true,
        'sanitize_callback' => 'rookie_sanitize_checkbox',
        'capability'    => 'edit_theme_options',
        'type'          => 'option',
    ) );

    $wp_customize->add_control( 'themeboy_show_post_date', array(
        'label'     => __('Display post date?', 'rookie'),
        'section'   => 'rookie_posts',
        'settings'  => 'themeboy[show_post_date]',
        'type'      => 'checkbox',
        'std'       => 'yes',
    ) );

    /**
     * Display Post Author
     */
    $wp_customize->add_setting( 'themeboy[show_post_author]', array(
        'default'       => false,
        'sanitize_callback' => 'rookie_sanitize_checkbox',
        'capability'    => 'edit_theme_options',
        'type'          => 'option',
    ) );

    $wp_customize->add_control( 'themeboy_show_post_author', array(
        'label'     => __('Display post author?', 'rookie'),
        'section'   => 'rookie_posts',
        'settings'  => 'themeboy[show_post_author]',
        'type'      => 'checkbox',
        'std'       => 'no',
    ) );
    
    /*
     * Layout Section
     */
    $wp_customize->add_section( 'rookie_layout' , array(
        'title'      => __( 'Layout', 'rookie' ),
    ) );

    /**
     * Content width
     */
    $wp_customize->add_setting( 'themeboy[content_width]', array(
        'default'       => 1000,
        'sanitize_callback' => 'rookie_sanitize_content_width',
        'capability'    => 'edit_theme_options',
        'type'          => 'option',
    ) );

    $wp_customize->add_control( 'themeboy_content_width', array(
        'label'       => __('Content Width', 'rookie'),
        'description' => '<a class="button button-small" href="#minus">-</a> <span>px</span> <a class="button button-small" href="#plus">+</a>',
        'section'     => 'rookie_layout',
        'settings'    => 'themeboy[content_width]',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 1000,
            'max'  => 2000,
            'step' => 10,
        ),
    ) );

    $wp_customize->get_setting( 'themeboy[content_width]' )->transport = 'postMessage';

    /**
     * Sidebar
     */
    $wp_customize->add_setting( 'themeboy[sidebar]', array(
        'default'       => is_rtl() ? 'left' : 'right',
        'sanitize_callback' => 'rookie_sanitize_choices',
        'capability'    => 'edit_theme_options',
        'type'          => 'option',
    ) );

    $wp_customize->add_control( 'themeboy_sidebar', array(
        'label'     => __('Sidebar', 'rookie'),
        'section'   => 'rookie_layout',
        'settings'  => 'themeboy[sidebar]',
        'type'      => 'radio',
        'choices'   => array(
            '' => __( 'Default', 'rookie' ) . ' (' . ( is_rtl() ? __( 'Left', 'rookie' ) : __( 'Right', 'rookie' ) ) . ')',
            'left' => __( 'Left', 'rookie' ),
            'right' => __( 'Right', 'rookie' ),
            'double' => __( 'Both', 'rookie' ),
            'no' => __( 'None', 'rookie' ),
        ),
    ) );
    
    do_action( 'rookie_customize_register', $wp_customize );
}
add_action( 'customize_register', 'rookie_customize_register' );
endif;

if ( ! function_exists( 'rookie_customize_preview_js' ) ) :
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function rookie_customize_preview_js() {
    wp_register_script( 'rookie_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'jquery', 'customize-preview' ), '1.4', true );

    $vars = apply_filters( 'rookie_customizer_vars', array(
        'content_width_selector' => '.site-header, .site-content, .site-footer, .site-info',
        'content_width_adjustment' => 0,
    ) );
    wp_localize_script( 'rookie_customizer', 'vars', $vars );
    
    wp_enqueue_script( 'rookie_customizer' );
}
add_action( 'customize_preview_init', 'rookie_customize_preview_js' );
endif;
