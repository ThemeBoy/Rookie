<?php
/*
Plugin Name: Social Sidebar
Plugin URI: http://www.socialsidebar.net/
Description: Add a responsive social media sidebar to your site.
Author: ThemeBoy
Version: 1.0.2
Author URI: http://www.themeboy.com/
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Social_Sidebar' ) ) :

/**
 * Main Social_Sidebar Class
 *
 * @class Social_Sidebar
 * @version	1.0.2
 */
class Social_Sidebar {

	/**
	 * @var string
	 */
	public $version = '1.0.2';

	/**
	 * @var Social_Sidebar The single instance of the class
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * @var array
	 */
	var $networks = array();

	/**
	 * Main Social_Sidebar Instance
	 *
	 * Ensures only one instance of Social_Sidebar is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @see Social_Sidebar()
	 * @return Social_Sidebar - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'social-sidebar' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'social-sidebar' ), '1.0' );
	}

	/**
	 * Social_Sidebar Constructor.
	 * @access public
	 */
	public function __construct() {

		// Define constants
		$this->define_constants();

		// Get options
		$this->get_options();

		// Admin
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );

		// Frontend
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'get_footer', array( $this, 'sidebar' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SOCIAL_SIDEBAR_VERSION' ) )
			define( 'SOCIAL_SIDEBAR_VERSION', '1.0' );

		if ( defined( 'THEMEBOY_FILE' ) ) {
			if ( !defined( 'SOCIAL_SIDEBAR_URL' ) )
				define( 'SOCIAL_SIDEBAR_URL', get_template_directory_uri() . '/plugins/social-sidebar/' );

			if ( !defined( 'SOCIAL_SIDEBAR_DIR' ) )
				define( 'SOCIAL_SIDEBAR_DIR', get_template_directory() . '/plugins/social-sidebar/' );
		} else {
			if ( !defined( 'SOCIAL_SIDEBAR_URL' ) )
				define( 'SOCIAL_SIDEBAR_URL', plugin_dir_url( __FILE__ ) );

			if ( !defined( 'SOCIAL_SIDEBAR_DIR' ) )
				define( 'SOCIAL_SIDEBAR_DIR', plugin_dir_path( __FILE__ ) );
		}
	}

	/**
	 * Get options
	*/
	private function get_options() {
		$this->styles = apply_filters( 'social-sidebar_styles', array(
			'classic' => 'Classic',
			'minimal' => 'Minimal',
			'flat' => 'Flat',
			'material' => 'Material',
			'buttons' => 'Buttons',
		) );

		$this->positions = apply_filters( 'social-sidebar_positions', array(
			'left' => __( 'Left', 'social-sidebar' ),
			'right' => __( 'Right', 'social-sidebar' ),
		) );

		$this->networks = apply_filters( 'social-sidebar_networks', array(
			'500px' => '500px',
			'behance' => 'Behance',
			'dribbble' => 'Dribbble',
			'dropbox' => 'Dropbox',
			'evernote' => 'Evernote',
			'facebook' => 'Facebook',
			'flickr' => 'Flickr',
			'foursquare' => 'Foursquare',
			'github' => 'GitHub',
			'google' => 'Google+',
			'grooveshark' => 'Grooveshark',
			'instagram' => 'Instagram',
			'lastfm' => 'Last.fm',
			'line' => 'LINE',
			'linkedin' => 'LinkedIn',
			'mixi' => 'Mixi',
			'paypal' => 'PayPal',
			'pinterest' => 'Pinterest',
			'scribd' => 'Scribd',
			'skype' => 'Skype',
			'slideshare' => 'SlideShare',
			'smashing' => 'Smashing',
			'snapchat' => 'Snapchat',
			'soundcloud' => 'SoundCloud',
			'spotify' => 'Spotify',
			'stumbleupon' => 'StumbleUpon',
			'swarm' => 'Swarm',
			'tumblr' => 'Tumblr',
			'twitch' => 'Twitch',
			'twitter' => 'Twitter',
			'vine' => 'Vine',
			'vimeo' => 'Vimeo',
			'vk' => 'VK',
			'whatsapp' => 'WhatsApp',
			'wordpress' => 'WordPress',
			'xing' => 'Xing',
			'yelp' => 'Yelp',
			'youtube' => 'YouTube',
		) );
	}

	/**
	 * Add plugin settings to menu.
	 */
	public function admin_menu() {
		add_options_page( __( 'Social Sidebar Settings', 'social-sidebar' ), __( 'Social Sidebar', 'social-sidebar' ), 'manage_options', 'social-sidebar', array( $this, 'settings_page' ) );
	}

