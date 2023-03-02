<?php
/**
 * Template for displaying header
 *
 * Contains <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package puffin-innovations
 */

/* Imports */

use PI\PI as PI;
use Formation\Pub\Nav_Walker;

/* Classes */

$list_classes = 'c-nav__list l-relative l-flex l-align-center l-gap-margin-s l-gap-margin-sm-l t-list-style-none l-overflow-x-auto l-overflow-y-hidden l-padding-top-5xs l-padding-bottom-5xs';
$light        = false;

if ( PI::is_text_light( PI::$hero_theme ) ) {
	$light         = true;
	$list_classes .= ' t-link t-background-light t-light';
} else {
	$list_classes .= ' t-link-current t-foreground-dark';
}

/* Logo */

$logo = PI::render_logo( '', true );

if ( $logo ) {
	$logo = (
		'<div data-logo' . ( $light ? ' class="t-light"' : '' ) . '>' .
			'<a href="' . esc_url( home_url( '/' ) ) . '" class="l-block l-relative l-svg l-svg-absolute o-logo" id="js-logo">' .
				'<span class="a11y-visually-hidden">' . get_bloginfo( 'name' ) . ' home</span>' .
				$logo .
			'</a>' .
		'</div>'
	);
}

/* Main navigation */

$main_nav             = '';
$main_nav_overflow_id = uniqid();

if ( has_nav_menu( 'main' ) ) {
	$a_class  = 'c-nav__link t l-padding-top-5xs l-padding-bottom-5xs l-inline-flex l-relative l-after';
	$li_class = 'c-nav__item';
	$attr     = ' data-overflow-group="0"';

	$main_nav = wp_nav_menu(
		[
			'menu'       => 'main',
			'container'  => '',
			'items_wrap' => '%3$s',
			'echo'       => false,
			'walker'     => new Nav_Walker(
				[
					'a_class'            => $a_class,
					'li_class'           => $li_class,
					'before_link_output' => function( &$obj, &$output, $depth, $args, $item ) use ( $a_class ) {
						$obj->a_class = $a_class;

						if ( 'Button' === $item->post_content ) {
							$obj->a_class = 'c-nav__link c-nav__cta l-relative l-before o-button-primary o-button-small bg-foreground-dark t-background-light';
						}
					},
					'before_output'      => function( &$obj, &$output, $depth, $args, $item ) use ( $attr ) {
						$obj->li_attr = $attr;
					},
				]
			),
		]
	);
}

/* Search form */

/* phpcs:ignore */
$search_icon = file_get_contents( PI::$svg_assets_path . 'search.svg' );  // Ignore: local path

/* phpcs:ignore */
$close_icon = file_get_contents( PI::$svg_assets_path . 'close.svg' );  // Ignore: local path

$search_id = uniqid();

$search_form = (
	'<li class="c-nav__item outline-tight" data-overflow-group="0" data-depth="0">' .
		'<div class="c-nav-search">' .
			"<div class='c-nav-search__bar l-absolute l-bottom-0 l-left-0 l-width-100-pc e-transition' id='$search_id' data-display='false'>" .
				PI::render_form_search(
					[
						'form_class'   => 'o-form o-form-small o-form-round o-form-search l-relative' . ( ! $light ? ' t-dark' : '' ),
						'field_class'  => '',
						'input_class'  => 'l-height-m',
						'button_class' => 'l-absolute l-right-0 l-bottom-0 l-top-0 l-flex l-align-center l-justify-center l-width-m l-height-m t-current',
						'icon_class'   => 'l-flex l-width-xs l-height-xs l-svg',
						'icon_path'    => PI::$svg_assets_path . 'search.svg',
						'a11y_class'   => 'a11y-visually-hidden',
					]
				) .
			'</div>' .
			'<div class="c-nav-overflow__hide">' .
				"<button class='c-nav-search__button t-current l-width-xs l-height-m l-flex l-align-center l-justify-center' type='button' aria-expanded='false' aria-controls='$search_id' aria-label='Toggle search bar'>" .
					'<span class="l-flex l-width-xs l-height-xs l-svg">' .
						$search_icon .
					'</span>' .
					'<span class="l-flex l-width-xs l-height-xs l-svg">' .
						$close_icon .
					'</span>' .
				'</button>' .
			'</div>' .
		'</div>' .
	'</li>'
); ?>

