<?php
/**
 * Template Name: Homepage
 * Template Post Type: page
 *
 * The magazine-style homepage with hero, featured posts, related
 * section, and newsletter. Users assign this template to a page
 * and set that page as the static front page in Settings → Reading.
 *
 * @package Vamika
 */

get_header();

$featured_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => true,
	)
);

$semantic_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 4,
		'offset'              => 1,
		'ignore_sticky_posts' => true,
	)
);

$post_counts   = wp_count_posts();
$publish_count = isset( $post_counts->publish ) ? (int) $post_counts->publish : 0;
$user_data     = count_users();
$total_users   = isset( $user_data['total_users'] ) ? (int) $user_data['total_users'] : 0;
$term_count    = (int) wp_count_terms( array( 'taxonomy' => 'category', 'hide_empty' => true ) );

$vamika_has_iris = vamika_has_iris();

/* ---- Hero ---- */
$hero_sticker   = get_theme_mod( 'vamika_hero_sticker_one', __( 'New issue out now', 'vamika' ) );
$hero_eyebrow   = get_theme_mod( 'vamika_hero_eyebrow', __( 'THE READING MAGAZINE', 'vamika' ) );
$hero_title_one = get_theme_mod( 'vamika_hero_title_one', __( 'Read less.', 'vamika' ) );
$hero_title_two = get_theme_mod( 'vamika_hero_title_two', __( 'Understand more.', 'vamika' ) );

$title_one_parts = preg_split( '/\s+/', trim( $hero_title_one ) );
$title_two_parts = preg_split( '/\s+/', trim( $hero_title_two ) );

/* ---- Stats ---- */
$stat_1_label = get_theme_mod( 'vamika_hero_stat_1_label', __( 'essays published', 'vamika' ) );
$stat_2_label = get_theme_mod( 'vamika_hero_stat_2_label', __( 'active sections', 'vamika' ) );
$stat_3_label = get_theme_mod( 'vamika_hero_stat_3_label', __( 'site users', 'vamika' ) );

/* ---- Marquee ---- */
$marquee_phrases = array(
	get_theme_mod( 'vamika_marquee_1', __( 'Read slowly', 'vamika' ) ),
	get_theme_mod( 'vamika_marquee_2', __( 'Think longer', 'vamika' ) ),
	get_theme_mod( 'vamika_marquee_3', __( 'Stay curious', 'vamika' ) ),
	get_theme_mod( 'vamika_marquee_4', __( 'Finish the essay', 'vamika' ) ),
	get_theme_mod( 'vamika_marquee_5', __( 'Ask better questions', 'vamika' ) ),
);

/* ---- Featured ---- */
$featured_number   = get_theme_mod( 'vamika_featured_number', __( '01', 'vamika' ) );
$featured_title    = get_theme_mod( 'vamika_featured_title', __( 'This week,', 'vamika' ) );
$featured_title_em = get_theme_mod( 'vamika_featured_title_em', __( 'in Vamika', 'vamika' ) );
$featured_link     = get_theme_mod( 'vamika_featured_link', __( 'See all', 'vamika' ) );

/* ---- Semantic ---- */
$semantic_eyebrow        = get_theme_mod( 'vamika_semantic_eyebrow', __( 'Connected By Meaning', 'vamika' ) );
$semantic_heading        = get_theme_mod( 'vamika_semantic_heading', __( 'Related, by', 'vamika' ) );
$semantic_heading_em     = get_theme_mod( 'vamika_semantic_heading_em', __( 'idea', 'vamika' ) );
$semantic_heading_suffix = get_theme_mod( 'vamika_semantic_heading_suffix', __( '- not by tag.', 'vamika' ) );
$semantic_reading_label  = get_theme_mod( 'vamika_semantic_reading_label', __( 'Latest Post', 'vamika' ) );

/* ---- Newsletter ---- */
$newsletter_eyebrow    = get_theme_mod( 'vamika_newsletter_eyebrow', __( 'The Weekly Letter', 'vamika' ) );
$newsletter_heading    = get_theme_mod( 'vamika_newsletter_heading', __( 'Three stories, every Sunday.', 'vamika' ) );
$newsletter_text       = get_theme_mod( 'vamika_newsletter_text', __( 'Curated by the Vamika editors. One essay, one idea, one thing worth reading.', 'vamika' ) );
$newsletter_button     = get_theme_mod( 'vamika_newsletter_button', __( 'Subscribe', 'vamika' ) );
$newsletter_button_url = get_theme_mod( 'vamika_newsletter_button_url', home_url( '/contact/' ) );
$newsletter_fine_print = get_theme_mod( 'vamika_newsletter_fine_print', __( 'Free - unsubscribe anytime', 'vamika' ) );

