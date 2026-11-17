<?php
/**
 * Front page template.
 *
 * Works out of the box with zero setup — just like block themes.
 * The magazine homepage renders on the very first visit, whether
 * the user has chosen "Your latest posts" or a static page.
 *
 * Priority order:
 *  1. Static page with a custom page template → load that template
 *  2. Static page with Elementor / block content → render that content
 *  3. Default (any setting) → magazine homepage layout
 *
 * @package Vamika
 */

/* 1. Custom page template (e.g. "Homepage" or any other). */
if ( 'page' === get_option( 'show_on_front' ) ) {
	$vamika_page_template = get_page_template_slug();
	if ( $vamika_page_template ) {
		$vamika_template_file = locate_template( $vamika_page_template );
		if ( $vamika_template_file ) {
			require $vamika_template_file;
			return;
		}
	}

	/* 2. Page has its own content (Elementor / block editor / classic). */
	if ( have_posts() ) {
		the_post();

		$vamika_has_content = 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true )
			|| ! empty( get_post_meta( get_the_ID(), '_elementor_data', true ) )
			|| trim( (string) get_the_content() );

		if ( $vamika_has_content ) {
			get_header();
			?>
			<main id="content" class="site-main">
				<?php the_content(); ?>
			</main>
			<?php
			get_footer();
			return;
		}

		rewind_posts();
	}
}

/* 3. Default: magazine homepage — works with any Settings → Reading choice. */
require get_template_directory() . '/templates/template-homepage.php';
