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
use Formation\Utils_Optional;

/* Logo */

$logo = Utils_Optional::render_logo();

if ( $logo ) {
	$logo = (
		'<div>' .
			'<a href="' . esc_url( home_url( '/' ) ) . '" class="l-block o-logo">' .
				'<span class="a11y-visually-hidden">' . get_bloginfo( 'name' ) . ' home</span>' .
				$logo .
			'</a>' .
		'</div>'
	);
}

/* Main navigation */

$main_nav = '';

if ( has_nav_menu( 'main' ) ) {
	$main_nav = wp_nav_menu(
		[
			'menu'           => 'main',
			'theme_location' => 'main',
			'container'      => '',
			'items_wrap'     => '%3$s',
			'echo'           => false,
			'walker'         => new Nav_Walker(
				[
					'a_class' => 'c-nav__link t l-padding-top-5xs l-padding-bottom-5xs',
				]
			),
		]
	);
} ?>

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
	<header class="l-padding-top-2xs l-padding-bottom-2xs l-padding-top-xs-l l-padding-bottom-xs-l">
		<a href="#main" class="c-skip-link bg-primary-dark t-light">
			<div class="l-container">Skip to main content</div>
		</a>
		<nav class="l-container t-link-current" aria-label="Main">
			<div class="c-nav__overlay"></div>
			<div class="l-flex l-justify-between">
				<?php /* phpcs:ignore */ ?>
				<?php echo $logo; ?>
			</div>
			<ul class="c-nav__list l-flex l-gap-margin-sm t-list-style-none" role="list">
				<?php /* phpcs:ignore */ ?>
				<?php echo $main_nav; ?>
			</ul>
			<button class="c-nav__button" type="button" aria-expanded="false" aria-controls="js-nav-overflow">
				<div class="c-nav-icon l-position-relative l-margin-auto" data-num="5">
					<div class="c-nav-icon__top"></div>
					<div class="c-nav-icon__middle e-transition"></div>
					<div class="c-nav-icon__bottom"></div>
				</div>
				<div>Menu</div>
			</button>
			<div class="c-nav-overflow">
				<ul class="c-nav-overflow__list l-flex" id="js-nav-overflow"></ul>
			</div>
		</nav>
	</header>
	<main id="main" class="l-min-height-100-vh">
