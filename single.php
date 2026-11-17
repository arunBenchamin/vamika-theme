<?php
/**
 * Single post template.
 *
 * @package Vamika
 */

get_header();
?>
<main id="content" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php $primary_category = get_the_category(); ?>
		<div class="breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'vamika' ); ?></a>
			<span class="sep">/</span>
			<a href="<?php echo esc_url( ! empty( $primary_category ) ? get_category_link( $primary_category[0]->term_id ) : ( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ) ); ?>"><?php echo esc_html( vamika_get_primary_term_name() ); ?></a>
			<span class="sep">/</span>
			<span class="curr"><?php the_title(); ?></span>
		</div>

		<header class="article-header">
			<span class="category-tag"><?php echo esc_html( vamika_get_primary_term_name() . ' · ' . __( 'Essay', 'vamika' ) ); ?></span>
			<h1 class="article-title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="article-standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>

			<div class="article-meta">
				<div class="author-mini">
					<div class="avatar"><em><?php echo esc_html( vamika_get_post_initials() ); ?></em></div>
					<div class="info">
						<div class="name"><?php the_author(); ?></div>
						<div class="role"><?php esc_html_e( 'Contributing Editor', 'vamika' ); ?></div>
					</div>
				</div>
				<div class="meta-dot"></div>
				<div class="meta-item"><strong><?php echo esc_html( get_the_date() ); ?></strong></div>
				<div class="meta-dot"></div>
				<div class="meta-item"><strong><?php echo esc_html( str_replace( ' read', '', vamika_get_post_reading_time() ) ); ?></strong> <?php esc_html_e( 'min read', 'vamika' ); ?></div>
				<div class="meta-dot"></div>
				<div class="meta-item"><strong><?php echo esc_html( number_format_i18n( (int) get_post_meta( get_the_ID(), 'vamika_readers', true ) ?: 0 ) ); ?></strong> <?php esc_html_e( 'readers', 'vamika' ); ?></div>
			</div>
		</header>

		<div class="article-hero-img">
			<div class="frame">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'full' ); ?>
				<?php else : ?>
					<?php vamika_render_post_visual( 'story-img' ); ?>
				<?php endif; ?>
			</div>
			<div class="caption"><?php esc_html_e( 'Illustration for Vamika', 'vamika' ); ?> · <em><?php echo esc_html( get_the_date( 'F Y' ) ); ?></em></div>
		</div>

		<?php
		/*
		 * Post summary card.
		 *
		 * When the Iris Theme Kit plugin is active, it renders an
		 * interactive "Iris Summary" card whose Regenerate button hits
		 * the Iris AI REST endpoint. This lives in the plugin per the
		 * WordPress.org theme review "plugin territory" rule.
		 *
		 * When the plugin is inactive, the theme falls back to a plain
		 * static excerpt card below — same visual design, no AI claims,
		 * no JS, no plugin dependency. Graceful degradation.
		 */
		if ( function_exists( 'iris_theme_kit_render_post_summary' ) ) {
			iris_theme_kit_render_post_summary();
		} else {
			?>
			<div class="summary-bar container-narrow">
				<div class="summary-box">
					<div class="summary-header">
						<div class="summary-label">
							<div class="iris-mini" aria-hidden="true">
								<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path class="face" d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z"/>
								</svg>
							</div>
							<span><?php esc_html_e( 'At a glance', 'vamika' ); ?></span>
						</div>
					</div>
					<div class="summary-content">
						<p><?php echo esc_html( has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 40, '…' ) ); ?></p>
						<?php
						$summary_headings = vamika_get_post_headings();
						if ( ! empty( $summary_headings ) ) :
							?>
							<ul>
								<?php foreach ( array_slice( $summary_headings, 0, 3 ) as $sh ) : ?>
									<li><?php echo esc_html( $sh ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>

		<?php
		/*
		 * .article-layout is always a centered container with the
		 * .article-body centered inside it. Keeping .article-body
		 * centered in the viewport is what allows `.alignfull` blocks
		 * (galleries, images, embeds) to break out to the viewport
		 * edges via the standard WP `calc(-50vw + 50%)` trick. The
		 * optional Iris sidebar (rendered below when the plugin is
		 * active) floats to the viewport edge via `position: fixed`
		 * on wide screens — it sits outside the content flow so it
		 * never shifts the article column off-center.
		 */
		?>
		<div class="article-layout">
			<article class="article-body" id="articleBody">
				<div class="share-float">
					<button class="sf-share" type="button" title="<?php esc_attr_e( 'Share', 'vamika' ); ?>" data-url="<?php the_permalink(); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>">↗</button>
					<?php if ( comments_open() ) : ?>
						<button class="sf-comment" type="button" title="<?php esc_attr_e( 'Comment', 'vamika' ); ?>">✎</button>
					<?php endif; ?>
				</div>
				<?php the_content(); ?>
				<?php
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'vamika' ),
						'after'  => '</div>',
					)
				);

				$vamika_tag_list = get_the_tag_list( '', '', '' );
				if ( $vamika_tag_list ) :
					?>
					<div class="post-tags">
						<span class="post-tags__label"><?php esc_html_e( 'Tagged', 'vamika' ); ?></span>
						<div class="post-tags__links"><?php echo wp_kses_post( $vamika_tag_list ); ?></div>
					</div>
					<?php
				endif;
				?>
			</article>

			<?php if ( vamika_has_iris() ) : ?>
			<aside class="iris-sidebar"
				data-iris-context="<?php echo esc_attr( sprintf(
					/* translators: %1$s: post title, %2$s: post excerpt or trimmed content */
					__( 'You are Iris, the AI reading companion for Vamika magazine. The reader is currently viewing the essay titled "%1$s". Here is a brief summary: %2$s. Answer questions about this essay in a warm, concise tone. Use specific details from the article when possible.', 'vamika' ),
					get_the_title(),
					has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 60, '…' )
				) ); ?>"
			>
				<div class="iris-sb-header">
					<div class="iris-sb-avatar"><?php vamika_render_iris_icon(); ?></div>
					<div class="info">
						<div class="name"><?php esc_html_e( 'Ask Iris', 'vamika' ); ?></div>
						<div class="sub"><?php esc_html_e( 'About this article', 'vamika' ); ?></div>
					</div>
				</div>
				<div class="iris-sb-title"><?php esc_html_e( 'I have read this essay. Ask me anything about it - or start with these.', 'vamika' ); ?></div>
				<div class="iris-sb-prompts">
					<button type="button"><?php esc_html_e( 'What is the main argument?', 'vamika' ); ?></button>
					<button type="button"><?php esc_html_e( 'Summarize this in 3 bullets', 'vamika' ); ?></button>
					<button type="button"><?php esc_html_e( 'Who is the main subject here?', 'vamika' ); ?></button>
					<button type="button"><?php esc_html_e( 'Find essays like this one', 'vamika' ); ?></button>
				</div>
				<div class="iris-sb-chat" style="display:none;"></div>
				<div class="iris-sb-input">
					<input type="text" placeholder="<?php esc_attr_e( 'Ask about this article…', 'vamika' ); ?>">
					<button type="button">→</button>
				</div>
				<?php $headings = vamika_get_post_headings(); ?>
				<?php if ( ! empty( $headings ) ) : ?>
					<div class="iris-sb-toc">
						<div class="lbl">✦ <?php esc_html_e( 'In this essay', 'vamika' ); ?></div>
						<ul>
							<li class="active"><?php esc_html_e( 'The opening anecdote', 'vamika' ); ?></li>
							<?php foreach ( array_slice( $headings, 0, 4 ) as $heading ) : ?>
								<li><?php echo esc_html( $heading ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</aside>
			<?php endif; ?>
		</div>

		<section class="author-card-section">
			<div class="author-card">
				<div class="big-avatar"><em><?php echo esc_html( vamika_get_post_initials() ); ?></em></div>
				<div class="details">
					<div class="lbl">— <?php esc_html_e( 'About The Author', 'vamika' ); ?> —</div>
					<h3><?php the_author(); ?></h3>
					<p><?php echo esc_html( get_the_author_meta( 'description' ) ?: __( 'This author writes for Vamika.', 'vamika' ) ); ?></p>
				</div>
				<a class="follow" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php esc_html_e( 'Follow', 'vamika' ); ?> <?php the_author(); ?> →</a>
			</div>
		</section>

		<?php
		/**
		 * Reactions are provided by the Iris Theme Kit companion plugin so that
		 * reaction data (post meta + AJAX endpoint) is not orphaned when a user
		 * switches themes. The theme renders nothing for this section when the
		 * plugin is not active.
		 */
		if ( function_exists( 'iris_theme_kit_render_reactions' ) ) {
			iris_theme_kit_render_reactions( get_the_ID() );
		}
		?>

		<section class="related-section">
			<div class="related-header">
				<?php if ( vamika_has_iris() ) : ?>
					<div class="eyebrow">✦ <?php esc_html_e( 'Iris Recommends', 'vamika' ); ?> ✦</div>
				<?php else : ?>
					<div class="eyebrow">✦ <?php esc_html_e( 'Related Reading', 'vamika' ); ?> ✦</div>
				<?php endif; ?>
				<h2><?php esc_html_e( 'If you liked this,', 'vamika' ); ?> <em><?php esc_html_e( 'read these.', 'vamika' ); ?></em></h2>
			</div>
			<div class="related-scatter">
				<?php
				$related = new WP_Query(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 4,
						'post__not_in'   => array( get_the_ID() ),
						'category__in'   => wp_get_post_categories( get_the_ID() ),
					)
				);

				$vamika_rel_idx = 0;
				if ( $related->have_posts() ) :
					while ( $related->have_posts() ) :
						$related->the_post();
						++$vamika_rel_idx;
						get_template_part( 'template-parts/content', 'related', array( 'index' => $vamika_rel_idx ) );
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>
		</section>

		<section class="keep-reading">
			<div class="inner">
				<h2><?php esc_html_e( 'More from this', 'vamika' ); ?> <em><?php esc_html_e( 'issue.', 'vamika' ); ?></em></h2>
				<?php if ( vamika_has_iris() ) : ?>
					<p><?php esc_html_e( 'You just finished one essay from the archive. Keep reading, or have Iris summarize the rest for you.', 'vamika' ); ?></p>
				<?php else : ?>
					<p><?php esc_html_e( 'You just finished one essay from the archive. Keep reading — there is more to explore.', 'vamika' ); ?></p>
				<?php endif; ?>
				<div class="btn-row">
					<a class="primary" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>"><?php esc_html_e( 'Next Essay', 'vamika' ); ?> →</a>
					<?php if ( vamika_has_iris() ) : ?>
						<a class="secondary" href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( vamika_get_primary_term_name() ) ) ); ?>">✦ <?php esc_html_e( 'Summarize The Issue', 'vamika' ); ?></a>
					<?php else : ?>
						<a class="secondary" href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( vamika_get_primary_term_name() ) ) ); ?>"><?php esc_html_e( 'Browse This Topic', 'vamika' ); ?> →</a>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
