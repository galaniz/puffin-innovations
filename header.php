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

/* Logo */

$logo = PI::render_logo();

if ( $logo ) {
	$logo = (
		'<div data-logo>' .
			'<a href="' . esc_url( home_url( '/' ) ) . '" class="l-block o-logo">' .
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
							$obj->a_class = 'c-nav__link o-button-primary o-button-small bg-foreground-dark t-background-light';
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

/* Classes */

$list_classes = 'c-nav__list l-flex l-flex-wrap l-align-center l-gap-margin-s l-gap-margin-sm-l t-list-style-none';
$light        = false;

if ( 'foreground-dark' === PI::$hero_theme || 'primary-dark' === PI::$hero_theme ) {
	$light         = true;
	$list_classes .= ' t-link t-background-light t-light';
} else {
	$list_classes .= ' t-link-current t-foreground-dark';
}

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="theme-color" content="#e05920">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<header class="l-padding-top-2xs l-padding-bottom-2xs l-padding-top-xs-l l-padding-bottom-xs-l" data-theme="<?php echo esc_attr( PI::$hero_theme ); ?>">
		<a href="#main" class="c-skip-link t-h5 bg-background-light t-foreground-dark l-block l-absolute l-left-0 l-right-0 l-top-0 l-padding-right-2xs l-padding-left-2xs l-padding-top-2xs l-padding-bottom-2xs t-align-center outline-snug">
			Skip to main content
		</a>
		<nav class="c-nav l-container l-relative" aria-label="Main">
			<div class="c-nav__overlay bg-foreground-dark-09 l-fixed l-top-0 l-left-0 l-width-100-pc l-height-100-pc e-transition"></div>
			<div class="l-flex l-justify-between l-align-center">
				<?php /* phpcs:ignore */ ?>
				<?php echo $logo; ?>
				<ul class="<?php echo esc_attr( $list_classes ); ?>" role="list">
					<?php /* phpcs:ignore */ ?>
					<?php echo $main_nav; ?>
				</ul>
				<button class="c-nav__button l-relative l-z-index-1<?php echo $light ? ' t-light' : ''; ?>" type="button" aria-haspopup="true" aria-controls="<?php echo esc_attr( $main_nav_overflow_id ); ?>">
					<span class="c-nav-icon l-relative l-margin-auto e-transition" data-num="4">
						<span class="c-nav-icon__top e-transition"></span>
						<span class="c-nav-icon__middle e-transition"></span>
						<span class="c-nav-icon__bottom e-transition"></span>
					</span>
					<span class="c-nav-icon-label t-h6 l-padding-top-5xs e-transition">Menu</span>
				</button>
				<div class="c-nav-overflow l-fixed l-right-0 l-bottom-0 l-height-100-vh bg-primary-light t-foreground-dark t-link-current e-transition l-width-4-5" role="dialog" aria-modal="true" aria-label="Main navigation" id="<?php echo esc_attr( $main_nav_overflow_id ); ?>">
					<div class="l-height-100-vh l-overflow-y-auto l-padding-right-2xs l-padding-left-xs l-padding-top-2xl l-padding-bottom-xs">
						<ul class="c-nav-overflow__list l-flex l-flex-column l-gap-margin-xs t-list-style-none" role="list"></ul>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<main id="main" class="l-min-height-100-vh">
