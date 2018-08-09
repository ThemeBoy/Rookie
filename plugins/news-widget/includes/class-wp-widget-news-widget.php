<?php
/**
 * Widget API: WP_Widget_News_Widget class
 *
 * @package News_Widget
 * @subpackage Widgets
 * @since 1.0
 */

/**
 * Core class used to implement a News Widget widget.
 *
 * @since 1.0
 *
 * @see WP_Widget
 */
class WP_Widget_News_Widget extends WP_Widget {

	/**
	 * Sets up a new News Widget widget instance.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_news_widget', 'description' => __( 'A feed of posts with featured images.', 'news-widget' ) );
		parent::__construct( 'news-widget', __( 'News Widget', 'news-widget' ), $widget_ops );
		$this->alt_option_name = 'widget_news_widget';
	}

	/**
	 * Outputs the content for the current News Widget widget instance.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current News Widget widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

		$args['title'] = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$args['category'] = ( ! empty( $instance['category'] ) ) ? absint( $instance['category'] ) : 0;

		$args['number'] = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $args['number'] )
			$args['number'] = 5;

		$args['columns'] = ( ! empty( $instance['columns'] ) ) ? absint( $instance['columns'] ) : 1;
		if ( ! $args['columns'] )
			$args['columns'] = 1;

		$args['offset'] = ( ! empty( $instance['offset'] ) ) ? absint( $instance['offset'] ) : 0;
		if ( ! $args['offset'] )
			$args['offset'] = 0;

		$args['show_date'] = isset( $instance['show_date'] ) ? $instance['show_date'] : true;
		
		$args['show_excerpt'] = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : true;
		
		News_Widget::widget( $args );
	}

	/**
	 * Handles updating the settings for the current News Widget widget instance.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['category'] = (int) $new_instance['category'];
		$instance['number'] = (int) $new_instance['number'];
		$instance['columns'] = (int) $new_instance['columns'];
		$instance['offset'] = (int) $new_instance['offset'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? (bool) $new_instance['show_excerpt'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the News Widget widget.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$category  = isset( $instance['category'] ) ? absint( $instance['category'] ) : 0;
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$columns   = isset( $instance['columns'] ) ? absint( $instance['columns'] ) : 1;
		$offset    = isset( $instance['offset'] ) ? absint( $instance['offset'] ) : 0;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
		$show_excerpt = isset( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : true;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ); ?></label>
		<?php
		wp_dropdown_categories( array(
			'id' => $this->get_field_id( 'category' ),
			'name' => $this->get_field_name( 'category' ),
			'selected' => $category,
			'show_option_all' => __( 'All', 'news-widgets' ),
			'hide_empty' => false
		) ); ?></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php _e( 'Columns:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>" type="number" step="1" min="1" max="5" value="<?php echo $columns; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'offset' ); ?>"><?php _e( 'Offset:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>" type="number" step="1" min="0" value="<?php echo $offset; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_excerpt ); ?> id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>"><?php _e( 'Display excerpt?' ); ?></label></p>
		<?php
	}
}
