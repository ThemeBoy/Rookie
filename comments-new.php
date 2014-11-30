<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

class Premier_Walker_Comment extends Walker_Comment {

	var $tree_type = 'comment';
	var $db_fields = array ('parent' => 'comment_parent', 'id' => 'comment_ID');

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				$output .= '<div class="row">' . "\n";
				break;
			case 'ol':
				$output .= '<ol class="children">' . "\n";
				break;
			default:
			case 'ul':
				$output .= '<ul class="children">' . "\n";
				break;
		}
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				$output .= "</div><!-- .row -->\n";
				break;
			case 'ol':
				$output .= "</ol><!-- .children -->\n";
				break;
			default:
			case 'ul':
				$output .= "</ul><!-- .children -->\n";
				break;
		}
	}

	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];
		$id = $element->$id_field;

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

		// If we're at the max depth, and the current element still has children, loop over those and display them at this level
		// This is to prevent them being orphaned to the end of the list.
		if ( $max_depth <= $depth + 1 && isset( $children_elements[$id]) ) {
			foreach ( $children_elements[ $id ] as $child )
				$this->display_element( $child, $children_elements, $max_depth, $depth, $args, $output );

			unset( $children_elements[ $id ] );
		}

	}

	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty( $args['callback'] ) ) {
			ob_start();
			call_user_func( $args['callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}

		if ( $depth > 1 )
			$output .= '<' . $args['style'] . ' class="large-10 small-11 large-offset-2 small-offset-1 columns">';
		else
			$output .= '<' . $args['style'] . '>';

		if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
			ob_start();
			$this->ping( $comment, $depth, $args );
			$output .= ob_get_clean();
		} else {
			ob_start();
			$this->html5_comment( $comment, $depth, $args );
			$output .= ob_get_clean();
		}
	}

	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( !empty( $args['end-callback'] ) ) {
			ob_start();
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}
		$output .= '</' . $args['style'] . ">\n";
	}

	protected function html5_comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body clearfix">

				<?php if ( 0 != $args['avatar_size'] ): ?>
					<div class="comment-avatar">
						<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
					</div><!-- .comment-avatar -->
				<?php endif; ?>

				<div class="comment-main">
					<div class="comment-author">
						<?php comment_author_link(); ?>
					</div><!-- .comment-author -->

					<div class="comment-content">
						<?php comment_text(); ?>
					</div><!-- .comment-content -->

					<footer class="comment-meta">
						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>" title="<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'themeboy' ), get_comment_date(), get_comment_time() ); ?>">
									<span class="icon-clock"></span>
									<?php printf( __( '%s ago', 'themeboy' ), human_time_diff( get_comment_time('U'), current_time('timestamp') ) ); ?>
								</time>
							</a>
							<?php edit_comment_link( __( 'Edit', 'themeboy' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- .comment-metadata -->

						<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'themeboy' ); ?></p>
						<?php endif; ?>
					</footer><!-- .comment-meta -->
				</div><!-- .comment-main -->

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<span class="icon-reply"></span>' ) ) ); ?>
				</div><!-- .reply -->
			</article><!-- .comment-body -->
		</<?php echo $tag; ?>><!-- $tag -->
<?php
	}
}

?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

	<h2 class="comments-title">
		<a href="#respond" class="smoothscroll respond-button"><span class="icon-pencil"></span></a>
		<?php _e( 'Comments', 'themeboy' ); ?>
		<span class="comments-count"><?php echo get_comments_number(); ?></span>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyfourteen' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyfourteen' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>

	<div class="comment-list">
		<?php
			wp_list_comments( array(
				'walker'     => new Premier_Walker_Comment,
				'max_depth'  => 3,
				'style'      => 'div',
				'short_ping' => true,
				'avatar_size'=> 80,
			) );
		?>
	</div><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyfourteen' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyfourteen' ) ); ?></div>
	</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfourteen' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$args = array(
			'comment_notes_before' => null,
			'comment_notes_after' => null,
			'title_reply' => __( 'Leave a comment', 'themeboy' ),
			'comment_field' => '<p class="comment-form-comment">' .
				'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' .
				__( 'Your Comment', 'themeboy' ) . '"></textarea>' .
				'</p>',
	 		'label_submit' => __( 'Submit Comment', 'themeboy' ),
	 		'fields' => apply_filters( 'comment_form_default_fields', array(
	 			'author' => '<p class="comment-form-author">' .
			    '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			    '" size="30"' . $aria_req . ' placeholder="' . __( 'Name', 'themeboy' ) . '" /></p>',
			  'email' =>
			    '<p class="comment-form-email">' .
			    '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			    '" size="30"' . $aria_req . ' placeholder="' . __( 'Email', 'themeboy' ) .
			    '" title="' . __( 'Your email address will not be published.', 'themeboy' ) . '" /></p>',
			) ),
		);
		tb_comment_form( $args );
	?>

</div><!-- #comments -->
