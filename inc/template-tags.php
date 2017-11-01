<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Rookie
 */

if ( ! function_exists( 'rookie_header_widgets' ) ) :
/**
 * Display header widgets section.
 */
function rookie_header_widgets() {
	if ( is_active_sidebar( 'header-1' ) ) {
		?>
		<div id="tertiary" class="site-widgets" role="complementary">
			<div class="site-widget-region">
				<?php dynamic_sidebar( 'header-1' ); ?>
			</div>
		</div><!-- .site-widgets -->
		<?php
	}
}
endif;

if ( ! function_exists( 'rookie_header_branding' ) ) :
/**
 * Display header branding section.
 */
function rookie_header_branding( $options = array() ) {
	?>
	<div class="site-branding<?php if ( ! isset( $options['logo_url'] ) && ! $options['display_header_text'] ) { ?> site-branding-empty<?php } ?>">
		<div class="site-identity">
			<?php if ( isset( $options['logo_url'] ) ) { ?>
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo $options['logo_url']; ?>" alt="<?php bloginfo( 'name' ); ?>"></a>
			<?php } ?>
			<?php if ( $options['display_header_text'] ) { ?>
			<hgroup class="site-hgroup" style="color: #<?php echo $options['header_textcolor']; ?>">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
			<?php } ?>
		</div>
	</div><!-- .site-branding -->
	<?php
}
endif;

if ( ! function_exists( 'rookie_header_banner' ) ) :
/**
 * Display header banner section.
 */
function rookie_header_banner() {
	?>
	<div class="site-banner">
		<img class="site-banner-image" src="<?php header_image(); ?>" alt="<?php bloginfo( 'description' ); ?>">
	</div><!-- .site-banner -->
	<?php
}
endif;

if ( ! function_exists( 'rookie_header_menu' ) ) :
/**
 * Display header menu section.
 */
function rookie_header_menu( $options = array() ) {
	?>
	<div class="site-menu">
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="menu" aria-expanded="false"><span class="dashicons dashicons-menu"></span></button>
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			<?php if ( $options['has_search'] ) get_search_form(); ?>
		</nav><!-- #site-navigation -->
	</div><!-- .site-menu -->
	<?php
}
endif;

if ( ! function_exists( 'rookie_header_area' ) ) :
/**
 * Display header area sections.
 */
function rookie_header_area() {
	$options = get_option( 'themeboy', array() );
	if ( array_key_exists( 'logo_url', $options ) && ! empty( $options['logo_url'] ) ) {
		$options['logo_url'] = esc_url( set_url_scheme( $options['logo_url'] ) );
	}
	
	if ( ! array_key_exists( 'nav_menu_search', $options ) || $options['nav_menu_search'] ) {
		$options['has_search'] = true;
	} else {
		$options['has_search'] = false;
	}
	
	$options['display_header_text'] = display_header_text();
	
	$style_options = apply_filters( 'rookie_header_image_style_options', array(
        'background' => __( 'Background', 'rookie' ),
        'image' => __( 'Image', 'rookie' ),
    ) );

	reset( $style_options );
	$options['style'] = key( $style_options );
	
	if ( array_key_exists( 'header_image_style', $options ) && array_key_exists( $options['header_image_style'], $style_options ) ) {
		$options['style'] = $options['header_image_style'];
	}
	
	$options['header'] = get_header_image();
	
	$options['header_textcolor'] = get_header_textcolor();
	$options['header_textcolor'] = str_replace( '#', '', $options['header_textcolor'] );

	$sections = apply_filters( 'rookie_header_area_sections', array(
		'widgets',
		'branding',
		'banner',
		'menu',
	) );
	?>
	<?php if ( $options['header'] && 'background' == $options['style'] ) { ?>
	<div class="header-area header-area-custom<?php if ( isset( $options['logo_url'] ) ) { ?> header-area-has-logo<?php } ?><?php if ( $options['has_search'] ) { ?> header-area-has-search<?php } ?><?php if ( $options['display_header_text'] ) { ?> header-area-has-text<?php } ?>" style="background-image: url(<?php header_image(); ?>);">
	<?php } else { ?>
	<div class="header-area<?php if ( isset( $options['logo_url'] ) ) { ?> header-area-has-logo<?php } ?><?php if ( $options['has_search'] ) { ?> header-area-has-search<?php } ?>">
	<?php } ?>
		<?php do_action( 'rookie_before_header_area', $options, $sections ); ?>
		<div class="header-inner">
			<?php
			foreach ( $sections as $section ) {
				if ( 'widgets' == $section ) {
					rookie_header_widgets();
				} elseif ( 'banner' == $section && $options['header'] && 'image' == $options['style'] ) {
					rookie_header_banner();
				} elseif ( 'branding' == $section ) {
					rookie_header_branding( $options );
				} elseif ( 'menu' == $section ) {
					rookie_header_menu( $options );
				} else {
					do_action( 'rookie_header_area_section_' . $section, $options );
				}
			}
			?>
		</div><!-- .header-inner -->
		<?php do_action( 'rookie_after_header_area', $options, $sections ); ?>
	</div><!-- .header-area -->
	<?php
}
endif;

