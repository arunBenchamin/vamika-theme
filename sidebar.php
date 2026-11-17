<?php
/**
 * Sidebar template.
 *
 * @package Vamika
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'vamika' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
