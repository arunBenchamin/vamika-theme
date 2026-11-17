<?php
/**
 * Theme bootstrap.
 *
 * @package Vamika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VAMIKA_VERSION', '0.9.1' );
define( 'VAMIKA_THEME_DIR', get_template_directory() );
define( 'VAMIKA_THEME_URI', get_template_directory_uri() );

require_once VAMIKA_THEME_DIR . '/inc/template-tags.php';
require_once VAMIKA_THEME_DIR . '/inc/class-tgm-plugin-activation.php';

/**
 * Register recommended plugins via TGM Plugin Activation.
 *
 * Recommends (but does not require) the Iris Theme Kit companion plugin
 * and the Iris AI plugin for full theme functionality.
 *
 * @since 1.0.0
 * @return void
 */
add_action( 'tgmpa_register', 'vamika_register_recommended_plugins' );

function vamika_register_recommended_plugins() {

	$plugins = array(
		array(
			'name'         => 'Iris Theme Kit',
			'slug'         => 'iris-theme-kit',
			'source'       => 'repo',
			'required'     => false,
			'external_url' => 'https://wordpress.org/plugins/iris-theme-kit/',
		),
		array(
			'name'     => 'Iris AI – AI Homepage, Chatbot Site Assistant',
			'slug'     => 'iris-ai',
			'required' => false,
		),
	);

	$config = array(
		'id'           => 'vamika',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'is_automatic' => false,
	);

	tgmpa( $plugins, $config );
}


if ( ! isset( $content_width ) ) {
	$content_width = 740;
}

function vamika_setup() {
	load_theme_textdomain( 'vamika', VAMIKA_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style', 'search-form' ) );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'custom-background' );
	add_theme_support(
		'custom-header',
		array(
			'width'         => 1600,
			'height'        => 900,
			'flex-width'    => true,
			'flex-height'   => true,
			'header-text'   => false,
			'default-image' => '',
		)
	);
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/vamika.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'vamika' ),
			'footer'  => __( 'Footer Menu', 'vamika' ),
		)
	);
	/*
	 * Footer links use the "Footer Menu" location. Menu items are
	 * automatically split into three columns in footer.php.
	 */
}
add_action( 'after_setup_theme', 'vamika_setup' );

function vamika_register_block_extensions() {
	if ( function_exists( 'register_block_style' ) ) {
		register_block_style(
			'core/quote',
			array(
				'name'  => 'vamika-editorial',
				'label' => __( 'Editorial', 'vamika' ),
			)
		);

		register_block_style(
			'core/image',
			array(
				'name'  => 'vamika-framed',
				'label' => __( 'Framed', 'vamika' ),
			)
		);
	}

	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'vamika',
			array( 'label' => __( 'Vamika', 'vamika' ) )
		);
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern(
			'vamika/editorial-intro',
			array(
				'title'      => __( 'Editorial intro', 'vamika' ),
				'categories' => array( 'vamika' ),
				'content'    => '<!-- wp:group {"align":"wide","className":"vamika-pattern-intro"} --><div class="wp-block-group alignwide vamika-pattern-intro"><!-- wp:heading {"level":2} --><h2>' . esc_html__( 'Start with the argument.', 'vamika' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'A concise introduction for essays, interviews, and editorial notes.', 'vamika' ) . '</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			)
		);
	}
}
add_action( 'init', 'vamika_register_block_extensions' );

/**
 * Whether AI-powered features should render on the front end.
 *
 * Returns true only when the Iris AI plugin is installed AND the
 * Iris Theme Kit dashboard toggle "Enable AI Features" is on.
 * When the Theme Kit plugin is not installed we fall back to the
 * raw IRISAI_VERSION check so the theme still works standalone
 * with just the AI plugin.
 *
 * Templates should call `vamika_has_iris()` instead of
 * `defined( 'IRISAI_VERSION' )` so the dashboard toggle works.
 *
 * @return bool
 */
