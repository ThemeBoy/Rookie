<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Rookie
 */

if ( ! is_active_sidebar( 'sidebar-1' ) && ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
}

$sidebar = rookie_get_sidebar_setting();

// Initialize counter
$i = 1;

// Output left sidebar
if ( in_array( $sidebar, array( 'left', 'double' ) ) ) {
?>
<div id="secondary" class="widget-area widget-area-left<?php if ( 'double' === $sidebar ) { ?> widget-area-narrow<?php } ?>" role="complementary">
	<?php dynamic_sidebar( 'sidebar-' . $i ); ?>
</div><!-- #secondary -->
<?php
$i++;
}

// Output right sidebar
if ( in_array( $sidebar, array( 'right', 'double' ) ) ) {
?>
<div id="secondary<?php if ( $i > 1 ) { echo '-' . $i; } ?>" class="widget-area widget-area-right<?php if ( 'double' === $sidebar ) { ?> widget-area-narrow<?php } ?>" role="complementary">
    <?php dynamic_sidebar( 'sidebar-' . $i ); ?>
</div><!-- #secondary -->
<?php
}