/* ---- Features (Iris AI) ---- */
$features_eyebrow     = get_theme_mod( 'vamika_features_eyebrow', __( 'Powered By Iris', 'vamika' ) );
$features_heading     = get_theme_mod( 'vamika_features_heading', __( 'Five AI tools,', 'vamika' ) );
$features_heading_2   = get_theme_mod( 'vamika_features_heading_2', __( 'built into the', 'vamika' ) );
$features_heading_em  = get_theme_mod( 'vamika_features_heading_em', __( 'reading.', 'vamika' ) );
$features_description = get_theme_mod( 'vamika_features_description', __( 'Vamika is not a magazine with AI bolted on. Every feature is designed to disappear into the experience of reading.', 'vamika' ) );

$feat_cards = array(
	array(
		'style'      => 'feat-style-hero',
		'num'        => '01',
		'label'      => get_theme_mod( 'vamika_feat_1_label', __( 'Chat Assistant', 'vamika' ) ),
		'heading'    => get_theme_mod( 'vamika_feat_1_heading', __( 'Ask any article', 'vamika' ) ),
		'heading_em' => get_theme_mod( 'vamika_feat_1_heading_em', __( 'anything.', 'vamika' ) ),
		'text'       => get_theme_mod( 'vamika_feat_1_text', __( 'Every essay becomes a conversation.', 'vamika' ) ),
	),
	array(
		'style'      => 'feat-style-sun',
		'num'        => '02',
		'label'      => get_theme_mod( 'vamika_feat_2_label', __( 'Summaries', 'vamika' ) ),
		'heading'    => get_theme_mod( 'vamika_feat_2_heading', __( 'Summaries, done', 'vamika' ) ),
		'heading_em' => get_theme_mod( 'vamika_feat_2_heading_em', __( 'right.', 'vamika' ) ),
		'text'       => get_theme_mod( 'vamika_feat_2_text', __( 'One-click summaries that stay faithful to the writer.', 'vamika' ) ),
	),
	array(
		'style'      => 'feat-style-cobalt',
		'num'        => '03',
		'label'      => get_theme_mod( 'vamika_feat_3_label', __( 'Semantic Search', 'vamika' ) ),
		'heading'    => get_theme_mod( 'vamika_feat_3_heading', __( 'Search by', 'vamika' ) ),
		'heading_em' => get_theme_mod( 'vamika_feat_3_heading_em', __( 'meaning.', 'vamika' ) ),
		'text'       => get_theme_mod( 'vamika_feat_3_text', __( 'Find essays by idea, not just keyword.', 'vamika' ) ),
	),
	array(
		'style'      => 'feat-style-mint',
		'num'        => '04',
		'label'      => get_theme_mod( 'vamika_feat_4_label', __( 'Insights', 'vamika' ) ),
		'heading'    => get_theme_mod( 'vamika_feat_4_heading', __( 'Editor', 'vamika' ) ),
		'heading_em' => get_theme_mod( 'vamika_feat_4_heading_em', __( 'dashboard.', 'vamika' ) ),
		'text'       => get_theme_mod( 'vamika_feat_4_text', __( 'Track what readers are asking and what to publish next.', 'vamika' ) ),
	),
	array(
		'style'      => 'feat-style-plum',
		'num'        => '05',
		'label'      => get_theme_mod( 'vamika_feat_5_label', __( 'Smart Suggestions', 'vamika' ) ),
		'heading'    => get_theme_mod( 'vamika_feat_5_heading', __( 'The right read,', 'vamika' ) ),
		'heading_em' => get_theme_mod( 'vamika_feat_5_heading_em', __( 'at the right sentence.', 'vamika' ) ),
		'text'       => get_theme_mod( 'vamika_feat_5_text', __( 'Contextual recommendations that stay inside the flow of reading.', 'vamika' ) ),
	),
);
?>
<main id="content" class="site-main">
	<section class="hero">
		<?php if ( ! empty( $hero_sticker ) ) : ?>
			<div class="hero-sticker sticker-1"><?php echo esc_html( $hero_sticker ); ?></div>
		<?php endif; ?>
		<div class="hero-sticker sticker-2"><?php echo esc_html( sprintf( __( '%s posts live', 'vamika' ), $publish_count ) ); ?></div>
		<div class="hero-grid">
			<div class="hero-eyebrow">
				<span class="chip"><span class="live-dot"></span><?php echo esc_html( $hero_eyebrow ); ?></span>
			</div>

			<h1 class="hero-title">
				<?php foreach ( $title_one_parts as $part ) : ?>
					<span class="word"><?php echo esc_html( $part ); ?></span>
				<?php endforeach; ?>
				<br>
				<?php if ( ! empty( $title_two_parts[0] ) ) : ?>
					<span class="word"><span class="highlight"><?php echo esc_html( $title_two_parts[0] ); ?></span></span>
				<?php endif; ?>
				<?php if ( ! empty( $title_two_parts[1] ) ) : ?>
					<span class="word"><span class="serif-it"><?php echo esc_html( $title_two_parts[1] ); ?></span></span>
				<?php endif; ?>
			</h1>

			<p class="hero-sub">
				<?php echo esc_html( get_bloginfo( 'description' ) ); ?>
			</p>

			<?php
			/*
			 * Chat card: when the Iris Theme Kit plugin is active it
			 * renders the full AI-powered card. Otherwise the theme
			 * shows a static card with the same visual design — prompts
			 * link to search, no AI claims, no JS dependency.
			 */
			if ( vamika_has_iris() && function_exists( 'iris_theme_kit_render_hero_chat' ) ) :
				iris_theme_kit_render_hero_chat( array( 'articles_count' => $publish_count ) );
			else :
				$card_name        = get_theme_mod( 'vamika_hero_card_name', __( 'Vamika', 'vamika' ) );
				$card_subtitle    = get_theme_mod( 'vamika_hero_card_subtitle', __( 'Reading companion', 'vamika' ) );
				$card_placeholder = get_theme_mod( 'vamika_hero_card_placeholder', __( 'Search essays, ideas, or writers...', 'vamika' ) );
				$prompts          = array(
					array(
						'emoji' => '✦',
						'label' => get_theme_mod( 'vamika_hero_prompt_1_label', __( 'Latest', 'vamika' ) ),
						'text'  => get_theme_mod( 'vamika_hero_prompt_1_text', __( 'What is the latest essay about?', 'vamika' ) ),
					),
					array(
						'emoji' => '◐',
						'label' => get_theme_mod( 'vamika_hero_prompt_2_label', __( 'Discover', 'vamika' ) ),
						'text'  => get_theme_mod( 'vamika_hero_prompt_2_text', __( 'What should I read about attention and focus?', 'vamika' ) ),
					),
					array(
						'emoji' => '↗',
						'label' => get_theme_mod( 'vamika_hero_prompt_3_label', __( 'Popular', 'vamika' ) ),
						'text'  => get_theme_mod( 'vamika_hero_prompt_3_text', __( 'Show me the most popular essays', 'vamika' ) ),
					),
					array(
						'emoji' => '✷',
						'label' => get_theme_mod( 'vamika_hero_prompt_4_label', __( 'Archive', 'vamika' ) ),
						'text'  => get_theme_mod( 'vamika_hero_prompt_4_text', __( 'Browse the full archive by topic', 'vamika' ) ),
					),
				);
			?>
			<div class="chat-card">
				<div class="chat-topbar">
					<div class="chat-topbar-left">
						<div class="iris-avatar"><?php vamika_render_iris_icon( true ); ?></div>
						<div>
							<div class="name"><?php echo esc_html( $card_name ); ?></div>
							<div class="meta"><?php echo esc_html( $card_subtitle ); ?></div>
						</div>
					</div>
					<div class="chat-topbar-right">
						<span class="pill active">● <?php esc_html_e( 'LIVE', 'vamika' ); ?></span>
						<span class="pill">
							<?php
							echo esc_html(
								sprintf(
									_n( '%d ARTICLE', '%d ARTICLES', $publish_count, 'vamika' ),
									$publish_count
								)
							);
							?>
						</span>
					</div>
				</div>
				<div class="prompt-grid">
					<?php foreach ( $prompts as $prompt ) : ?>
						<button class="prompt-btn" type="button" data-search="<?php echo esc_attr( $prompt['text'] ); ?>">
							<span class="emoji"><?php echo esc_html( $prompt['emoji'] ); ?></span>
							<span class="txt"><small><?php echo esc_html( $prompt['label'] ); ?></small><?php echo esc_html( $prompt['text'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
				<?php
				get_search_form(
					array(
						'vamika_variant' => 'chat',
						'placeholder'    => $card_placeholder,
					)
				);
				?>
			</div>
			<?php endif; ?>

			<div class="chat-stats">
				<div class="chat-stat"><div class="n"><?php echo esc_html( number_format_i18n( $publish_count ) ); ?></div><div class="l"><?php echo esc_html( $stat_1_label ); ?></div></div>
				<div class="chat-stat"><div class="n"><?php echo esc_html( number_format_i18n( $term_count ) ); ?></div><div class="l"><?php echo esc_html( $stat_2_label ); ?></div></div>
				<div class="chat-stat"><div class="n"><?php echo esc_html( number_format_i18n( $total_users ) ); ?></div><div class="l"><?php echo esc_html( $stat_3_label ); ?></div></div>
			</div>
		</div>
	</section>

	<div class="marquee">
		<div class="marquee-track">
			<?php foreach ( $marquee_phrases as $phrase ) : ?>
				<span><?php echo esc_html( $phrase ); ?></span>
			<?php endforeach; ?>
			<?php /* duplicate for seamless scroll */ ?>
			<?php foreach ( $marquee_phrases as $phrase ) : ?>
				<span><?php echo esc_html( $phrase ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>

	<section class="featured">
		<div class="section-title">
			<div class="t"><span class="num"><?php echo esc_html( $featured_number ); ?></span><?php echo esc_html( $featured_title ); ?> <em><?php echo esc_html( $featured_title_em ); ?></em></div>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>" class="more"><?php echo esc_html( $featured_link ); ?> →</a>
		</div>
		<div class="stories-grid">
			<?php
			if ( $featured_query->have_posts() ) :
				$index = 0;
				while ( $featured_query->have_posts() ) :
					$featured_query->the_post();
					++$index;
					get_template_part( 'template-parts/content', 'card', array( 'featured' => 1 === $index ) );
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</section>

	<?php
	/*
	 * The "Five AI tools" section describes features that only
	 * exist when the Iris AI companion plugin is active. Hide
	 * when inactive — WordPress.org "plugin territory" rule.
	 */
	if ( $vamika_has_iris ) :
	?>
	<section class="features">
		<div class="intro">
			<span class="eyebrow">✦ <?php echo esc_html( $features_eyebrow ); ?> ✦</span>
			<h2><?php echo esc_html( $features_heading ); ?><br><?php echo esc_html( $features_heading_2 ); ?> <em><?php echo esc_html( $features_heading_em ); ?></em></h2>
			<p><?php echo esc_html( $features_description ); ?></p>
		</div>
		<div class="feature-grid">
			<?php foreach ( $feat_cards as $card ) : ?>
				<div class="feat <?php echo esc_attr( $card['style'] ); ?>">
					<div class="label"><?php echo esc_html( $card['num'] ); ?> · <?php echo esc_html( $card['label'] ); ?></div>
					<h3><?php echo esc_html( $card['heading'] ); ?> <em><?php echo esc_html( $card['heading_em'] ); ?></em></h3>
					<p><?php echo esc_html( $card['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php
	$latest_post  = get_posts( array( 'numberposts' => 1, 'post_type' => 'post' ) );
	$latest_title = ! empty( $latest_post ) ? get_the_title( $latest_post[0] ) : get_bloginfo( 'name' );
	?>
	<section class="semantic">
		<div class="semantic-intro">
			<div class="eyebrow"><?php echo esc_html( $semantic_eyebrow ); ?></div>
			<h2><?php echo esc_html( $semantic_heading ); ?> <em><?php echo esc_html( $semantic_heading_em ); ?></em> <?php echo esc_html( $semantic_heading_suffix ); ?></h2>
		</div>
		<div class="reading-now">
			<div class="rnlbl"><?php echo esc_html( $semantic_reading_label ); ?></div>
			<?php if ( ! empty( $latest_post ) ) : ?>
				<a href="<?php echo esc_url( get_permalink( $latest_post[0] ) ); ?>" class="rntxt"><?php echo esc_html( $latest_title ); ?></a>
			<?php else : ?>
				<div class="rntxt"><?php echo esc_html( $latest_title ); ?></div>
			<?php endif; ?>
		</div>
		<div class="related-scatter">
			<?php
			$vamika_rel_idx = 0;
			if ( $semantic_query->have_posts() ) :
				while ( $semantic_query->have_posts() ) :
					$semantic_query->the_post();
					++$vamika_rel_idx;
					get_template_part( 'template-parts/content', 'related', array( 'index' => $vamika_rel_idx ) );
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</section>

	<section class="newsletter">
		<div class="inner">
			<div class="ey">— <?php echo esc_html( $newsletter_eyebrow ); ?> —</div>
			<h2><?php echo esc_html( $newsletter_heading ); ?></h2>
			<p><?php echo esc_html( $newsletter_text ); ?></p>
			<div class="newsletter-form">
				<a class="newsletter-btn" href="<?php echo esc_url( $newsletter_button_url ); ?>"><?php echo esc_html( $newsletter_button ); ?></a>
			</div>
			<div class="fine"><?php echo esc_html( $newsletter_fine_print ); ?></div>
		</div>
	</section>
</main>
<?php
get_footer();
