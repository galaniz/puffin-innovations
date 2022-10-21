<?php
/**
 * Template for displaying the footer
 *
 * Contains the closing of main and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package puffin-innovations
 */

/* Imports */

use PI\PI as PI;
use Formation\Common\Field\Select_Fields;

/* Namespace */

$n = PI::$namespace;

/* Logo */

$logo = PI::render_logo( '', true );

if ( $logo ) {
	$tagline = get_option( $n . '_tagline', '' );

	if ( $tagline ) {
		$tagline = "<p class='t-s l-padding-top-2xs l-padding-left-5xs l-padding-left-4xs-l'>$tagline</p>";
	}

	$logo = (
		'<div>' .
			'<div class="l-width-4xl-m">' .
				'<a href="' . esc_url( home_url( '/' ) ) . '" class="l-block l-relative l-svg l-svg-absolute o-logo-s">' .
					'<span class="a11y-visually-hidden">' . get_bloginfo( 'name' ) . '</span>' .
					$logo .
				'</a>' .
				$tagline .
			'</div>' .
		'</div>'
	);
}

/* Navigation */

$navigation = '';

if ( has_nav_menu( 'footer' ) ) {
	$navigation = wp_nav_menu(
		[
			'theme_location' => 'footer',
			'container'      => '',
			'items_wrap'     => '%3$s',
			'echo'           => false,
		]
	);

	$navigation = (
		'<div>' .
			'<nav aria-label="Overview">' .
				'<ul class="l-flex l-flex-column l-gap-margin-4xs l-gap-margin-2xs-l t-list-style-none e-underline-reverse t" role="list">' .
					$navigation .
				'</ul>' .
			'</nav>' .
		'</div>'
	);
}

/* Social */

$social = PI::render_social(
	[
		'links'      => 'social',
		'list_class' => 'l-flex l-flex-wrap l-gap-margin-2xs t-list-style-none',
		'list_attr'  => [
			'role' => 'list',
		],
		'link_class' => 'l-flex l-padding-top-3xs l-padding-bottom-3xs l-padding-left-3xs l-padding-right-3xs bg-background-light-15 e-transition-border-radius',
		'link_attr'  => [
			'target' => '_blank',
			'rel'    => 'noreferrer',
		],
		'a11y_class' => PI::$a11y_class['visually_hide'],
		'icon_class' => 'l-flex l-width-2xs l-height-2xs l-svg',
		'icon_paths' => [
			'Facebook'  => PI::$svg_assets_path . 'facebook.svg',
			'Instagram' => PI::$svg_assets_path . 'instagram.svg',
			'Twitter'   => PI::$svg_assets_path . 'twitter.svg',
			'LinkedIn'  => PI::$svg_assets_path . 'linkedin.svg',
		],
	]
);

if ( $social ) {
	$social = "<nav aria-label='Social'>$social</nav>";
}

/* Legal */

$legal = [];

if ( has_nav_menu( 'legal' ) ) {
	$legal_navigation = wp_nav_menu(
		[
			'theme_location' => 'legal',
			'container'      => '',
			'items_wrap'     => '%3$s',
			'echo'           => false,
		]
	);

	$legal[] = (
		'<nav aria-label="Legal" class="l-margin-top-auto">' .
			'<ul class="l-flex l-flex-column l-flex-row-m l-flex-wrap l-gap-margin-4xs l-gap-margin-xs-m t-list-style-none t-link-current e-underline-reverse t-xs" role="list">' .
				$legal_navigation .
			'</ul>' .
		'</nav>'
	);
}

/* Copyright */

$copyright = get_option( $n . '_copyright', '&copy; ' . get_bloginfo( 'name' ) . ' *|YEAR|*. All rights reserved.' );
$copyright = (
	'<div class="l-margin-top-auto">' .
		'<p class="t-xs">' . str_replace( '*|YEAR|*', gmdate( 'Y' ), $copyright ) . '</p>' .
	'</div>'
);

$legal[] = $copyright;

/* Knowbility */

$knowbility = '';

/* phpcs:ignore */
$knowbility_logo = file_get_contents( PI::$svg_assets_path . 'knowbility.svg' ); // Ignore: local path

if ( $knowbility_logo ) {
	$legal[] = (
		'<div>' .
			'<a class="l-flex l-width-xl l-height-xs l-svg t-link-current" href="https://knowbility.org/programs/air" target="_blank" rel="noreferrer" aria-label="Knowbility">' .
				$knowbility_logo .
			'</a>' .
		'</div>'
	);
}

/* Legal output */

if ( $legal ) {
	$legal = (
		'<div class="l-align-self-center-m">' .
			'<div class="l-flex l-flex-column l-flex-row-m l-flex-wrap l-gap-margin-4xs l-gap-margin-xs-m">' .
				implode( '', $legal ) .
			'</div>' .
		'</div>'
	);
} ?>

	</main><!-- #main -->
	<footer class="bg-foreground-dark bg-seamless t-light l-relative l-before l-padding-top-l l-padding-bottom-m l-padding-top-2xl-l l-padding-bottom-l-l">
		<div class="l-container">
			<div class="l-flex l-flex-column l-flex-row-m l-flex-wrap l-justify-between l-gap-margin-s l-gap-margin-xl-m">
				<?php /* phpcs:disable */ ?>
				<?php echo $logo; ?>
				<?php echo $navigation; ?>
				<?php if ( is_active_sidebar( "$n-footer-contact-form" ) ) : ?>
					<div class="l-flex-grow-1 l-width-2-5-m">
						<?php dynamic_sidebar( "$n-footer-contact-form" ); ?>
					</div>
				<?php endif; ?>
				<?php echo $social; ?>
				<?php echo $legal ?>
				<?php /* phpcs:enable */ ?>
			</div>
		</div>
	</footer>
<?php wp_footer(); ?>
</body>
</html>
