<?php
/**
 * Comments template.
 *
 * @package Vamika
 */

if ( post_password_required() ) {
	return;
}
?>
<section class="reactions">
	<div class="lbl">— <?php esc_html_e( 'Conversation', 'vamika' ); ?> —</div>
	<?php if ( have_comments() ) : ?>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true ) ); ?>
		</ol>
		<?php
		the_comments_pagination(
			array(
				'prev_text' => esc_html__( 'Older comments', 'vamika' ),
				'next_text' => esc_html__( 'Newer comments', 'vamika' ),
			)
		);
		?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>

