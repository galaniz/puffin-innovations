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
				'<span class="a11y-visually-hidden">' . get_bloginfo( 'name' ) . '</span>' .
				$logo .
			'</a>' .
		'</div>'
	);
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
	<header class="l-padding-top-2xs l-padding-bottom-2xs l-padding-top-xs-l l-padding-bottom-xs-l">
		<a href="#main" class="c-skip-link">
			<div class="l-container">Skip to main content</div>
		</a>
		<nav aria-label="Main">
			<div class="l-container">
				<?php echo $logo; ?>
			</div>
		</nav>
	</header>
	<main id="main" class="l-min-height-100-vh">
