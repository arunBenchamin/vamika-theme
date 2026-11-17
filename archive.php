<?php
/**
 * Archive template.
 *
 * @package Vamika
 */

get_header();
global $wp_query;

$archive_title = is_home() ? __( 'Archive', 'vamika' ) : vamika_get_archive_title_text();
$posts_by_year = array();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$year = get_the_date( 'Y' );
		if ( ! isset( $posts_by_year[ $year ] ) ) {
			$posts_by_year[ $year ] = array();
		}
		$posts_by_year[ $year ][] = get_post();
	}
	rewind_posts();
}
?>
<main id="content" class="page active page-archive site-main">
	<div class="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vamika' ); ?></a>
		<span class="sep">/</span>
		<span class="curr"><?php echo esc_html( $archive_title ); ?></span>
	</div>

	<section class="page-hero">
		<div class="eyebrow">✦ <?php esc_html_e( 'The Full Shelf', 'vamika' ); ?> ✦</div>
		<h1><?php esc_html_e( 'Every', 'vamika' ); ?> <em><?php esc_html_e( 'issue,', 'vamika' ); ?></em><br><?php esc_html_e( 'back to the', 'vamika' ); ?> <span class="hl"><?php esc_html_e( 'beginning.', 'vamika' ); ?></span></h1>
		<p class="lede"><?php echo esc_html( sprintf( __( '%d posts. %d categories. All of it still worth reading.', 'vamika' ), (int) $wp_query->found_posts, (int) wp_count_terms( array( 'taxonomy' => 'category', 'hide_empty' => true ) ) ) ); ?></p>
	</section>

	<section class="archive-section container">
		<?php if ( vamika_has_iris() ) : ?>
		<div class="ai-inline-tool stacked" role="region"
			aria-label="<?php esc_attr_e( 'Iris archive assistant', 'vamika' ); ?>"
			data-iris-prompt="<?php echo esc_attr( __( 'You are Iris, the AI reading companion for Vamika magazine. The reader is browsing the full archive. Help them find what they are looking for. If they describe a mood, theme, or half-remembered idea, suggest 3 matching articles with titles, links, and a one-line reason each. Keep the tone warm and concise.', 'vamika' ) ); ?>"
		>
			<div class="iris-avatar" aria-hidden="true"><?php vamika_render_iris_icon(); ?></div>
			<p><?php esc_html_e( 'Describe a mood or half-remembered idea and Iris will point you to the right part of the archive - or summarize it in a sentence.', 'vamika' ); ?></p>
			<div class="ai-inline-prompts">
				<button type="button"><?php esc_html_e( 'Summarize the latest issue', 'vamika' ); ?></button>
				<button type="button"><?php esc_html_e( 'Best of last year', 'vamika' ); ?></button>
				<button type="button"><?php esc_html_e( 'Essays on focus', 'vamika' ); ?></button>
			</div>
			<button class="ai-inline-btn" type="button"><?php esc_html_e( 'Ask Iris', 'vamika' ); ?></button>
			<div class="ai-inline-response" style="display:none;">
				<div class="ai-inline-response-head">
					<div class="iris-team-av"><?php vamika_render_iris_icon( false ); ?></div>
					<div class="ai-inline-response-meta">
						<div class="ai-inline-response-name"><?php esc_html_e( 'Iris', 'vamika' ); ?></div>
						<div class="ai-inline-response-sub"><?php esc_html_e( 'Archive guide', 'vamika' ); ?></div>
					</div>
					<button class="ai-inline-close" type="button" aria-label="<?php esc_attr_e( 'Close', 'vamika' ); ?>">&times;</button>
				</div>
				<div class="ai-inline-text"></div>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $posts_by_year ) ) : ?>
			<?php foreach ( $posts_by_year as $year => $posts ) : ?>
				<div class="year-group">
					<div class="year-header">
						<div class="yr"><?php echo esc_html( substr( (string) $year, 0, 2 ) ); ?><em><?php echo esc_html( substr( (string) $year, 2 ) ); ?></em></div>
						<div class="count"><?php echo esc_html( sprintf( _n( '%d issue', '%d issues', count( $posts ), 'vamika' ), count( $posts ) ) ); ?></div>
					</div>
					<div class="issue-grid">
						<?php foreach ( $posts as $index => $post ) : setup_postdata( $post ); ?>
							<div class="issue-card" data-href="<?php the_permalink(); ?>" tabindex="0" role="link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<div class="issue-cover">
									<span class="vol"><?php echo esc_html( sprintf( __( 'No. %d', 'vamika' ), $index + 1 ) ); ?></span>
									<?php
									/*
									 * The "Summarize" pill + hidden summary dialog ship
									 * with the Iris Theme Kit plugin so they only appear
									 * when Iris AI is active (plugin-territory rule).
									 * When inactive the helper emits nothing.
									 */
									if ( function_exists( 'iris_theme_kit_render_card_summary_button' ) ) {
										iris_theme_kit_render_card_summary_button();
									}
									?>
									<?php vamika_render_post_visual( 'story-img' ); ?>
								</div>
								<div class="issue-info">
									<div class="num"><?php esc_html_e( 'No.', 'vamika' ); ?> <em><?php echo esc_html( $index + 1 ); ?></em></div>
									<div class="date"><?php echo esc_html( get_the_date( 'F Y' ) ); ?></div>
									<div class="theme">"<?php the_title(); ?>"</div>
								</div>
							</div>
						<?php endforeach; wp_reset_postdata(); ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No archive entries found yet.', 'vamika' ); ?></p>
		<?php endif; ?>
	</section>
</main>
<?php
get_footer();
