<?php
/**
 * Site footer.
 *
 * @package Vamika
 */

/*
 * Footer tagline default: neutral copy that makes no AI claims. When
 * the Iris AI companion plugin is active, swap in the richer tagline
 * that names the feature. Site owners can override either via the
 * Customizer ("Footer Tagline").
 */
$default_tagline = vamika_has_iris()
	? __( 'A magazine with opinions. Powered by AI - the AI reading companion built for independent publishers.', 'vamika' )
	: __( 'A magazine with opinions. Long-form writing for independent publishers.', 'vamika' );
$tagline            = get_theme_mod( 'vamika_footer_tagline', $default_tagline );
$footnote           = get_theme_mod( 'vamika_footer_note', __( 'Theme: Vamika for WordPress v1.0', 'vamika' ) );
$nl_show            = (bool) get_theme_mod( 'vamika_footer_newsletter_show', true );
$nl_heading         = get_theme_mod( 'vamika_newsletter_heading', __( 'Three stories, every Sunday.', 'vamika' ) );
$nl_text            = get_theme_mod( 'vamika_newsletter_text', __( 'Curated by the Vamika editors. One essay, one idea, one thing worth reading.', 'vamika' ) );
$nl_button          = get_theme_mod( 'vamika_newsletter_button', __( 'Subscribe', 'vamika' ) );
$nl_button_url      = get_theme_mod( 'vamika_newsletter_button_url', home_url( '/contact/' ) );
$copyright = sprintf(
	/* translators: %s: current year. */
	__( '© %s %s', 'vamika' ),
	date_i18n( 'Y' ),
	get_bloginfo( 'name' )
);
?>
	<footer class="site-footer">
		<div class="footer-top container-wide">
			<div>
				<div class="footer-brand"><span class="blob"></span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
				<div class="footer-tagline"><?php echo esc_html( $tagline ); ?></div>
			</div>
			<?php
			if ( has_nav_menu( 'footer' ) ) :
				$footer_items = wp_get_nav_menu_items( get_nav_menu_locations()['footer'] );
				if ( ! empty( $footer_items ) ) :
					$columns = array_chunk( $footer_items, (int) ceil( count( $footer_items ) / 3 ) );
					foreach ( $columns as $col_items ) :
						?>
						<div class="footer-col">
							<ul>
								<?php foreach ( $col_items as $item ) : ?>
									<li><a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php if ( $nl_show && ! is_front_page() ) : ?>
			<div class="footer-newsletter">
				<div class="footer-newsletter-inner">
					<div class="footer-newsletter-text">
						<strong><?php echo esc_html( $nl_heading ); ?></strong>
						<span><?php echo esc_html( $nl_text ); ?></span>
					</div>
					<a class="footer-newsletter-btn" href="<?php echo esc_url( $nl_button_url ); ?>"><?php echo esc_html( $nl_button ); ?> →</a>
				</div>
			</div>
		<?php endif; ?>
		<div class="footer-bottom container-wide">
			<span><?php echo esc_html( $copyright ); ?></span>
			<span><?php echo esc_html( $footnote ); ?></span>
		</div>
	</footer>

	<?php
	// The floating Iris dock lives in the Iris Theme Kit plugin and is
	// gated on the Iris AI plugin being active — per the WordPress.org
	// theme review "plugin territory" rule.
	if ( function_exists( 'iris_theme_kit_render_iris_dock' ) ) {
		iris_theme_kit_render_iris_dock();
	}
	?>
</div>
<?php wp_footer(); ?>
</body>
</html>
