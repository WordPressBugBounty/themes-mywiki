<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package MyWiki
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside id="secondary" class="mw-sidebar widget-area" aria-label="<?php esc_attr_e( 'Sidebar', 'mywiki' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
