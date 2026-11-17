<?php
/**
 * Search form template.
 *
 * @package Vamika
 */
if ( empty( $args ) || ! is_array( $args ) ) {
	$args = array();
}

$vamika_search_id   = wp_unique_id( 'vamika-search-' );
$vamika_placeholder = ! empty( $args['placeholder'] ) ? $args['placeholder'] : __( 'Search by idea, writer, or theme', 'vamika' );
$vamika_variant     = ! empty( $args['vamika_variant'] ) ? $args['vamika_variant'] : 'default';

if ( 'chat' === $vamika_variant ) :
	?>
	<form role="search" method="get" class="chat-input-row" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="icon" aria-hidden="true"><?php vamika_render_iris_icon( false ); ?></div>
		<label class="screen-reader-text" for="<?php echo esc_attr( $vamika_search_id ); ?>"><?php esc_html_e( 'Search for:', 'vamika' ); ?></label>
		<input type="search" id="<?php echo esc_attr( $vamika_search_id ); ?>" name="s" placeholder="<?php echo esc_attr( $vamika_placeholder ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
		<button type="submit" class="send-btn" aria-label="<?php esc_attr_e( 'Search', 'vamika' ); ?>">&rarr;</button>
	</form>
	<?php
else :
	?>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text" for="<?php echo esc_attr( $vamika_search_id ); ?>"><?php esc_html_e( 'Search for:', 'vamika' ); ?></label>
		<input type="search" id="<?php echo esc_attr( $vamika_search_id ); ?>" class="search-field" placeholder="<?php echo esc_attr( $vamika_placeholder ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
		<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'vamika' ); ?></button>
	</form>
	<?php
endif;
