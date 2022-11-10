<?php
/**
 * Main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package puffin-innovations
 */

/* Imports */

use PI\PI as PI;
use PI\Common\Blocks\Hero;

/* Header */

get_header();

/* Content */

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
else :
	/* phpcs:ignore */
	echo PI::render_content_none();
endif;

/* Footer */

get_footer();
