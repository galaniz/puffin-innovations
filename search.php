<?php
/**
 * Template for search results
 *
 * @package puffin-innovations
 */

/* Imports */

use PI\PI as PI;
use PI\Common\Blocks\Hero;

/* Header */

get_header();

/* Hero */

global $wp_query;

/* phpcs:ignore */
echo Hero::render_hero(
	[
		'title_small'   => 'Search Results',
		'title_large'   => get_search_query(),
		'text'          => $wp_query->found_posts . ' results',
		'bg_color_slug' => 'background-base',
	]
);

/* Posts */

/* Footer */

get_footer();
