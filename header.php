<?php
/**
 * Site header.
 *
 * @package Vamika
 */

$body_mode  = vamika_get_body_mode();
$cta_show   = (bool) get_theme_mod( 'vamika_header_cta_show', true );
$cta_label  = get_theme_mod( 'vamika_header_cta_label', __( 'Subscribe', 'vamika' ) );
$cta_url    = get_theme_mod( 'vamika_header_cta_url', home_url( '/contact/' ) );
$menu_items = vamika_get_default_menu_items();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( $body_mode ); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'vamika' ); ?></a>
<div class="site">
	<?php if ( is_single() ) : ?>
		<div class="progress-bar" id="progressBar"></div>
	<?php endif; ?>

	<nav class="topnav" aria-label="<?php esc_attr_e( 'Primary navigation', 'vamika' ); ?>">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Vamika home', 'vamika' ); ?>">
			<span class="blob"></span>
			<?php echo esc_html( get_bloginfo( 'name' ) ); ?><sup>&reg;</sup>
		</a>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-menu">
			<span class="menu-label-open"><?php esc_html_e( 'Menu', 'vamika' ); ?></span>
			<span class="menu-label-close" hidden><?php esc_html_e( 'Close', 'vamika' ); ?></span>
		</button>
		<div class="nav-panel" id="site-menu">
			<?php vamika_render_menu( 'primary', $menu_items ); ?>
			<?php if ( $cta_show ) : ?>
				<a href="<?php echo esc_url( $cta_url ); ?>" class="nav-cta magnetic"><?php echo esc_html( $cta_label ); ?></a>
			<?php endif; ?>
		</div>
	</nav>
	<?php if ( get_header_image() ) : ?>
		<div class="custom-header-media">
			<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		</div>
	<?php endif; ?>
