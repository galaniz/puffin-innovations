<?php
/**
 * Column block
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

class Column {

	/**
	 * Variables
	 */

	public static $blocks = [
		'column' => [
			'attr'    => [
				'internal_name' => ['type' => 'string'],
				'tag'           => ['type' => 'string'],
				'width_mobile'  => ['type' => 'string'],
				'width'         => ['type' => 'string'],
				'justify'       => ['type' => 'string'],
				'grow'          => ['type' => 'boolean'],
			],
			'default' => [
				'internal_name' => '',
				'tag'           => 'div',
				'width_mobile'  => '',
				'width'         => '',
				'justify'       => '',
				'grow'          => false,
			],
			'render'  => [__CLASS__, 'render_column'],
			'handle'  => 'column',
			'script'  => 'column.js',
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

	public static function render_column( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['column']['default'], $attributes );

		/* Destructure */

		[
			'tag'          => $tag,
			'width_mobile' => $width_mobile,
			'width'        => $width,
			'justify'      => $justify,
			'grow'         => $grow,
		] = $attr;

		/* Classes */

		$classes = 'l-flex l-flex-column l-width-100-pc';

		/* Grow */

		if ( $grow ) {
			$classes .= ' l-flex-grow-1';
		}

		/* Justify */

		if ( $justify ) {
			$classes .= " l-justify-$justify";
		}

		/* Width */

		if ( $width_mobile ) {
			$classes .= " l-width-$width_mobile-s";
		}

		if ( $width && $width !== $width_mobile ) {
			$classes .= " l-width-$width-l";
		}

		/* Output */

		return (
			"<$tag class='$classes'>" .
				$content .
			"</$tag>"
		);
	}

} // End Column
