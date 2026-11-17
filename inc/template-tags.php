<?php
/**
 * Template helpers.
 *
 * @package Vamika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vamika_get_body_mode() {
	if ( is_single() ) {
		return 'theme-post';
	}

	if ( is_front_page() ) {
		return 'theme-magazine';
	}

	/*
	 * Pages using the "Homepage" template also need the magazine
	 * body class, even if they aren't set as the static front page.
	 */
	if ( is_page() && 'templates/template-homepage.php' === get_page_template_slug() ) {
		return 'theme-magazine';
	}

	return 'theme-pages';
}

function vamika_get_archive_title_text() {
	if ( is_category() || is_tag() || is_tax() ) {
		return single_term_title( '', false );
	}

	if ( is_author() ) {
		return get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) );
	}

	if ( is_search() ) {
		return sprintf( __( 'Results for "%s"', 'vamika' ), get_search_query() );
	}

	return wp_strip_all_tags( get_the_archive_title() );
}

function vamika_get_post_reading_time( $post_id = 0 ) {
	$post_id    = $post_id ? $post_id : get_the_ID();
	$content    = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( (string) $content ) );

	/**
	 * Filters the words-per-minute rate used to calculate reading time.
	 *
	 * @param int $wpm     Words per minute. Default 220.
	 * @param int $post_id Post ID being calculated.
	 */
	$wpm     = (int) apply_filters( 'vamika_reading_time_wpm', 220, $post_id );
	$minutes = max( 1, (int) ceil( $word_count / max( 1, $wpm ) ) );

	return sprintf( _n( '%d min read', '%d min read', $minutes, 'vamika' ), $minutes );
}

function vamika_get_primary_term_name( $post_id = 0 ) {
	$post_id    = $post_id ? $post_id : get_the_ID();
	$categories = get_the_category( $post_id );

	if ( ! empty( $categories ) ) {
		return $categories[0]->name;
	}

	return __( 'Essay', 'vamika' );
}

function vamika_get_primary_term_slug( $post_id = 0 ) {
	$post_id    = $post_id ? $post_id : get_the_ID();
	$categories = get_the_category( $post_id );

	if ( ! empty( $categories ) ) {
		return sanitize_html_class( $categories[0]->slug );
	}

	return 'essay';
}

function vamika_get_post_headings( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = (string) get_post_field( 'post_content', $post_id );

	if ( '' === $content ) {
		return array();
	}

	$headings = array();

	if ( preg_match_all( '/<h2[^>]*>(.*?)<\/h2>/is', $content, $matches ) ) {
		foreach ( $matches[1] as $heading ) {
			$clean = trim( wp_strip_all_tags( $heading ) );

			if ( '' !== $clean ) {
				$headings[] = $clean;
			}
		}
	}

	return $headings;
}

function vamika_get_post_initials( $name = '' ) {
	$name  = $name ? $name : get_the_author();
	$parts = preg_split( '/\s+/', trim( (string) $name ) );

	if ( empty( $parts ) ) {
		return __( 'VA', 'vamika' );
	}

	$initials = '';

	foreach ( array_slice( $parts, 0, 2 ) as $part ) {
		$initials .= strtoupper( substr( $part, 0, 1 ) );
	}

	return $initials;
}

if ( ! function_exists( 'vamika_render_iris_icon' ) ) {
	function vamika_render_iris_icon( $full = true ) {
		?>
		<svg class="iris-icon" viewBox="0 0 100 100" aria-hidden="true">
			<?php if ( $full ) : ?>
				<circle class="ring" cx="50" cy="55" r="32"></circle>
			<?php endif; ?>
			<circle class="face" cx="50" cy="55" r="30"></circle>
			<circle class="eye l" cx="40" cy="52" r="3.5"></circle>
			<circle class="eye r" cx="60" cy="52" r="3.5"></circle>
			<path class="mouth" d="M 42,64 Q 50,70 58,64"></path>
			<?php if ( $full ) : ?>
				<path class="sparkle" d="M 78,22 L 81,29 L 88,32 L 81,35 L 78,42 L 75,35 L 68,32 L 75,29 Z"></path>
			<?php endif; ?>
		</svg>
		<?php
	}
}

function vamika_render_post_visual( $class = 'story-img' ) {
	?>
	<div class="<?php echo esc_attr( $class ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large' ); ?>
		<?php else : ?>
			<svg viewBox="0 0 800 450" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
				<rect width="800" height="450" fill="#a8d5ff"></rect>
				<circle cx="600" cy="140" r="60" fill="#ffd230" stroke="#14120f" stroke-width="4"></circle>
				<path d="M 0,340 Q 200,260 400,320 T 800,280 L 800,450 L 0,450 Z" fill="#2ab364" stroke="#14120f" stroke-width="4"></path>
				<rect x="320" y="260" width="160" height="90" fill="#ff5533" stroke="#14120f" stroke-width="4"></rect>
				<polygon points="320,260 400,200 480,260" fill="#ffb896" stroke="#14120f" stroke-width="4"></polygon>
			</svg>
		<?php endif; ?>
	</div>
	<?php
}

function vamika_render_menu( $location, $fallback_items = array(), $menu_class = 'navlinks' ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => $menu_class,
				'fallback_cb'    => false,
				'depth'          => 0,
			)
		);
		return;
	}

	echo '<ul class="' . esc_attr( $menu_class ) . '">';

	foreach ( $fallback_items as $item ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}

	echo '</ul>';
}

function vamika_get_default_menu_items() {
	$archive_url = get_post_type_archive_link( 'post' );

	return array(
		
		array(
			'label' => __( 'Archive', 'vamika' ),
			'url'   => $archive_url ? $archive_url : home_url( '/' ),
		),
		array(
			'label' => __( 'Search', 'vamika' ),
			'url'   => home_url( '/?s=' ),
		)
	);
}
