<?php
/**
 * Template for search results
 *
 * @package puffin-innovations
 */

/* Imports */

use PI\PI as PI;
use PI\Common\Blocks\Hero;
use PI\Common\Blocks\Container;

/* Determine content */

global $wp_query;

if ( have_posts() ) {
	$content = Hero::render_hero(
		[
			'title_small'   => 'Search Results',
			'title_large'   => get_search_query(),
			'text'          => $wp_query->found_posts . ' results',
			'bg_color_slug' => 'background-base',
		]
	);

	$content .= Container::render_container(
		[
			'tag'                   => 'section',
			'contain'               => true,
			'padding_top_mobile'    => 'm',
			'padding_top'           => 'l',
			'padding_bottom_mobile' => 'xl',
			'padding_bottom'        => '2xl',
		],
		'<div class="l-width-1-1 l-width-2-3-l">' .
			PI::render_list() .
		'</div>'
	);
} else {
	$content = PI::render_content_none();
}

/* Header */

get_header();

/* Content */

/* phpcs:ignore */
echo $content;

/* Footer */

get_footer();