<!DOCTYPE html>
<html id="<?php echo esc_attr( PI::$namespace ); ?>" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php /* phpcs:ignore */ ?>
	<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/public/fonts/mont-extra-bold.woff2" as="font" type="font/woff2" crossorigin>
	<?php /* phpcs:ignore */ ?>
	<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/public/fonts/mont-bold.woff2" as="font" type="font/woff2" crossorigin>
	<?php /* phpcs:ignore */ ?>
	<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/public/fonts/neue-haas-unica.woff2" as="font" type="font/woff2" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class( PI::$namespace ); ?>>
	<?php wp_body_open(); ?>
	<header class="l-padding-top-2xs l-padding-bottom-2xs l-padding-top-xs-l l-padding-bottom-xs-l" data-theme="<?php echo esc_attr( PI::$hero_theme ); ?>" style="--hero-bg: var(--<?php echo esc_attr( PI::$hero_theme ); ?>)">
		<a href="#main" class="c-skip-link t-h5 bg-background-light t-foreground-dark l-block l-absolute l-left-0 l-right-0 l-top-0 l-padding-right-2xs l-padding-left-2xs l-padding-top-2xs l-padding-bottom-2xs t-align-center outline-snug">
			Skip to main content
		</a>
		<nav class="c-nav l-container l-relative" id="n-<?php echo esc_attr( uniqid() ); ?>" aria-label="Site">
			<div class="c-nav__overlay bg-foreground-dark-09 l-fixed l-top-0 l-left-0 l-z-index-1 l-width-100-pc l-height-100-pc e-transition"></div>
			<div class="l-flex l-justify-between l-align-center">
				<?php /* phpcs:ignore */ ?>
				<?php echo $logo; ?>
				<ul class="<?php echo esc_attr( $list_classes ); ?>" role="list">
					<?php /* phpcs:ignore */ ?>
					<?php echo $main_nav . $search_form; ?>
				</ul>
				<div class="c-nav__hide">
					<button class="c-nav__button c-nav__open l-height-sm l-padding-top-5xs l-relative <?php echo $light ? ' t-light' : ''; ?>" type="button" aria-haspopup="dialog" aria-controls="<?php echo esc_attr( $main_nav_overflow_id ); ?>">
						<span class="c-nav-icon l-block l-relative l-margin-auto e-transition" data-num="4">
							<span class="c-nav-icon__top l-block e-transition"></span>
							<span class="c-nav-icon__middle l-block e-transition"></span>
							<span class="c-nav-icon__bottom l-block e-transition"></span>
						</span>
						<span class="c-nav-icon-label t-h6 t-line-height-100-pc l-block l-padding-top-4xs e-transition">Menu</span>
					</button>
				</div>
				<div class="c-nav-overflow l-fixed l-right-0 l-bottom-0 l-z-index-1 l-height-100-pc bg-primary-light t-foreground-dark t-link-current e-transition l-width-4-5" role="dialog" aria-modal="true" aria-label="Main menu" id="<?php echo esc_attr( $main_nav_overflow_id ); ?>">
					<div class="l-height-100-pc l-overflow-y-auto l-overscroll-none l-overflow-x-hidden l-padding-right-2xs l-padding-left-xs l-padding-top-xl l-padding-bottom-xs">
						<ul class="c-nav-overflow__list l-flex l-flex-column l-gap-margin-xs t-list-style-none" role="list"></ul>
					</div>
					<div class="c-nav__hide">
						<button class="c-nav__button c-nav__close l-width-sm l-height-sm l-padding-top-5xs l-fixed" type="button" aria-label="Close menu" data-visible="false">
							<span class="c-nav-icon l-block l-relative l-margin-auto e-transition" data-num="4">
								<span class="c-nav-icon__top l-block e-transition"></span>
								<span class="c-nav-icon__middle l-block e-transition"></span>
								<span class="c-nav-icon__bottom l-block e-transition"></span>
							</span>
							<span class="c-nav-icon-label t-h6 t-line-height-100-pc l-block l-padding-top-4xs e-transition" aria-hidden="true">Menu</span>
						</button>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<main id="main" class="l-relative l-before">
