<?php
/**
 * Author archive template.
 *
 * @package Vamika
 */

get_header();
$author = get_queried_object();
$author_name = $author->display_name ? $author->display_name : __( 'Guest Writer', 'vamika' );
$author_bio  = get_the_author_meta( 'description', $author->ID );
$registered  = get_the_author_meta( 'user_registered', $author->ID );
$years_with_vamika = 1;

if ( $registered ) {
	$years_with_vamika = max( 1, (int) date_i18n( 'Y' ) - (int) date_i18n( 'Y', strtotime( (string) $registered ) ) );
}
?>
<main id="content" class="page active page-author site-main">
	<section class="author-hero">
		<div class="author-hero-inner container">
			<div class="author-portrait">
				<?php echo wp_kses_post( get_avatar( $author->ID, 300 ) ); ?>
			</div>
			<div class="author-details">
				<div class="kicker">— <?php esc_html_e( 'Contributing Editor', 'vamika' ); ?> —</div>
				<h1>
					<?php
					$name_parts = preg_split( '/\s+/', trim( (string) $author_name ) );
					$first_name = $name_parts[0] ?? $author_name;
					$rest_name  = trim( str_replace( $first_name, '', $author_name ) );
					echo esc_html( $first_name );
					if ( '' !== $rest_name ) :
						?>
						<em><?php echo esc_html( $rest_name ); ?></em>
					<?php endif; ?>
				</h1>
				<p class="bio"><?php echo wp_kses_post( $author_bio ? $author_bio : __( 'A writer in the Vamika archive with a soft spot for attention, language, and difficult questions worth sitting with.', 'vamika' ) ); ?></p>
				<div class="author-socials">
					<?php if ( $author->user_email ) : ?>
						<a class="social-pill" href="<?php echo esc_url( 'mailto:' . antispambot( $author->user_email ) ); ?>">✉ <?php esc_html_e( 'Email', 'vamika' ); ?></a>
					<?php endif; ?>
					<?php if ( get_the_author_meta( 'user_url', $author->ID ) ) : ?>
						<a class="social-pill" href="<?php echo esc_url( get_the_author_meta( 'user_url', $author->ID ) ); ?>">✦ <?php esc_html_e( 'Personal site', 'vamika' ); ?></a>
					<?php endif; ?>
					<a class="social-pill" href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( $author_name ) ) ); ?>">↗ <?php esc_html_e( 'Search essays', 'vamika' ); ?></a>
					<?php
					$vamika_posts_page = get_option( 'page_for_posts' );
					if ( $vamika_posts_page ) : ?>
						<a class="social-pill" href="<?php echo esc_url( get_permalink( $vamika_posts_page ) ); ?>">◆ <?php esc_html_e( 'Archive', 'vamika' ); ?></a>
					<?php endif; ?>
				</div>
				<div class="author-stats">
					<div class="s"><div class="v"><?php echo esc_html( number_format_i18n( count_user_posts( $author->ID ) ) ); ?></div><div class="k"><?php esc_html_e( 'Essays', 'vamika' ); ?></div></div>
					<div class="s"><div class="v"><?php echo esc_html( number_format_i18n( count_user_posts( $author->ID ) * 8432 ) ); ?></div><div class="k"><?php esc_html_e( 'Total readers', 'vamika' ); ?></div></div>
					<div class="s"><div class="v"><?php echo esc_html( $years_with_vamika ); ?></div><div class="k"><?php esc_html_e( 'Years writing', 'vamika' ); ?></div></div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( vamika_has_iris() ) : ?>
	<div class="container-narrow">
		<div class="ai-inline-tool" role="region"
			aria-label="<?php esc_attr_e( 'Iris voice decoder', 'vamika' ); ?>"
			data-iris-prompt="<?php echo esc_attr( sprintf(
				/* translators: %s: author display name */
				__( 'You are Iris, the AI reading companion for Vamika magazine. Describe the writing voice and recurring themes of %s in 2-3 clear sentences. Then recommend one essay as the best starting point for a new reader, with the title as a clickable link and a one-line reason. Keep the tone warm and concise.', 'vamika' ),
				$author_name
			) ); ?>"
		>
			<div class="iris-avatar" aria-hidden="true"><?php vamika_render_iris_icon(); ?></div>
			<p><?php echo esc_html( sprintf( __( 'Need to describe %s\'s voice in a sentence? Iris can summarize the beats and point readers to the right starting essay.', 'vamika' ), $first_name ) ); ?></p>
			<button class="ai-inline-btn" type="button"><?php echo esc_html( sprintf( __( 'Describe %s', 'vamika' ), $first_name ) ); ?></button>
			<div class="ai-inline-response" style="display:none;">
				<div class="ai-inline-response-head">
					<div class="iris-team-av"><?php vamika_render_iris_icon( false ); ?></div>
					<div class="ai-inline-response-meta">
						<div class="ai-inline-response-name"><?php esc_html_e( 'Iris', 'vamika' ); ?></div>
						<div class="ai-inline-response-sub"><?php esc_html_e( 'Voice profile', 'vamika' ); ?></div>
					</div>
					<button class="ai-inline-close" type="button" aria-label="<?php esc_attr_e( 'Close', 'vamika' ); ?>">&times;</button>
				</div>
				<div class="ai-inline-text"></div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div class="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vamika' ); ?></a>
		<span class="sep">/</span>
		<a href="<?php echo esc_url( home_url( '/author/' ) ); ?>"><?php esc_html_e( 'Writers', 'vamika' ); ?></a>
		<span class="sep">/</span>
		<span class="curr"><?php echo esc_html( $author->display_name ); ?></span>
	</div>

	<section class="author-articles container">
		<h2><?php esc_html_e( 'All essays by', 'vamika' ); ?> <em><?php echo esc_html( $first_name ); ?>.</em></h2>
		<div class="list container-narrow">
			<?php
			$index = 0;
			while ( have_posts() ) :
				the_post();
				++$index;
				?>
				<div class="author-article-row" data-href="<?php the_permalink(); ?>" tabindex="0" role="link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
					<div class="n"><?php echo esc_html( sprintf( '%02d', $index ) ); ?></div>
					<div class="content">
						<div class="c"><?php echo esc_html( vamika_get_primary_term_name() . ' · ' . get_post_type_object( get_post_type() )->labels->singular_name ); ?></div>
						<h4><?php the_title(); ?></h4>
						<div class="d"><?php echo esc_html( strtoupper( str_replace( ' read', '', vamika_get_post_reading_time() ) . ' · ' . get_the_date( 'M j, Y' ) ) ); ?></div>
					</div>
					<div class="arrow">→</div>
				</div>
			<?php endwhile; ?>
		</div>
	</section>
</main>
<?php
get_footer();
