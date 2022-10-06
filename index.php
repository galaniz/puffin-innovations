<?php
/**
 * Main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package puffin-innovations
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
else :
	get_template_part( 'template-parts/content', 'none' );
endif;

get_footer();