function vamika_has_iris() {
	if ( function_exists( 'iris_theme_kit_is_iris_ai_active' ) ) {
		return iris_theme_kit_is_iris_ai_active();
	}

	// Fallback: Theme Kit not installed — check the raw constant.
	return defined( 'IRISAI_VERSION' );
}

/**
 * Add a `has-iris-sidebar` body class on single posts when the Iris AI
 * companion plugin is active. The CSS uses this class to switch the
 * article-layout into a 2-column grid (article + sticky sidebar) on wide
 * screens. Without the plugin, the class is absent and the article stays
 * a single fluid column — no empty grid track.
 */
function vamika_body_classes( $classes ) {
	if ( vamika_has_iris() && is_singular( 'post' ) ) {
		$classes[] = 'has-iris-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'vamika_body_classes' );

/**
 * Register local web fonts.
 *
 * Fonts are bundled as variable-font woff2 files under assets/fonts/
 * so no external CDN request is made (GDPR-safe, offline-capable).
 *
 * Child themes or plugins can disable font loading:
 *
 *     add_filter( 'vamika_load_fonts', '__return_false' );
 *
 * @since 1.0.0
 *
 * @return void
 */
function vamika_register_fonts() {
	if ( ! apply_filters( 'vamika_load_fonts', true ) ) {
		return;
	}

	wp_register_style(
		'vamika-fonts',
		VAMIKA_THEME_URI . '/assets/css/fonts.css',
		array(),
		VAMIKA_VERSION
	);
}
add_action( 'init', 'vamika_register_fonts' );

function vamika_enqueue_assets() {
	$theme_css_path = VAMIKA_THEME_DIR . '/assets/css/vamika.css';

	$font_deps = array();
	if ( wp_style_is( 'vamika-fonts', 'registered' ) ) {
		wp_enqueue_style( 'vamika-fonts' );
		$font_deps[] = 'vamika-fonts';
	}

	wp_enqueue_style(
		'vamika-style',
		VAMIKA_THEME_URI . '/assets/css/vamika.css',
		$font_deps,
		file_exists( $theme_css_path ) ? (string) filemtime( $theme_css_path ) : VAMIKA_VERSION
	);

	if ( is_front_page() || is_home() ) {
		vamika_enqueue_script( 'vamika-magazine', '/assets/js/vamika-magazine.js' );
	} elseif ( is_single() ) {
		vamika_enqueue_script( 'vamika-post', '/assets/js/vamika-post.js' );
	} else {
		vamika_enqueue_script( 'vamika-pages', '/assets/js/vamika-pages.js' );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vamika_enqueue_assets' );

function vamika_enqueue_editor_assets() {
	$theme_css_path = VAMIKA_THEME_DIR . '/assets/css/vamika.css';

	$font_deps = array();
	if ( wp_style_is( 'vamika-fonts', 'registered' ) ) {
		wp_enqueue_style( 'vamika-fonts' );
		$font_deps[] = 'vamika-fonts';
	}

	wp_enqueue_style(
		'vamika-style',
		VAMIKA_THEME_URI . '/assets/css/vamika.css',
		$font_deps,
		file_exists( $theme_css_path ) ? (string) filemtime( $theme_css_path ) : VAMIKA_VERSION
	);

	$services_css_path = VAMIKA_THEME_DIR . '/assets/css/services.css';
	if ( file_exists( $services_css_path ) ) {
		wp_enqueue_style(
			'vamika-services',
			VAMIKA_THEME_URI . '/assets/css/services.css',
			array( 'vamika-style' ),
			(string) filemtime( $services_css_path )
		);
	}
}
add_action( 'elementor/editor/after_enqueue_styles', 'vamika_enqueue_editor_assets' );
add_action( 'elementor/preview/enqueue_styles', 'vamika_enqueue_editor_assets' );

function vamika_enqueue_script( $handle, $relative_path ) {
	$path = VAMIKA_THEME_DIR . $relative_path;

	wp_enqueue_script(
		$handle,
		VAMIKA_THEME_URI . $relative_path,
		array(),
		file_exists( $path ) ? (string) filemtime( $path ) : VAMIKA_VERSION,
		true
	);
}

function vamika_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'vamika' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Optional sidebar for additional widgets.', 'vamika' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	/*
	 * Footer links are driven by the "Footer Menu" nav menu location
	 * (Appearance → Menus). The menu items are split evenly into three
	 * columns in footer.php.
	 */
}
add_action( 'widgets_init', 'vamika_widgets_init' );

function vamika_customize_register( $wp_customize ) {

	/* ---- Hero Section ---- */
	$wp_customize->add_section(
		'vamika_hero_options',
		array(
			'title'    => __( 'Hero', 'vamika' ),
			'priority' => 25,
		)
	);

	$hero_settings = array(
		'vamika_hero_sticker_one'      => array( __( 'New issue out now', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_eyebrow'          => array( __( 'THE READING MAGAZINE', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_title_one'        => array( __( 'Read less.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_title_two'        => array( __( 'Understand more.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_card_name'        => array( __( 'Vamika', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_card_subtitle'    => array( __( 'Reading companion', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_card_placeholder' => array( __( 'Search essays, ideas, or writers...', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_1_label'   => array( __( 'Latest', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_1_text'    => array( __( 'What is the latest essay about?', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_2_label'   => array( __( 'Discover', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_2_text'    => array( __( 'What should I read about attention and focus?', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_3_label'   => array( __( 'Popular', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_3_text'    => array( __( 'Show me the most popular essays', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_4_label'   => array( __( 'Archive', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_prompt_4_text'    => array( __( 'Browse the full archive by topic', 'vamika' ), 'sanitize_text_field' ),
	);

	foreach ( $hero_settings as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $config[0],
				'sanitize_callback' => $config[1],
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => ucwords( str_replace( '_', ' ', str_replace( 'vamika_hero_', '', $setting_id ) ) ),
				'section' => 'vamika_hero_options',
				'type'    => 'text',
			)
		);
	}

	/* ---- Hero Stats ---- */
	$hero_stat_settings = array(
		'vamika_hero_stat_1_label' => array( __( 'essays published', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_stat_2_label' => array( __( 'active sections', 'vamika' ), 'sanitize_text_field' ),
		'vamika_hero_stat_3_label' => array( __( 'site users', 'vamika' ), 'sanitize_text_field' ),
	);

	foreach ( $hero_stat_settings as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $config[0],
				'sanitize_callback' => $config[1],
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => ucwords( str_replace( '_', ' ', str_replace( 'vamika_hero_', '', $setting_id ) ) ),
				'section' => 'vamika_hero_options',
				'type'    => 'text',
			)
		);
	}

	/* ---- Marquee Section ---- */
	$wp_customize->add_section(
		'vamika_marquee_options',
		array(
			'title'    => __( 'Marquee', 'vamika' ),
			'priority' => 26,
		)
	);

	$marquee_defaults = array(
		'vamika_marquee_1' => __( 'Read slowly', 'vamika' ),
		'vamika_marquee_2' => __( 'Think longer', 'vamika' ),
		'vamika_marquee_3' => __( 'Stay curious', 'vamika' ),
		'vamika_marquee_4' => __( 'Finish the essay', 'vamika' ),
		'vamika_marquee_5' => __( 'Ask better questions', 'vamika' ),
	);

	foreach ( $marquee_defaults as $setting_id => $default ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$num = str_replace( 'vamika_marquee_', '', $setting_id );
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => sprintf( __( 'Phrase %s', 'vamika' ), $num ),
				'section' => 'vamika_marquee_options',
				'type'    => 'text',
			)
		);
	}

	/* ---- Featured Section ---- */
	$wp_customize->add_section(
		'vamika_featured_options',
		array(
			'title'    => __( 'Featured Section', 'vamika' ),
			'priority' => 27,
		)
	);

	$featured_settings = array(
		'vamika_featured_number'   => array( __( '01', 'vamika' ), 'sanitize_text_field' ),
		'vamika_featured_title'    => array( __( 'This week,', 'vamika' ), 'sanitize_text_field' ),
		'vamika_featured_title_em' => array( __( 'in Vamika', 'vamika' ), 'sanitize_text_field' ),
		'vamika_featured_link'     => array( __( 'See all', 'vamika' ), 'sanitize_text_field' ),
	);

	foreach ( $featured_settings as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $config[0],
				'sanitize_callback' => $config[1],
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => ucwords( str_replace( '_', ' ', str_replace( 'vamika_featured_', '', $setting_id ) ) ),
				'section' => 'vamika_featured_options',
				'type'    => 'text',
			)
		);
	}

	/* ---- Semantic Section ---- */
	$wp_customize->add_section(
		'vamika_semantic_options',
		array(
			'title'    => __( 'Semantic Section', 'vamika' ),
			'priority' => 28,
		)
	);

	$semantic_settings = array(
		'vamika_semantic_eyebrow'        => array( __( 'Connected By Meaning', 'vamika' ), 'sanitize_text_field' ),
		'vamika_semantic_heading'        => array( __( 'Related, by', 'vamika' ), 'sanitize_text_field' ),
		'vamika_semantic_heading_em'     => array( __( 'idea', 'vamika' ), 'sanitize_text_field' ),
		'vamika_semantic_heading_suffix' => array( __( '- not by tag.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_semantic_reading_label'  => array( __( 'Reading Now', 'vamika' ), 'sanitize_text_field' ),
	);

	foreach ( $semantic_settings as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $config[0],
				'sanitize_callback' => $config[1],
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => ucwords( str_replace( '_', ' ', str_replace( 'vamika_semantic_', '', $setting_id ) ) ),
				'section' => 'vamika_semantic_options',
				'type'    => 'text',
			)
		);
	}

	/* ---- Features Section (Iris AI) ---- */
	$wp_customize->add_section(
		'vamika_features_options',
		array(
			'title'       => __( 'Features Section (AI)', 'vamika' ),
			'priority'    => 29,
			'description' => __( 'This section only appears when the Iris AI plugin is active.', 'vamika' ),
		)
	);

	$features_settings = array(
		'vamika_features_eyebrow'     => array( __( 'Powered By Iris', 'vamika' ), 'sanitize_text_field' ),
		'vamika_features_heading'     => array( __( 'Five AI tools,', 'vamika' ), 'sanitize_text_field' ),
		'vamika_features_heading_2'   => array( __( 'built into the', 'vamika' ), 'sanitize_text_field' ),
		'vamika_features_heading_em'  => array( __( 'reading.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_features_description' => array( __( 'Vamika is not a magazine with AI bolted on. Every feature is designed to disappear into the experience of reading.', 'vamika' ), 'sanitize_textarea_field' ),
		'vamika_feat_1_label'         => array( __( 'Chat Assistant', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_1_heading'       => array( __( 'Ask any article', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_1_heading_em'    => array( __( 'anything.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_1_text'          => array( __( 'Every essay becomes a conversation.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_2_label'         => array( __( 'Summaries', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_2_heading'       => array( __( 'Summaries, done', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_2_heading_em'    => array( __( 'right.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_2_text'          => array( __( 'One-click summaries that stay faithful to the writer.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_3_label'         => array( __( 'Semantic Search', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_3_heading'       => array( __( 'Search by', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_3_heading_em'    => array( __( 'meaning.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_3_text'          => array( __( 'Find essays by idea, not just keyword.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_4_label'         => array( __( 'Insights', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_4_heading'       => array( __( 'Editor', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_4_heading_em'    => array( __( 'dashboard.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_4_text'          => array( __( 'Track what readers are asking and what to publish next.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_5_label'         => array( __( 'Smart Suggestions', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_5_heading'       => array( __( 'The right read,', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_5_heading_em'    => array( __( 'at the right sentence.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_feat_5_text'          => array( __( 'Contextual recommendations that stay inside the flow of reading.', 'vamika' ), 'sanitize_text_field' ),
	);

	foreach ( $features_settings as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $config[0],
				'sanitize_callback' => $config[1],
				'transport'         => 'refresh',
			)
		);

		$label = str_replace( array( 'vamika_features_', 'vamika_feat_' ), '', $setting_id );
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => ucwords( str_replace( '_', ' ', $label ) ),
				'section' => 'vamika_features_options',
				'type'    => ( 'vamika_features_description' === $setting_id ) ? 'textarea' : 'text',
			)
		);
	}

	/* ---- General Options ---- */
	$wp_customize->add_section(
		'vamika_theme_options',
		array(
			'title'    => __( 'Vamika Options', 'vamika' ),
			'priority' => 30,
		)
	);

	$settings = array(
		'vamika_header_cta_label'       => array( 'Subscribe', 'sanitize_text_field' ),
		'vamika_header_cta_url'         => array( home_url( '/contact/' ), 'esc_url_raw' ),
		'vamika_footer_tagline'         => array(
			vamika_has_iris()
				? __( 'A magazine with opinions. Powered by AI - the AI reading companion built for independent publishers.', 'vamika' )
				: __( 'A magazine with opinions. Long-form writing for independent publishers.', 'vamika' ),
			'sanitize_text_field',
		),
		'vamika_footer_note'            => array( __( 'Theme: Vamika for WordPress v1.0', 'vamika' ), 'sanitize_text_field' ),
		'vamika_newsletter_eyebrow'     => array( __( 'The Weekly Letter', 'vamika' ), 'sanitize_text_field' ),
		'vamika_newsletter_heading'     => array( __( 'Three stories, every Sunday.', 'vamika' ), 'sanitize_text_field' ),
		'vamika_newsletter_text'        => array( __( 'Curated by the Vamika editors. One essay, one idea, one thing worth reading.', 'vamika' ), 'sanitize_textarea_field' ),
		'vamika_newsletter_button'      => array( __( 'Subscribe', 'vamika' ), 'sanitize_text_field' ),
		'vamika_newsletter_button_url'  => array( home_url( '/contact/' ), 'esc_url_raw' ),
		'vamika_newsletter_fine_print'  => array( __( 'Free - unsubscribe anytime', 'vamika' ), 'sanitize_text_field' ),
	);

	foreach ( $settings as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $config[0],
				'sanitize_callback' => $config[1],
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => ucwords( str_replace( '_', ' ', str_replace( 'vamika_', '', $setting_id ) ) ),
				'section' => 'vamika_theme_options',
				'type'    => ( false !== strpos( $setting_id, 'text' ) ) ? 'textarea' : 'text',
			)
		);
	}

	/*
	 * Visibility toggles for the header CTA and footer newsletter strip.
	 * Stored as booleans (true = shown, false = hidden).
	 */
	$toggles = array(
		'vamika_header_cta_show'         => array(
			'default' => true,
			'label'   => __( 'Show header "Subscribe" button', 'vamika' ),
		),
		'vamika_footer_newsletter_show'  => array(
			'default' => true,
			'label'   => __( 'Show footer newsletter strip', 'vamika' ),
		),
	);

	foreach ( $toggles as $toggle_id => $toggle ) {
		$wp_customize->add_setting(
			$toggle_id,
			array(
				'default'           => $toggle['default'],
				'sanitize_callback' => 'vamika_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$toggle_id,
			array(
				'label'   => $toggle['label'],
				'section' => 'vamika_theme_options',
				'type'    => 'checkbox',
			)
		);
	}
}

/**
 * Sanitize a checkbox value to a strict boolean.
 *
 * @param mixed $value Raw value from the Customizer.
 * @return bool
 */
function vamika_sanitize_checkbox( $value ) {
	return (bool) $value;
}
add_action( 'customize_register', 'vamika_customize_register' );

function vamika_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}

	return '&hellip;';
}
add_filter( 'excerpt_more', 'vamika_excerpt_more' );

