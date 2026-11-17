<?php
/**
 * Category archive template.
 *
 * @package Vamika
 */

get_header();
global $wp_query;
$term = get_queried_object();
?>
<main id="content" class="page active page-category site-main">
	<section class="category-header">
		<div class="inner container-narrow">
			<div class="ey"><?php esc_html_e( 'Section', 'vamika' ); ?></div>
			<h1><?php single_cat_title(); ?><em>.</em></h1>
			<p><?php echo esc_html( term_description( $term ) ?: __( 'Essays, arguments, and long thoughts for readers willing to stay with an idea a little longer.', 'vamika' ) ); ?></p>
			<div class="meta">
				<div><strong><?php echo esc_html( number_format_i18n( (int) $wp_query->found_posts ) ); ?></strong><?php esc_html_e( 'Essays', 'vamika' ); ?></div>
				<div><strong><?php echo esc_html( number_format_i18n( count( get_users( array( 'fields' => 'ID', 'who' => 'authors' ) ) ) ) ); ?></strong><?php esc_html_e( 'Writers', 'vamika' ); ?></div>
				<div><strong><?php echo esc_html( date_i18n( 'Y' ) ); ?></strong><?php esc_html_e( 'Running', 'vamika' ); ?></div>
			</div>
		</div>
	</section>

	<div class="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vamika' ); ?></a>
		<span class="sep">/</span>
		<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>"><?php esc_html_e( 'Sections', 'vamika' ); ?></a>
		<span class="sep">/</span>
		<span class="curr"><?php single_cat_title(); ?></span>
	</div>

	<section class="category-filters">
		<div class="filters-inner container">
			<span class="label"><?php esc_html_e( 'Filter →', 'vamika' ); ?></span>
			<button class="filter-pill active" type="button"><?php esc_html_e( 'All', 'vamika' ); ?></button>
			<?php foreach ( get_categories( array( 'hide_empty' => true, 'number' => 5 ) ) as $category ) : ?>
				<button class="filter-pill<?php echo (int) $category->term_id === (int) $term->term_id ? ' active' : ''; ?>" type="button"><?php echo esc_html( $category->name ); ?></button>
			<?php endforeach; ?>
			<span class="label" style="margin-left: auto;"><?php esc_html_e( 'Sort →', 'vamika' ); ?></span>
			<button class="filter-pill active" type="button"><?php esc_html_e( 'Latest', 'vamika' ); ?></button>
			<button class="filter-pill" type="button"><?php esc_html_e( 'Most read', 'vamika' ); ?></button>
		</div>
		<?php if ( vamika_has_iris() ) : ?>
		<div class="ai-inline-tool" role="region"
			aria-label="<?php esc_attr_e( 'Iris topic explorer', 'vamika' ); ?>"
			data-iris-prompt="<?php echo esc_attr( sprintf(
				/* translators: %s: category name */
				__( 'You are Iris, the AI reading companion for Vamika magazine. The reader is browsing the "%s" category. Assemble a three-story reading stack from this category that matches a curious reader with about 15 minutes. For each story, include the title as a clickable link, estimated reading time, and a one-line hook. Keep the tone warm and concise.', 'vamika' ),
				single_cat_title( '', false )
			) ); ?>"
		>
			<div class="iris-avatar" aria-hidden="true"><?php vamika_render_iris_icon(); ?></div>
			<p><?php esc_html_e( 'Let Iris assemble a three-story reading stack that matches your intent and time window.', 'vamika' ); ?></p>
			<button class="ai-inline-btn" type="button"><?php esc_html_e( 'Build stack', 'vamika' ); ?></button>
			<div class="ai-inline-response" style="display:none;">
				<div class="ai-inline-response-head">
					<div class="iris-team-av"><?php vamika_render_iris_icon( false ); ?></div>
					<div class="ai-inline-response-meta">
						<div class="ai-inline-response-name"><?php esc_html_e( 'Iris', 'vamika' ); ?></div>
						<div class="ai-inline-response-sub"><?php esc_html_e( 'Reading stack', 'vamika' ); ?></div>
					</div>
					<button class="ai-inline-close" type="button" aria-label="<?php esc_attr_e( 'Close', 'vamika' ); ?>">&times;</button>
				</div>
				<div class="ai-inline-text"></div>
			</div>
		</div>
		<?php endif; ?>
	</section>

	<section class="category-body">
		<div class="cat-stories container">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'cat-story' ); ?> data-href="<?php the_permalink(); ?>" tabindex="0" role="link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
					<?php vamika_render_post_visual( 'img' ); ?>
					<div class="body">
						<span class="cat-tag"><?php echo esc_html( vamika_get_primary_term_name() ); ?></span>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
						<div class="foot"><span><?php the_author(); ?></span><span><?php echo esc_html( strtoupper( str_replace( ' read', '', vamika_get_post_reading_time() ) . ' · ' . get_the_date( 'M j' ) ) ); ?></span></div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="load-more-row">
			<?php the_posts_pagination( array( 'prev_text' => '←', 'next_text' => '→' ) ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
