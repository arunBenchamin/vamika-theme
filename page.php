<?php
/**
 * Page template.
 *
 * @package Vamika
 */

get_header();
?>
<main id="content" class="page active page-about site-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php
		$elementor_page = 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) || ! empty( get_post_meta( get_the_ID(), '_elementor_data', true ) );
		?>
		<?php if ( $elementor_page ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<div class="breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vamika' ); ?></a>
				<span class="sep">/</span>
				<span class="curr"><?php the_title(); ?></span>
			</div>

			<section class="page-hero">
				<div class="eyebrow">✦ <?php esc_html_e( 'Page', 'vamika' ); ?> ✦</div>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</section>

			<div class="container-narrow">
				<article class="page-body">
					<?php the_content(); ?>
					<?php
					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'vamika' ),
							'after'  => '</div>',
						)
					);
					?>
				</article>

				<?php if ( vamika_has_iris() ) : ?>
				<div class="ai-inline-tool" role="region"
					aria-label="<?php esc_attr_e( 'Iris page helper', 'vamika' ); ?>"
					data-iris-prompt="<?php echo esc_attr( sprintf(
						/* translators: %s: page title */
						__( 'You are Iris, the AI reading companion for Vamika magazine. Summarize the page titled "%s" in 2-3 clear, plain-English sentences. Focus on the key points a reader would want to know. Keep the tone warm and concise.', 'vamika' ),
						get_the_title()
					) ); ?>"
				>
					<div class="iris-avatar" aria-hidden="true"><?php vamika_render_iris_icon(); ?></div>
					<p><?php esc_html_e( 'Need a quick summary of this page? Iris can generate a plain-English overview for readers.', 'vamika' ); ?></p>
					<button class="ai-inline-btn" type="button"><?php esc_html_e( 'Generate summary', 'vamika' ); ?></button>
					<div class="ai-inline-response" style="display:none;">
						<div class="ai-inline-response-head">
							<div class="iris-team-av"><?php vamika_render_iris_icon( false ); ?></div>
							<div class="ai-inline-response-meta">
								<div class="ai-inline-response-name"><?php esc_html_e( 'Iris', 'vamika' ); ?></div>
								<div class="ai-inline-response-sub"><?php esc_html_e( 'Page summary', 'vamika' ); ?></div>
							</div>
							<button class="ai-inline-close" type="button" aria-label="<?php esc_attr_e( 'Close', 'vamika' ); ?>">&times;</button>
						</div>
						<div class="ai-inline-text"></div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
