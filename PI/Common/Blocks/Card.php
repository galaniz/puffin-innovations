<?php
/**
 * Card block
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

class Card {

	/**
	 * Variables
	 */

	public static $blocks = [
		'card' => [
			'attr'             => [
				'exists' => ['type' => 'boolean'], // For context purposes
				'border' => ['type' => 'boolean'],
			],
			'default'          => [
				'exists' => true,
				'border' => false,
			],
			'provides_context' => [
				'card/exists' => 'exists',
			],
			'render'           => [__CLASS__, 'render_card'],
			'handle'           => 'card',
			'script'           => 'card.js',
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

	public static function render_card( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['card']['default'], $attributes );

		/* Destructure */

		[ 'border' => $border ] = $attr;

		/* Classes */

		$classes = 'l-relative l-flex l-flex-column';

		/* Border */

		if ( $border ) {
			$classes .= ' l-padding-top-xs l-padding-bottom-xs l-padding-left-s l-padding-right-s b-radius-m b-all b-primary-tint-65 e-transition e-bg-primary-tint-15';
		}

		/* Output */

		return (
			"<div class='$classes'>" .
				$content .
			'</div>'
		);
	}

} // End Card