if ( ! function_exists( 'rookie_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function rookie_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'rookie' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'rookie' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'rookie' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'rookie_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function rookie_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'rookie' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'rookie' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'rookie' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'rookie_entry_title' ) ) :
/**
 * Prints HTML with title and author.
 */
function rookie_entry_title() {
	the_title( '<h1 class="entry-title single-entry-title">', '</h1>' );
}
endif;

if ( ! function_exists( 'rookie_entry_date' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function rookie_entry_date() {
	$options = get_option( 'themeboy', array() );

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	
	$posted_on = '';

	if ( ! array_key_exists( 'show_post_date', $options ) || $options['show_post_date'] ) {
		$posted_on .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
	}

	if ( array_key_exists( 'show_post_author', $options ) && $options['show_post_author'] ) {
		$byline = sprintf(
			_x( 'by %s', 'post author', 'rookie' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
		$posted_on .= ' <span class="byline">' . $byline . '</span>';
	}

	if ( '' !== $posted_on ) {
		echo '<span class="posted-on">' . $posted_on . '</span>';
	}
}
endif;

if ( ! function_exists( 'rookie_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories and comments.
 */
function rookie_entry_meta() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() && rookie_categorized_blog() ) {
		$categories_list = get_the_category_list( ' ' );
		if ( $categories_list ) {
			?>
			<div class="entry-meta">
				<div class="entry-category-links">
					<?php echo $categories_list; ?>
				</div><!-- .entry-category-links -->
			</div><!-- .entry-meta -->
			<?php
		}
	}
}
endif;

if ( ! function_exists( 'rookie_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the tags.
 */
function rookie_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		$tags_list = get_the_tag_list( '', ' ' );

		if ( $tags_list ) {
			?>
			<footer class="entry-footer">
				<div class="entry-tag-links">
					<?php echo $tags_list; ?>
				</div><!-- .entry-tag-links -->
			</footer><!-- .entry-footer -->
			<?php
		}
	}
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'rookie' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'rookie' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'rookie' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'rookie' ), get_the_date( _x( 'Y', 'yearly archives date format', 'rookie' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'rookie' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'rookie' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'rookie' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'rookie' ) ) );
	} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
		$title = _x( 'Asides', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
		$title = _x( 'Galleries', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
		$title = _x( 'Images', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
		$title = _x( 'Videos', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
		$title = _x( 'Quotes', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
		$title = _x( 'Links', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
		$title = _x( 'Statuses', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
		$title = _x( 'Audio', 'post format archive title', 'rookie' );
	} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
		$title = _x( 'Chats', 'post format archive title', 'rookie' );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'rookie' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'rookie' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'rookie' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

if ( ! function_exists( 'rookie_categorized_blog' ) ) :
/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function rookie_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'rookie_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'rookie_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so rookie_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so rookie_categorized_blog should return false.
		return false;
	}
}
endif;

if ( ! function_exists( 'rookie_category_transient_flusher' ) ) :
/**
 * Flush out the transients used in rookie_categorized_blog.
 */
function rookie_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'rookie_categories' );
}
add_action( 'edit_category', 'rookie_category_transient_flusher' );
add_action( 'save_post',     'rookie_category_transient_flusher' );
endif;
