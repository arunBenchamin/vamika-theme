<?php
/**
 * Front page story card.
 *
 * @package Vamika
 */

$featured = ! empty( $args['featured'] );
?>
<article <?php post_class( 'story-card' . ( $featured ? ' big' : '' ) ); ?> data-href="<?php the_permalink(); ?>" tabindex="0" role="link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<div class="story-img">
		<?php
		/*
		 * The "Summarize" pill + summary dialog live in the Iris Theme
		 * Kit plugin so they render only when Iris AI is active (per the
		 * WordPress.org "plugin territory" rule). When the plugin is
		 * inactive the helper emits nothing and the card just shows its
		 * media block.
		 */
		if ( function_exists( 'iris_theme_kit_render_card_summary_button' ) ) {
			iris_theme_kit_render_card_summary_button();
		}
		?>
		<?php vamika_render_post_visual( 'story-img-media' ); ?>
	</div>
	<div class="story-body">
		<span class="cat-tag <?php echo esc_attr( vamika_get_primary_term_slug() ); ?>">
			<?php echo esc_html( vamika_get_primary_term_name() ); ?>
		</span>
		<h3><?php the_title(); ?></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></p>
		<div class="story-footer">
			<span class="by"><?php the_author(); ?></span>
			<span><?php echo esc_html( vamika_get_post_reading_time() ); ?></span>
		</div>
	</div>
</article>
