<?php
/*
Plugin Name: News Widget
Plugin URI: http://newswidget.me/
Description: Display latest posts in a widget with single or multiple columns.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.0.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'News_Widget' ) ) :

/**
 * Main News_Widget class
 *
 * @class News_Widget
 * @version	1.0.1
 */
class News_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_head', array( $this, 'add_shortcode_button' ), 11 );
		add_filter( 'tiny_mce_version', array( $this, 'refresh_mce' ) );
		add_filter( 'mce_external_languages', array( $this, 'add_tinymce_lang' ), 11, 1 );
		add_action( 'wp_ajax_news_widget', array( $this, 'ajax' ) );
		add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );
		add_shortcode( 'news_widget', array( $this, 'shortcode' ) );
		add_shortcode( 'news-widget', array( $this, 'shortcode' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'NEWS_WIDGET_VERSION' ) )
			define( 'NEWS_WIDGET_VERSION', '1.0.1' );

		if ( defined( 'THEMEBOY_FILE' ) ) {
			if ( !defined( 'NEWS_WIDGET_URL' ) )
				define( 'NEWS_WIDGET_URL', get_template_directory_uri() . '/plugins/news-widget/' );

			if ( !defined( 'NEWS_WIDGET_DIR' ) )
				define( 'NEWS_WIDGET_DIR', get_template_directory() . '/plugins/news-widget/' );
		} else {
			if ( !defined( 'NEWS_WIDGET_URL' ) )
				define( 'NEWS_WIDGET_URL', plugin_dir_url( __FILE__ ) );

			if ( !defined( 'NEWS_WIDGET_DIR' ) )
				define( 'NEWS_WIDGET_DIR', plugin_dir_path( __FILE__ ) );
		}
	}

	/**
	 * Add widget
	 */
	public function register_widget() {
		include_once( 'includes/class-wp-widget-news-widget.php' );
		register_widget( 'WP_Widget_News_Widget' );
	}

	/**
	 * Shortcode callback
	 */
	public static function shortcode( $atts = array() ) {
		include_once( 'includes/class-wp-widget-news-widget.php' );
		ob_start();
		self::widget( $atts );
		return ob_get_clean();
	}
	

	/**
	 * Widget content
	 */
	public static function widget( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'title' => '',
			'before_title' => '',
			'after_title' => '',
			'before_widget' => '',
			'after_widget' => '',
			'category' => 0,
			'number' => 5,
			'columns' => 1,
			'offset' => 0,
			'show_date' => true,
			'show_excerpt' => true
		) );
		extract( $args );
		
		/**
		 * Filter the arguments for the News Widget widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'cat'                 => $category,
			'posts_per_page'      => $number,
			'offset'              => $offset,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) ) );

		if ( $r->have_posts() ) :
		
			if ( 1 == $number ) {
				$size = 'large';
			} else {
				$size = 'medium';
			}
			?>
			<?php echo $before_widget; ?>
			<?php if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			} ?>
			<div class="news-widget--<?php echo $columns; ?>-<?php echo 1 == $columns ? 'column' : 'columns news-widget--has-columns'; ?>">
			<?php while ( $r->have_posts() ) : $r->the_post(); $permalink = get_permalink(); ?>
				<div class="news-widget__post">
					<a href="<?php echo $permalink; ?>">
						<?php the_post_thumbnail( $size, array( 'class' => 'news-widget__post__thumbnail' ) ); ?>
					</a>
					<a class="news-widget__post__title" href="<?php echo $permalink; ?>">
						<?php get_the_title() ? the_title() : the_ID(); ?>
					</a>
					<?php if ( $show_date ) : ?>
						<span class="news-widget__post__date">
							<a href="<?php echo $permalink; ?>">
								<?php echo get_the_date(); ?>
							</a>
						</span>
					<?php endif; ?>
					<?php if ( $show_excerpt ) : ?>
						<div class="news-widget__post__excerpt">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
			</div>
			<?php echo $after_widget; ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;
	}

	/**
	 * Enqueue styles
	 */
	public function styles() {
		wp_enqueue_style( 'news-widget-style', trailingslashit( NEWS_WIDGET_URL ) . 'assets/css/news-widget.css', array(), NEWS_WIDGET_VERSION );
	}

	/**
	 * Enqueue admin styles
	 */
	public function admin_styles() {
		wp_enqueue_style( 'news-widget-admin', NEWS_WIDGET_URL . 'assets/css/news-widget-admin.css', array(), NEWS_WIDGET_VERSION );
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
	    $arr['news_widget_button'] = NEWS_WIDGET_DIR . 'assets/js/editor-lang.php';
	    return $arr;
	}

	/**
	 * Register the shortcode button.
	 *
	 * @param array $buttons
	 * @return array
	 */
	public function register_shortcode_button( $buttons ) {
		array_push( $buttons, 'news_widget_button' );
		return $buttons;
	}

	/**
	 * Add the shortcode button to TinyMCE
	 *
	 * @param array $plugin_array
	 * @return array
	 */
	public function add_shortcode_tinymce_plugin( $plugin_array ) {
		$plugin_array['news_widget_button'] = NEWS_WIDGET_URL . 'assets/js/editor.js';
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
		<div class="wrap news-widget-thickbox-content" id="news-widget-thickbox">
			<form>
				<table class="news-widget-settings form-table">
					<tbody>
						<tr>
							<th scope="row"><?php _e( 'Category:' ); ?></th>
							<td>
								<?php
								wp_dropdown_categories( array(
									'id' => 'category',
									'name' => 'category',
									'selected' => 0,
									'show_option_all' => __( 'All', 'news-widgets' ),
									'hide_empty' => false
								) ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Number of posts to show:' ); ?></th>
							<td>
								<input class="tiny-text" id="number" name="number" type="number" step="1" min="1" value="3" size="3" />
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Columns:' ); ?></th>
							<td>
								<input class="tiny-text" id="columns" name="columns" type="number" step="1" min="1" max="5" value="3" size="3" />
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Offset:' ); ?></th>
							<td>
								<input class="tiny-text" id="offset" name="offset" type="number" step="1" min="0" value="0" size="3" />
							</td>
						</tr>
						<tr>
							<th colspan="2">
								<input class="checkbox" type="checkbox" <?php checked( true ); ?> id="show_date" name="show_date" />
								<label for="show_date"><?php _e( 'Display post date?' ); ?></label>
							</th>
						</tr>
						<tr>
							<th colspan="2">
								<input class="checkbox" type="checkbox" <?php checked( true ); ?> id="show_excerpt" name="show_excerpt" />
								<label for="show_excerpt"><?php _e( 'Display excerpt?' ); ?></label>
							</th>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="button" class="button-primary" value="<?php _e( 'Insert News Widget', 'news-widget' ); ?>" onclick="insert_news_widget();" />
					<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel' ); ?>"><?php _e( 'Cancel' ); ?></a>
				</p>
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
		function insert_news_widget() {
			var $div = jQuery('.news-widget-thickbox-content');

			// Initialize shortcode arguments
			var args = {};

			// Add category if available and not 0
			category = $div.find('[name=category]:enabled').val();
			if ( category != 0 ) args.category = category;

			// Add number
			args.number = $div.find('[name=number]').val();

			// Add columns
			args.columns = $div.find('[name=columns]').val();

			// Add offset
			args.offset = $div.find('[name=offset]').val();

			// Add show date
			args.show_date = $div.find('[name=show_date]').prop('checked') ? 1 : 0;

			// Add show excerpt
			args.show_excerpt = $div.find('[name=show_excerpt]').prop('checked') ? 1 : 0;

			// Generate the shortcode
			var shortcode = '[news_widget';
			for ( var key in args ) {
				if ( args.hasOwnProperty( key ) ) {
					shortcode += ' ' + key + '="' + args[key] + '"';
				}
			}
			shortcode += ']';

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

new News_Widget();

endif;
