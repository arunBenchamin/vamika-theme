<?php
/**
 * 404 template.
 *
 * @package Vamika
 */

get_header();
?>
<main id="content" class="page active page-notfound site-main">
	<section class="notfound">
		<div class="notfound-inner">
			<div class="notfound-iris"><?php vamika_render_iris_icon(); ?></div>
			<div class="code">4<em>0</em>4</div>
			<h1><?php esc_html_e( 'Hmm. That page is not here.', 'vamika' ); ?></h1>
			<p><?php esc_html_e( 'Either the URL changed or the story moved. Here are the places readers usually head next.', 'vamika' ); ?></p>
			<div class="notfound-actions">
				<a class="primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Back to Home', 'vamika' ); ?></a>
				<a class="secondary" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>"><?php esc_html_e( 'Browse Archive', 'vamika' ); ?></a>
			</div>
			<div class="notfound-suggest">
				<div class="lbl">✦ <?php esc_html_e( 'Popular right now', 'vamika' ); ?></div>
				<div class="pills">
					<?php
					$popular = get_posts(
						array(
							'post_type'      => 'post',
							'posts_per_page' => 4,
						)
					);

					foreach ( $popular as $post ) :
						setup_postdata( $post );
						?>
						<a class="pill" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
			</div>
			<?php if ( vamika_has_iris() ) : ?>
			<?php
			$attempted_path  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$iris_404_prompt = sprintf(
				/* translators: %s is the URL path the visitor tried to reach */
				__(
					'You are Iris, the AI reading companion for Vamika magazine. The visitor just hit a 404 page while trying to reach "%s". They are lost. Suggest 3 to 4 interesting articles or sections from the site they might enjoy based on what the URL suggests they were looking for. For each, include the article title as a clickable HTML link using the real URL from the site, and a one-line reason why it is worth reading. Keep the tone warm, friendly, and a little playful. End with a short encouraging note.',
					'vamika'
				),
				$attempted_path
			);
			?>
			<div class="ai-inline-tool" role="region"
				aria-label="<?php esc_attr_e( 'Iris reroute helper', 'vamika' ); ?>"
				data-iris-prompt="<?php echo esc_attr( $iris_404_prompt ); ?>"
			>
				<div class="iris-avatar" aria-hidden="true"><?php vamika_render_iris_icon(); ?></div>
				<p><?php esc_html_e( 'Lost? Tap Iris and she will nudge you toward the closest match or a good place to restart.', 'vamika' ); ?></p>
				<button class="ai-inline-btn" type="button"><?php esc_html_e( 'Send me somewhere', 'vamika' ); ?></button>
				<div class="ai-inline-response" style="display:none;">
					<div class="ai-inline-response-head">
						<div class="iris-team-av"><?php vamika_render_iris_icon( false ); ?></div>
						<div class="ai-inline-response-meta">
							<div class="ai-inline-response-name"><?php esc_html_e( 'Iris', 'vamika' ); ?></div>
							<div class="ai-inline-response-sub"><?php esc_html_e( 'Reroute suggestions', 'vamika' ); ?></div>
						</div>
						<button class="ai-inline-close" type="button" aria-label="<?php esc_attr_e( 'Close', 'vamika' ); ?>">&times;</button>
					</div>
					<div class="ai-inline-text"></div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
