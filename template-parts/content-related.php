<?php
/**
 * Related card template.
 *
 * @package Vamika
 */

$vamika_card_index = isset( $args['index'] ) ? (int) $args['index'] : 0;
?>
<div class="related-card" data-href="<?php the_permalink(); ?>" tabindex="0" role="link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<div class="score-big"><?php echo esc_html( sprintf( '%02d', $vamika_card_index ) ); ?></div>
	<div class="score-lbl"><?php echo esc_html( vamika_get_primary_term_name() ); ?></div>
	<div class="cat"><?php echo esc_html( get_the_date( 'F' ) ); ?> · <?php echo esc_html( get_the_date( 'Y' ) ); ?></div>
	<h4><?php the_title(); ?></h4>
	<div class="meta"><?php echo esc_html( vamika_get_post_reading_time() ); ?></div>
</div>
