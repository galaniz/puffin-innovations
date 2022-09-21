<?php
/**
 * PI theme
 *
 * @package puffin-innovations
 */

namespace PI;

/* If file is called directly abort. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dependencies and core theme class
 */

require get_template_directory() . '/vendor/autoload.php';

/* Import */

use PI\PI as PI;

/* Instantiate theme class */

$pi = new PI();
