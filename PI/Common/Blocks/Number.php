<?php
/**
 * Number block
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

class Number {

	/**
	 * Variables
	 */

	public static $blocks = [
		'number' => [
			'attr'    => [
				'number' => ['type' => 'string'],
			],
			'default' => [
				'number' => '1',
			],
			'render'  => [__CLASS__, 'render_number'],
			'handle'  => 'number',
			'script'  => 'number.js',
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

	public static function render_number( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['number']['default'], $attributes );

		/* Destructure */

		['number' => $number] = $attr;

		/* Output */

		return (
			'<div class="o-number l-width-m l-width-l-l l-height-m l-height-l-l l-inline-flex l-justify-center l-align-center b-radius-100-pc t-h2">' .
				$number .
			'</div>'
		);
	}

} // End Number
