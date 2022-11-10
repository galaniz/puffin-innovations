<?php
/**
 * Template for displaying 404 pages
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package puffin-innovations
 */

/* Imports */

use PI\PI as PI;
use PI\Common\Blocks\Hero;

/* Header */

get_header();

/* Content */

/* phpcs:ignore */
echo PI::render_content_none();

/* Footer */

get_footer();