	/**
	 * Plugin settings page.
	 */
	public function settings_page() {
		if ( ! empty( $_POST ) ) {
			$this->save_settings();
		}
		$style = get_option( 'social-sidebar_style', 'classic' );
		$position = get_option( 'social-sidebar_position', 'left' );
		$networks = $this->networks;
		$keys = array_keys( $networks );
		$blank = array_fill_keys( $keys, '' );
		$option = get_option( 'social-sidebar_links', array() );
		$links = array_merge( $option, $blank, $option );
		?>
		<div class="wrap social-sidebar">
			<h2><?php _e( 'Social Sidebar', 'social-sidebar' ); ?></h2>
			<p><?php _e( 'Drag each item into the order you prefer.', 'social-sidebar' ); ?></p>
			<form method="post" id="mainform" enctype="multipart/form-data">
				<?php wp_nonce_field( 'social-sidebar-settings' ); ?>
				<table class="form-table social-sidebar-settings">
					<tbody>
						<tr>
							<th scope="row">
								<label for="social-sidebar_style">
									<?php _e( 'Style', 'social-sidebar' ); ?>
								</label>
							</th>
							<td>
								<select name="social-sidebar_style">
									<?php foreach ( $this->styles as $key => $label ) { ?>
										<option value="<?php echo $key; ?>" <?php selected( $key, $style ); ?>><?php echo $label; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social-sidebar_position">
									<?php _e( 'Position', 'social-sidebar' ); ?>
								</label>
							</th>
							<td>
								<select name="social-sidebar_position">
									<?php foreach ( $this->positions as $key => $label ) { ?>
										<option value="<?php echo $key; ?>" <?php selected( $key, $position ); ?>><?php echo $label; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<?php foreach ( $links as $key => $link ) { ?>
							<?php if ( ! array_key_exists( $key, $networks ) ) continue; ?>
							<tr class="social-sidebar-network<?php if ( '' == trim( $link ) ) { ?> social-sidebar-network-inactive<?php } ?>">
								<th scope="row">
									<label for="social-sidebar_links_<?php echo $key; ?>">
										<i class="social-sidebar-icon social-sidebar-icon-<?php echo $key; ?>"></i>
										<?php echo $networks[ $key ]; ?>
									</label>
								</th>
								<td>
									<input name="social-sidebar_links[<?php echo $key; ?>]" id="social-sidebar_links_<?php echo $key; ?>" type="text" class="regular-text social-sidebar-network-link" value="<?php echo $link; ?>">
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'social-sidebar' ); ?>">
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Save plugin settings.
	 */
	public function save_settings() {
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'social-sidebar-settings' ) )
			die( __( 'Action failed. Please refresh the page and retry.', 'social-sidebar' ) );

		if ( isset( $_POST['social-sidebar_style'] ) )
			update_option( 'social-sidebar_style', $_POST['social-sidebar_style'] );

		if ( isset( $_POST['social-sidebar_position'] ) )
			update_option( 'social-sidebar_position', $_POST['social-sidebar_position'] );

		if ( isset( $_POST['social-sidebar_links'] ) )
			update_option( 'social-sidebar_links', array_map( 'trim', $_POST['social-sidebar_links'] ) );
	}

	/**
	 * Admin styles
	 */
	public function admin_styles() {
		wp_enqueue_style( 'social-sidebar-icons', trailingslashit( SOCIAL_SIDEBAR_URL ) . 'assets/css/social-sidebar-icons.css', array(), SOCIAL_SIDEBAR_VERSION );
		wp_enqueue_style( 'social-sidebar-admin', trailingslashit( SOCIAL_SIDEBAR_URL ) . 'assets/css/social-sidebar-admin.css', array(), SOCIAL_SIDEBAR_VERSION );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		$screen = get_current_screen();
		if ( 'settings_page_social-sidebar' !== $screen->id ) return;

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-sortable' );
	    wp_enqueue_script( 'social-sidebar-admin', trailingslashit( SOCIAL_SIDEBAR_URL ) . 'assets/js/social-sidebar-admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable' ), SOCIAL_SIDEBAR_VERSION, true );
	}

	/**
	 * Frontend styles
	 */
	public function styles() {
		$style = get_option( 'social-sidebar_style', 'classic' );
		wp_enqueue_style( 'social-sidebar-icons', trailingslashit( SOCIAL_SIDEBAR_URL ) . 'assets/css/social-sidebar-icons.css', array(), SOCIAL_SIDEBAR_VERSION );
		wp_enqueue_style( 'social-sidebar', trailingslashit( SOCIAL_SIDEBAR_URL ) . 'assets/css/social-sidebar.css', array( 'social-sidebar-icons' ), SOCIAL_SIDEBAR_VERSION );
		wp_enqueue_style( 'social-sidebar-' . $style, trailingslashit( SOCIAL_SIDEBAR_URL ) . 'assets/css/skins/' . $style . '.css', array( 'social-sidebar', 'social-sidebar-icons' ), SOCIAL_SIDEBAR_VERSION );
	}

	/**
	 * Sidebar
	 */
	public function sidebar() {
		$position = get_option( 'social-sidebar_position', 'left' );
		$option = (array) get_option( 'social-sidebar_links', array() );
		$links = array_filter( $option );
		if ( 0 == sizeof( $links ) ) return;
		$networks = $this->networks;
		?>
		<div class="social-sidebar">
			<?php
			foreach ( $links as $key => $url ) {
				if ( ! preg_match( "~^(?:f|ht)tps?://~i", $url ) ) $url = 'http://' . $url;
				?><a href="<?php echo $url; ?>" title="<?php echo $networks[ $key ]; ?>" target="_blank" rel="nofollow"><i class="social-sidebar-icon social-sidebar-icon-<?php echo $key; ?>"></i></a><?php
			}
			?>
		</div>
		<?php if ( 'right' === $position ) { ?>
		<style type="text/css">
		@media screen and (min-width: 1200px) {
			.social-sidebar {
				left: auto;
				right: 0;
			}
		}
		</style>
		<?php } ?>
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

return Social_Sidebar::instance();

endif;
