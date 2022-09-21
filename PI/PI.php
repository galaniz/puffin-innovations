<?php
/**
 * PI theme class
 *
 * @package puffin-innovations
 */

namespace PI;

/**
 * Imports
 */

use Formation\Formation as FRM;
use Formation\Utils;

/**
 * Class
 */

class PI {

	/**
	 * Namespace for handles.
	 *
	 * @var string $namespace
	 */

	public static $namespace = 'pi';

	/**
	 * Script version for wp_enqueue.
	 *
	 * @var string $script_ver
	 */

	public static $script_ver = '1.0.0';

	/**
	 * Script attributes to add in filter.
	 *
	 * @var array $script_attributes
	 */

	public static $script_attributes = [
		'script-compat' => 'nomodule',
		'script'        => 'type="module"',
	];

	/**
	 * Constructor
	 */

	public function __construct() {
		/* Set variables in Formation */

		FRM::$namespace         = self::$namespace;
		FRM::$script_ver        = self::$script_ver;
		FRM::$script_attributes = self::$script_attributes;

		FRM::$styles = [
			[
				'handle' => 'style',
				'url'    => get_template_directory_uri() . '/style.css',
			],
		];

		FRM::$scripts = [
			[
				'handle' => 'script-compat',
				'url'    => get_template_directory_uri() . '/assets/public/js/' . self::$namespace . '-compat.js',
			],
			[
				'handle' => 'script',
				'url'    => get_template_directory_uri() . '/assets/public/js/' . self::$namespace . '.js',
			],
		];

		/* Actions */

		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_assets'], 20 );

		/* Filters */

		add_filter( 'script_loader_tag', [$this, 'add_script_attributes'], 10, 2 );
	}

	/**
	 * Register and enqueue scripts and styles.
	 */

	public function enqueue_assets() {
		FRM::scripts();
	}

	/**
	 * Add attributes to scripts.
	 */

	public function add_script_attributes( $tag, $handle ) {
		return FRM::add_script_attributes( $tag, $handle );
	}

	/**
	 * Formation Utility methods.
	 */

	use Utils;

} // End PI
