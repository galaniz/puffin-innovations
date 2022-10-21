<?php
/**
 * Slider block
 *
 * @package puffin-innovations
 */

namespace PI\Common\Blocks;

/**
 * Imports
 */

use PI\PI as PI;
use Formation\Common\Blocks\Blocks;

/**
 * Class
 */

class Slider {

	/**
	 * Variables
	 */

	public static $blocks = [
		'slider' => [
			'attr'    => [
				'length'   => ['type' => 'integer'],
				'label'    => ['type' => 'string'],
				'selected' => ['type' => 'integer'],
				'loop'     => ['type' => 'boolean'],
			],
			'default' => [
				'length'   => 0,
				'label'    => '',
				'selected' => 0,
				'loop'     => false,
			],
			'render'  => [__CLASS__, 'render_slider'],
			'handle'  => 'slider',
			'script'  => 'slider.js',
		],
	];

	/**
	 * Constructor
	 */

	public function __construct() {
		/* Register blocks */

		add_action( 'init', [$this, 'register_blocks'] );
	}

	/**
	 * Pass blocks to Blocks class
	 */

	public function register_blocks() {
		foreach ( self::$blocks as $name => $b ) {
			Blocks::$blocks[ $name ] = $b;
		}
	}

	/**
	 * Render callbacks
	 */

	public static function render_slider( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['slider']['default'], $attributes );

		/* Destructure */

		[
			'length'   => $length,
			'label'    => $label,
			'selected' => $selected,
			'loop'     => $loop,
		] = $attr;

		/* Length and label required */

		if ( ! $length || ! $label ) {
			return '';
		}

		/* Output */

		return '';
	}

} // End Slider
