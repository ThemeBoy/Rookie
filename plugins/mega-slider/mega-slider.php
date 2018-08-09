<?php
/*
Plugin Name: Mega Slider
Plugin URI: http://megaslider.com/
Description: Create custom image sliders.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.0.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Mega_Slider' ) ) :

/**
 * Main Mega_Slider class
 *
 * @class Mega_Slider
 * @version	1.0.1
 */
class Mega_Slider {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'init', array( $this, 'init' ), 15 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'customize_register', array( $this, 'customizer' ) );
		add_action( 'mega_slider', array( $this, 'template' ) );
		add_filter( 'post_updated_messages', array( $this, 'messages' ) );
		add_filter( 'widget_text', array( $this, 'widget_text' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_head', array( $this, 'add_shortcode_button' ), 11 );
		add_filter( 'tiny_mce_version', array( $this, 'refresh_mce' ) );
		add_filter( 'mce_external_languages', array( $this, 'add_tinymce_lang' ), 11, 1 );
		add_action( 'wp_ajax_mega_slider', array( $this, 'ajax' ) );
		add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );
		add_shortcode( 'mega_slider', array( $this, 'shortcode' ) );
		add_shortcode( 'mega-slider', array( $this, 'shortcode' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'MEGA_SLIDER_VERSION' ) )
			define( 'MEGA_SLIDER_VERSION', '1.0.1' );

		if ( defined( 'THEMEBOY_FILE' ) ) {
			if ( !defined( 'MEGA_SLIDER_URL' ) )
				define( 'MEGA_SLIDER_URL', get_template_directory_uri() . '/plugins/mega-slider/' );

			if ( !defined( 'MEGA_SLIDER_DIR' ) )
				define( 'MEGA_SLIDER_DIR', get_template_directory() . '/plugins/mega-slider/' );
		} else {
			if ( !defined( 'MEGA_SLIDER_URL' ) )
				define( 'MEGA_SLIDER_URL', plugin_dir_url( __FILE__ ) );

			if ( !defined( 'MEGA_SLIDER_DIR' ) )
				define( 'MEGA_SLIDER_DIR', plugin_dir_path( __FILE__ ) );
		}
	}

	/**
	 * Enqueue styles
	 */
	public function styles() {
		wp_enqueue_style( 'mega-slider-style', trailingslashit( MEGA_SLIDER_URL ) . 'assets/css/mega-slider.css', array(), MEGA_SLIDER_VERSION );

		if ( is_rtl() )
			wp_enqueue_style( 'mega-slider-rtl-style', trailingslashit( MEGA_SLIDER_URL ) . 'assets/css/mega-slider-rtl.css', array(), MEGA_SLIDER_VERSION );
	}

	/**
	 * Enqueue scripts
	 */
	public function scripts() {
		wp_register_script( 'mega-slider', trailingslashit( MEGA_SLIDER_URL ) . 'assets/js/mega-slider.js', array( 'jquery' ), MEGA_SLIDER_VERSION, true );
		wp_enqueue_script( 'mega-slider' );
	}
	
	/**
	 * Template slider
	 */
	public function template() {
		$id = get_option( 'mega-slider' );
		if ( ! $id ) return;
		if ( 'publish' !== get_post_status( $id ) ) return;
		echo '<div class="mega-slider-template">';
			self::slider( $id );
		echo '</div>';
	}

	/**
	 * Register post type
	 */
	public static function init() {
		register_post_type( 'mega-slider',
			apply_filters( 'mega-slider_register_post_type',
				array(
					'labels' => array(
						'name' 					=> __( 'Image Sliders', 'mega-slider' ),
						'singular_name' 		=> __( 'Image Slider', 'mega-slider' ),
						'all_items' 			=> __( 'Image Sliders', 'mega-slider' ),
						'add_new_item' 			=> __( 'Add New Image Slider', 'mega-slider' ),
						'edit_item' 			=> __( 'Edit Image Slider', 'mega-slider' ),
						'new_item' 				=> __( 'New Image Slider', 'mega-slider' ),
						'view_item' 			=> __( 'View Image Slider', 'mega-slider' ),
						'search_items' 			=> __( 'Search', 'mega-slider' ),
						'not_found' 			=> __( 'No results found.', 'mega-slider' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'mega-slider' ),
					),
					'taxonomies' 			=> array( 'category' ),
					'public' 				=> false,
					'show_ui' 				=> true,
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'mega-slider_slug', 'slider' ) ),
					'supports' 				=> array( 'title', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'upload.php',
					'show_in_admin_bar' 	=> true,
				)
			)
		);
		register_taxonomy_for_object_type( 'category', 'mega-slider' );
	}

	public static function customizer( $wp_customize ) {
		if ( ! current_theme_supports( 'mega-slider' ) ) return;
		
		$args = array(
			'post_type' => 'mega-slider',
			'numberposts' => 500,
			'posts_per_page' => 500,
		);

		$sliders = get_posts( $args );
		
		if ( ! $sliders || ! is_array( $sliders ) || ! sizeof( $sliders ) ) return;
		
		$choices = array(
			0 => __( '&mdash; Select &mdash;', 'mega-slider' ),
		);
		foreach ( $sliders as $slider ) {
			$choices[ $slider->ID ] = $slider->post_title;
		}
		
		$wp_customize->add_setting( 'mega-slider', array(
			'default' 		=> 0,
			'capability' 	=> 'edit_theme_options',
			'type' 			=> 'option',
		) );

		$wp_customize->add_control( 'mega-slider', array(
			'label' 	=> __( 'Image Slider', 'mega-slider' ),
			'section' 	=> 'static_front_page',
			'settings' 	=> 'mega-slider',
			'type' 		=> 'select',
			'choices' 	=> $choices,
		) );
	}

	/**
	 * Add meta boxes
	 */
	public static function add_meta_boxes() {
		remove_meta_box( 'postimagediv', 'mega-slider', 'side' );
		add_meta_box( 'postimagediv', __( 'Default Image', 'mega-slider' ), 'post_thumbnail_meta_box', 'mega-slider', 'side', 'low' );
		add_meta_box( 'mega-slider_div', __( 'Preview', 'mega-slider' ), 'Mega_Slider::preview_meta_box', 'mega-slider', 'normal', 'high' );
		add_meta_box( 'mega-slider_shortcode_div', __( 'Shortcode', 'mega-slider' ), 'Mega_Slider::shortcode_meta_box', 'mega-slider', 'side', 'default' );
		add_meta_box( 'mega-slider_settings_div', __( 'Settings', 'mega-slider' ), 'Mega_Slider::settings_meta_box', 'mega-slider', 'side', 'default' );
	}

	/**
	 * Preview meta box content
	 */
	public static function preview_meta_box( $post ) {
		self::slider( $post->ID );
	}

	/**
	 * Shortcode meta box content
	 */
	public static function shortcode_meta_box( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'mega-slider' ); ?>
		</p>
		<p>
			<input type="text" value="[mega_slider <?php echo $post->ID; ?>]" readonly="readonly" class="code widefat">
		</p>
		<?php
	}

	/**
	 * Settings meta box content
	 */
	public static function settings_meta_box( $post ) {
		wp_nonce_field( 'mega-slider_meta_box', 'mega-slider_meta_box_nonce' );

		// Get limit settings
		$limit = get_post_meta( $post->ID, 'mega-slider_limit', true );
		if ( ! $limit ) $limit = 5;

		// Get autoplay settings
		$autoplay = get_post_meta( $post->ID, 'mega-slider_autoplay', true );
		$delay = get_post_meta( $post->ID, 'mega-slider_delay', true );
		if ( ! $delay ) $delay = 5;
		?>
		<p>
			<strong>
				<?php _e( 'Limit', 'mega-slider' ); ?>
			</strong>
		</p>
		<label class="screen-reader-text" for="mega-slider_limit"><?php _e( 'Limit', 'mega-slider' ); ?></label>
		<input name="mega-slider_limit" id="mega-slider_limit" type="number" step="1" min="1" value="<?php echo $limit; ?>" class="small-text">
		<?php _e( 'posts', 'mega-slider' ); ?>
		<p>
			<strong>
				<?php _e( 'Autoplay', 'mega-slider' ); ?>
			</strong>
		</p>
		<input name="mega-slider_autoplay" id="mega-slider_autoplay" type="checkbox" value="1" <?php checked( $autoplay ); ?>>
		<?php printf( __( 'Animate every %s seconds', 'mega-slider' ), '<input name="mega-slider_delay" id="mega-slider_delay" type="number" step="1" min="1" value="' . $delay . '" class="small-text">' ); ?>
		<?php
	}

	/**
	 * Shortcode callback
	 */
	public static function shortcode( $atts = array() ) {
		$id = reset( $atts );
		ob_start();
		self::slider( $id );
		return ob_get_clean();
	}

	/**
	 * Slider output
	 */
	public static function slider( $id ) {
		$args = array(
			'posts_per_page' => 5,
		);

		if ( $id ) {
			$categories = wp_get_post_categories( $id );
			if ( $categories ) {
				$args['category'] = implode( ',', $categories );
			}

			$limit = get_post_meta( $id, 'mega-slider_limit', true );
			if ( $limit ) {
				$args['posts_per_page'] = $limit;
			}
		}

		$slides = get_posts( $args );

		if ( $slides ) {
			$autoplay = get_post_meta( $id, 'mega-slider_autoplay', true );
			$delay = get_post_meta( $id, 'mega-slider_delay', true );
			if ( ! isset( $delay ) ) $delay = 5;

			$date_format = get_option( 'date_format' );
			?>
			<div class="mega-slider" data-autoplay="<?php echo $autoplay; ?>" data-delay="<?php echo $delay; ?>">
				<div class="mega-slider__stage">
					<?php foreach ( $slides as $i => $slide ) { ?>
						<?php if ( has_post_thumbnail( $slide->ID ) ) { ?>
							<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $slide->ID ), 'large' ); ?>
						<?php } else { ?>
							<?php $thumb = (array) wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'large' ); ?>
						<?php } ?>
						<a href="<?php echo get_post_permalink( $slide->ID, false, true ); ?>" class="mega-slider__slide<?php if ( 0 == $i ) { ?> mega-slider__slide--active<?php } ?>" style="background-image: url(<?php echo $thumb[0]; ?>); display: <?php if ( 0 == $i ) { ?>block<?php } else { ?>none<?php } ?>;">
							<span class="mega-slider__slide__label">
								<span class="mega-slider__slide__date"><?php echo get_the_date( $date_format, $slide->ID ); ?></span>
								<span class="mega-slider__slide__title"><?php echo get_the_title( $slide->ID ); ?></span>
							</span>
						</a>
					<?php } ?>
				</div>
				<div class="mega-slider__sidebar">
					<?php foreach ( $slides as $i => $slide ) { ?>
						<div class="mega-slider__row<?php if ( 0 == $i ) { ?> mega-slider__row--active<?php } ?>">
							<?php if ( has_post_thumbnail( $slide->ID ) ) { ?>
								<?php echo get_the_post_thumbnail( $slide->ID, 'thumbnail', array( 'class' => 'mega-slider__row__thumbnail' ) ); ?>
							<?php } else { ?>
								<?php echo get_the_post_thumbnail( $id, 'thumbnail', array( 'class' => 'mega-slider__row__thumbnail' ) ); ?>
							<?php } ?>
							<span class="mega-slider__row__label">
								<span class="mega-slider__row__title"><?php echo get_the_title( $slide->ID ); ?></span>
								<span class="mega-slider__row__date"><?php echo get_the_date( $date_format, $slide->ID ); ?></span>
								<a class="mega-slider__row__link" href="<?php echo get_post_permalink( $slide->ID, false, true ); ?>"><?php _e( 'Read more...', 'mega-slider' ); ?></a>
							</span>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Save slider meta
	 */
	public static function save_meta( $post_id ) {
		if ( ! isset( $_POST['mega-slider_meta_box_nonce'] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['mega-slider_meta_box_nonce'], 'mega-slider_meta_box' ) )
			return;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! isset( $_POST['post_type'] ) || 'mega-slider' != $_POST['post_type'] )
			return;

		$limit = sanitize_text_field( $_POST['mega-slider_limit'] );
		update_post_meta( $post_id, 'mega-slider_limit', $limit );

		$autoplay = isset( $_POST['mega-slider_autoplay'] );
		update_post_meta( $post_id, 'mega-slider_autoplay', $autoplay );

		$delay = sanitize_text_field( $_POST['mega-slider_delay'] );
		update_post_meta( $post_id, 'mega-slider_delay', $delay );
	}

	/**
	 * Post updated messages
	 */
	public static function messages( $messages ) {
		global $typenow, $post;

		if ( 'mega-slider' == $typenow ) {
			$obj = get_post_type_object( $typenow );

			for ( $i = 0; $i <= 10; $i++ ):
				$messages['post'][ $i ] = __( 'Changes saved.', 'mega-slider' );
			endfor;
		}

		return $messages;
	}

	/**
	 * Text widget filder
	 */
	public static function widget_text( $content ) {
		if ( ! preg_match( '/\[[\r\n\t ]*(mega-slider)?[\r\n\t ].*?\]/', $content ) )
			return $content;

		$content = do_shortcode( $content );

		return $content;
	}

	/**
	 * Enqueue admin styles
	 */
	public function admin_styles() {
		wp_enqueue_style( 'mega-slider-admin', MEGA_SLIDER_URL . 'assets/css/mega-slider-admin.css', array(), MEGA_SLIDER_VERSION );
	}

	/**
	 * Add a button for shortcodes to the WP editor.
	 */
	public function add_shortcode_button() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_shortcode_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_shortcode_button' ) );
		}
	}

	/**
	 * add_tinymce_lang function.
	 *
	 * @param array $arr
	 * @return array
	 */
	public function add_tinymce_lang( $arr ) {
	    $arr['mega_slider_button'] = MEGA_SLIDER_DIR . 'assets/js/editor-lang.php';
	    return $arr;
	}

	/**
	 * Register the shortcode button.
	 *
	 * @param array $buttons
	 * @return array
	 */
	public function register_shortcode_button( $buttons ) {
		array_push( $buttons, 'mega_slider_button' );
		return $buttons;
	}

	/**
	 * Add the shortcode button to TinyMCE
	 *
	 * @param array $plugin_array
	 * @return array
	 */
	public function add_shortcode_tinymce_plugin( $plugin_array ) {
		$plugin_array['mega_slider_button'] = MEGA_SLIDER_URL . 'assets/js/editor.js';
		return $plugin_array;
	}

	/**
	 * Force TinyMCE to refresh.
	 *
	 * @param int $ver
	 * @return int
	 */
	public function refresh_mce( $ver ) {
		$ver += 3;
		return $ver;
	}

	/**
	 * Ajax options window.
	 */
	public function ajax() {
		?>
		<div class="wrap mega-slider-thickbox-content" id="mega-slider-thickbox">
			<form>
				<?php
				$args = array(
					'post_type' => 'mega-slider',
					'numberposts' => 500,
					'posts_per_page' => 500,
				);

				$sliders = get_posts( $args );
				
				if ( ! $sliders || ! is_array( $sliders ) || ! sizeof( $sliders ) ) {
					?>
					<p><?php _e( 'No results found.', 'mega-slider' ); ?><br>
					<a href="<?php echo add_query_arg( 'post_type', 'mega-slider', admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Image Slider', 'mega-slider' ); ?></a>
					<?php
				} else {
				?>
				<table class="mega-slider-settings form-table">
					<tbody>
						<tr>
							<th scope="row"><?php _e( 'Image Slider:' ); ?></th>
							<td>
								<select name="slider_id">
								<?php
								foreach ( $sliders as $slider ) {
									?>
									<option value="<?php echo $slider->ID; ?>"><?php echo $slider->post_title; ?></option>
									<?php
								}
								?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="button" class="button-primary" value="<?php _e( 'Insert Image Slider', 'mega-slider' ); ?>" onclick="insert_mega_slider();" />
					<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel' ); ?>"><?php _e( 'Cancel' ); ?></a>
				</p>
				<?php } ?>
			</form>
		</div>
		<?php
		$this->ajax_scripts();
		die();
	}

	/**
	 * Ajax scripts.
	 */
	public function ajax_scripts() {
		?>
		<script type="text/javascript">
		function insert_mega_slider() {
			var $div = jQuery('.mega-slider-thickbox-content');

			// Generate the shortcode
			var shortcode = '[mega_slider ' + $div.find('[name=slider_id]').val() + ']';

			// Send the shortcode to the editor
			window.send_to_editor( shortcode );
		}
		</script>
		<?php
	}

	/**
	 * Disable requests to wp.org repository for this plugin.
	 *
	 * @since 1.0.1
	 */
	function disable_wporg_request( $r, $url ) {
		if ( 0 === strpos( $url, 'https://api.wordpress.org/plugins/update-check/1.1/' ) ) {
			$plugins = json_decode( $r['body']['plugins'], true );
			unset( $plugins['plugins'][plugin_basename( __FILE__ )] );
			$r['body']['plugins'] = json_encode( $plugins );
		}
		return $r;
	}
}

new Mega_Slider();

endif;
