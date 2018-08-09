<?php
/**
 * Rookie Plus functions and definitions
 *
 * @package Rookie Plus
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 653; /* pixels */
}

if ( ! isset( $full_content_width ) ) {
	$full_content_width = 990; /* pixels */
}

if ( ! class_exists( 'ThemeBoy_Rookie_Plus' ) ) :

class ThemeBoy_Rookie_Plus {

	/**
	 * @var string
	 */
	public $version = '1.2';

	/**
	 * @var string
	 */
	public $slug = 'marquee';

	/**
	 * @var string
	 */
	public $name = 'Rookie Plus';

	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Include plugins
		$this->include_plugins();

		// Hooks
		add_action( 'rookie_customize_register', array( $this, 'customize_register' ) );
		add_filter( 'rookie_footer_copyright', array( $this, 'footer_copyright' ), 20 );
		add_filter( 'rookie_footer_credit', array( $this, 'footer_credit' ), 20 );
		add_action( 'after_setup_theme', array( $this, 'updater' ) );
	}

	/**
	 * Define ThemeBoy Constants.
	 */
	private function define_constants() {
		define( 'THEMEBOY_FILE', __FILE__ );
		define( 'THEMEBOY_VERSION', $this->version );
		define( 'THEMEBOY_SLUG', $this->slug );
		define( 'THEMEBOY_NAME', $this->name );
	}

	/**
	 * Include plugins.
	 */
	private function include_plugins() {
		include_once get_template_directory() . '/plugins/mega-slider/mega-slider.php';
		include_once get_template_directory() . '/plugins/news-widget/news-widget.php';
		include_once get_template_directory() . '/plugins/social-sidebar/social-sidebar.php';
	}

	public function customize_register( $wp_customize ) {
	    /*
	     * Footer Section
	     */
	    $wp_customize->add_section( 'rookie_footer' , array(
	        'title'      => __( 'Footer', 'rookie' ),
	    ) );

	    /**
	     * Copyright
	     */
	    $wp_customize->add_setting( 'themeboy[footer_copyright]', array(
	        'default'       => '',
	        'sanitize_callback' => 'sanitize_text_field',
	        'capability'    => 'edit_theme_options',
	        'type'          => 'option',
	    ) );

	    $wp_customize->add_control( 'themeboy_footer_copyright', array(
	        'label'     => __('Copyright', 'rookie'),
	        'section'   => 'rookie_footer',
	        'settings'  => 'themeboy[footer_copyright]',
	        'input_attrs' => array(
	        	'placeholder' => sprintf( _x( '&copy; %1$s %2$s', 'copyright info', 'rookie' ), date( 'Y' ), get_bloginfo( 'name' ) ),
	        ),
	    ) );

	    /**
	     * Credit
	     */
	    $wp_customize->add_setting( 'themeboy[footer_credit]', array(
	        'default'       => '',
	        'sanitize_callback' => 'sanitize_text_field',
	        'capability'    => 'edit_theme_options',
	        'type'          => 'option',
	    ) );

	    $wp_customize->add_control( 'themeboy_footer_credit', array(
	        'label'     => __('Credit', 'rookie'),
	        'section'   => 'rookie_footer',
	        'settings'  => 'themeboy[footer_credit]',
	        'input_attrs' => array(
	        	'placeholder' => sprintf( __( 'Designed by %s', 'rookie' ), 'ThemeBoy' ),
	        ),
	    ) );

	    /**
	     * Link URL
	     */
	    $wp_customize->add_setting( 'themeboy[footer_link_url]', array(
	        'default'       => '',
	        'sanitize_callback' => 'esc_url',
	        'capability'    => 'edit_theme_options',
	        'type'          => 'option',
	    ) );

	    $wp_customize->add_control( 'themeboy_footer_link_url', array(
	        'label'     => __('Link URL', 'rookie'),
	        'section'   => 'rookie_footer',
	        'settings'  => 'themeboy[footer_link_url]',
	        'input_attrs' => array(
	        	'placeholder' => 'http://themeboy.com/',
	        ),
	    ) );
    
    	$wp_customize->remove_setting( 'themeboy[content_background]' );
	}
	
	public function footer_copyright( $copyright ) {
		$options = (array) get_option( 'themeboy', array() );

		// Return if not customized
		if ( ! isset( $options['footer_copyright'] ) || '' == $options['footer_copyright'] ) {
			return $copyright;
		} else {
			return $options['footer_copyright'];
		}
	}
	
	public function footer_credit( $credit ) {
		$options = (array) get_option( 'themeboy', array() );

		// Return if not customized
		if ( ( ! isset( $options['footer_credit'] ) || '' == $options['footer_credit'] ) && ( ! isset( $options['footer_link_url'] ) || '' == $options['footer_link_url'] ) ) {
			return $credit;
		} else {
			$text = sprintf( __( 'Designed by %s', 'rookie' ), 'ThemeBoy' );
			$url = 'http://themeboy.com/';
			
			if ( isset( $options['footer_credit'] ) && '' !== $options['footer_credit'] ) {
				$text = $options['footer_credit'];
			}
			
			if ( isset( $options['footer_link_url'] ) && '' !== $options['footer_link_url'] ) {
				$url = $options['footer_link_url'];
			}
			
			return '<a href="' . $url . '">' . $text . '</a>';
		}
	}

	public function updater() {
		require_once( 'updater/themeboy-updater.php' );
	}
}

new ThemeBoy_Rookie_Plus();

endif;

require_once( 'framework.php' );
