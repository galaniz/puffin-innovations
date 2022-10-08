<?php
/**
 * PI theme class
 *
 * @package puffin-innovations
 */

namespace PI;

/* If file is called directly abort. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Imports
 */

use Formation\Formation;
use Formation\Utils;
use Formation\Admin\Settings\Theme;

/**
 * Class
 */

class PI extends Formation {

	/**
	 * Variables
	 */

	public static $colors = [];

	/* Assets path for svgs */

	public static $svg_assets_path = '';

	/**
	 * Constructor
	 */

	public function __construct() {
		/* Namespace */

		self::$namespace = 'pi';

		/* Paths */

		self::$svg_assets_path = get_template_directory() . '/assets/public/svg/';

		/* Styles and scripts */

		self::$styles = [
			[
				'handle' => 'style',
				'url'    => get_template_directory_uri() . '/style.css',
			],
		];

		self::$scripts = [
			[
				'handle' => 'script-compat',
				'url'    => get_template_directory_uri() . '/assets/public/js/' . self::$namespace . '-compat.js',
			],
			[
				'handle' => 'script',
				'url'    => get_template_directory_uri() . '/assets/public/js/' . self::$namespace . '.js',
			],
		];

		self::$script_ver = '1.0.0';

		self::$dequeue_gutenberg = true;

		self::$script_attributes = [
			'script-compat' => 'nomodule',
			'script'        => 'type="module"',
		];

		self::$defer_script_handles = [
			self::$namespace . 'script-compat',
			self::$namespace . 'script',
		];

		/* Theme colors */

		self::$colors = [
			[
				'name'  => 'Orange',
				'slug'  => 'primary-base',
				'color' => '#e05920',
			],
			[
				'name'  => 'Yellow',
				'slug'  => 'primary-light',
				'color' => '#ffc14a',
			],
			[
				'name'  => 'Blue',
				'slug'  => 'primary-tint',
				'color' => '#4c94d5',
			],
			[
				'name'  => 'Blue Dark',
				'slug'  => 'primary-dark',
				'color' => '#1b62a4',
			],
			[
				'name'  => 'Black',
				'slug'  => 'foreground-dark',
				'color' => '#141415',
			],
			[
				'name'  => 'Grey Light',
				'slug'  => 'background-base',
				'color' => '#e9e9e9',
			],
			[
				'name'  => 'White',
				'slug'  => 'background-light',
				'color' => '#ffffff',
			],
			[
				'name'  => 'Blue Light',
				'slug'  => 'background-dark',
				'color' => '#cbe0f3',
			],
		];

		self::$editor_color_palette = self::$colors;

		/* Image sizes */

		self::$image_sizes = [
			'2000w' => 2000,
			'1600w' => 1600,
			'1200w' => 1200,
			'600w'  => 600,
		];

		/* Navigations */

		self::$nav_menus = [
			'main'   => 'Main',
			'footer' => 'Footer',
			'social' => 'Social',
			'legal'  => 'Legal',
		];

		/* Parent constructor */

		parent::__construct();

		/* Settings */

		if ( is_admin() ) {
			$theme_settings = new Theme(
				[
					'mailchimp_list_locations' => [
						'mc_footer' => 'footer',
					],
					'fields'                   => [
						[
							'name'    => 'tagline',
							'label'   => 'Tagline',
							'type'    => 'textarea',
							'section' => 'logo',
							'tab'     => 'General',
							'attr'    => [
								'rows'      => 3,
								'data-full' => '',
							],
						],
					],
				]
			);
		}
	}

} // End PI
