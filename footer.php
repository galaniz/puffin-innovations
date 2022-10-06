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
use Formation\Utils_Optional;

/* Namespace */

$n = PI::$namespace;

/* Logo */

$logo = Utils_Optional::render_logo();

if ( $logo ) {
	$logo = (
		'<div>' .
			'<a href="' . esc_url( home_url( '/' ) ) . '" class="l-block o-logo-s">' .
				'<span class="a11y-visually-hidden">' . get_bloginfo( 'name' ) . '</span>' .
				$logo .
			'</a>' .
		'</div>'
	);
}

/* Copyright */

$copyright = get_option( $n . '_copyright', '' );

if ( ! $copyright ) {
	$copyright = '&copy; ' . get_bloginfo( 'name' ) . ' *|YEAR|*. All rights reserved.';
}

$copyright = str_replace( '*|YEAR|*', gmdate( 'Y' ), $copyright ); ?>

	</main><!-- #main -->
	<footer class="l-padding-top-l l-padding-bottom-m">
		<div class="l-container">
			<div class="l-flex">
				<?php echo $logo; ?>
			</div>
		</div>
	</footer>
<?php wp_footer(); ?>
</body>
</html>
