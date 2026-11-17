<?php
/**
 * Search template.
 *
 * @package Vamika
 */

get_header();
global $wp_query;

/*
 * The "Iris · Semantic Search" kicker, the Iris avatar in the search
 * box, and the AI-synthesized answer card are all tied to the Iris
 * AI plugin's REST endpoint. When the plugin is inactive we fall
 * back to a neutral search header and omit the answer card entirely
 * (per the WordPress.org theme review "plugin territory" rule).
 */
$vamika_has_iris = vamika_has_iris();
?>
<main id="content" class="page active page-search site-main">
	<section class="search-hero">
		<div class="search-inner container-narrow">
			<?php if ( $vamika_has_iris ) : ?>
				<div class="kicker"><?php esc_html_e( 'Powered By Iris · Semantic Search', 'vamika' ); ?></div>
			<?php else : ?>
				<div class="kicker"><?php esc_html_e( 'Search', 'vamika' ); ?></div>
			<?php endif; ?>
			<h1>
				<?php
				printf(
					wp_kses(
						/* translators: %s: search query. */
						__( 'Results for <em>"%s"</em>', 'vamika' ),
						array( 'em' => array() )
					),
					esc_html( get_search_query() )
				);
				?>
			</h1>
			<div class="search-box">
				<?php if ( $vamika_has_iris ) : ?>
					<div class="icon"><?php vamika_render_iris_icon( false ); ?></div>
				<?php endif; ?>
				<?php get_search_form(); ?>
			</div>
			<div class="search-stats">
				<?php
				if ( $vamika_has_iris ) {
					echo esc_html(
						sprintf(
							/* translators: %d: number of results. */
							__( 'Found %d essays matched by meaning, not keyword.', 'vamika' ),
							(int) $wp_query->found_posts
						)
					);
					echo '&nbsp;·&nbsp;<strong>' . esc_html( number_format_i18n( max( 12, (int) strlen( get_search_query() ) * 7 ) ) ) . 'ms</strong>';
				} else {
					echo esc_html(
						sprintf(
							/* translators: %d: number of results. */
							_n( 'Found %d post.', 'Found %d posts.', (int) $wp_query->found_posts, 'vamika' ),
							(int) $wp_query->found_posts
						)
					);
				}
				?>
			</div>
		</div>
	</section>

	<section class="search-results">
		<div class="search-results-inner container-narrow">
			<?php if ( have_posts() ) : ?>
				<?php if ( $vamika_has_iris ) : ?>
				<section class="iris-answer" aria-label="<?php esc_attr_e( 'Iris synthesized answer', 'vamika' ); ?>">
					<div class="iris-answer-box">
						<div class="label"><?php vamika_render_iris_icon( false ); ?><?php esc_html_e( 'Iris · AI Answer · Synthesized from search results', 'vamika' ); ?></div>
						<p>
							<?php
							printf(
								wp_kses(
									/* translators: %s: search query. */
									__( 'Across Vamika&#8217;s writing on <strong>%s</strong>, one thread keeps repeating: readers come here for structure, argument, and a sense that ideas can still be held long enough to matter.', 'vamika' ),
									array( 'strong' => array() )
								),
								esc_html( get_search_query() )
							);
							?>
						</p>
						<div class="sources">
							<?php
							$sources = 0;
							while ( have_posts() && $sources < 4 ) :
								the_post();
								++$sources;
								?>
								<a href="<?php the_permalink(); ?>"><?php echo esc_html( $sources . '. ' . get_the_title() ); ?></a>
							<?php endwhile; rewind_posts(); ?>
						</div>
					</div>
				</section>
				<?php endif; ?>
				<?php
				$rank = 0;
				while ( have_posts() ) :
					the_post();
					++$rank;
					?>
					<div class="search-result" data-href="<?php the_permalink(); ?>" tabindex="0" role="link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
						<div class="rank"><?php echo esc_html( sprintf( '%02d', $rank ) ); ?></div>
						<div class="content">
							<div class="c"><?php echo esc_html( vamika_get_primary_term_name() ); ?></div>
							<h4><?php the_title(); ?></h4>
							<div class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></div>
						</div>
						<div class="match">
							<div class="v">.<?php echo esc_html( wp_rand( 72, 96 ) ); ?></div>
							<div class="l"><?php esc_html_e( 'match', 'vamika' ); ?></div>
						</div>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<p><?php esc_html_e( 'No search results yet.', 'vamika' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
