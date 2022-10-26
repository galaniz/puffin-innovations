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
				'internal_name'   => ['type' => 'string'],
				'tag'             => ['type' => 'string'],
				'width_mobile'    => ['type' => 'string'],
				'width'           => ['type' => 'string'],
				'width_immediate' => ['type' => 'boolean'],
				'align'           => ['type' => 'string'],
				'justify'         => ['type' => 'string'],
				'grow'            => ['type' => 'boolean'],
				'quote_mark'      => ['type' => 'boolean'],
				'editor_styles'   => ['type' => 'boolean'],
			],
			'default' => [
				'internal_name'   => '',
				'tag'             => 'div',
				'width_mobile'    => '',
				'width'           => '',
				'width_immediate' => false,
				'align'           => '',
				'justify'         => '',
				'grow'            => false,
				'quote_mark'      => false,
				'editor_styles'   => false,
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
			'tag'             => $tag,
			'width_mobile'    => $width_mobile,
			'width'           => $width,
			'width_immediate' => $width_immediate,
			'align'           => $align,
			'justify'         => $justify,
			'grow'            => $grow,
			'quote_mark'      => $quote_mark,
			'editor_styles'   => $editor_styles,
		] = $attr;

		/* Classes */

		$classes = 'l-flex l-flex-column l-width-100-pc';

		/* Attributes */

		$atr = '';

		/* Grow */

		if ( $grow ) {
			$classes .= ' l-flex-grow-1';
		}

		/* Align */

		if ( $align ) {
			$classes .= " l-align-$align";
		}

		/* Justify */

		if ( $justify ) {
			$classes .= " l-justify-$justify";
		}

		/* Width */

		if ( $width_mobile ) {
			$classes .= " l-width-$width_mobile";

			if ( ! $width_immediate ) {
				$classes .= '-s';
			}
		}

		if ( $width && $width !== $width_mobile ) {
			$classes .= " l-width-$width-l";
		}

		/* Editor styles */

		if ( $editor_styles ) {
			$classes .= ' ' . PI::$editor_classes;
		}

		/* Quote mark */

		if ( 'blockquote' === $tag && $quote_mark ) {
			$atr .= ' data-quote';
		}

		/* Output */

		return (
			"<$tag class='$classes'$atr>" .
				$content .
			"</$tag>"
		);
	}

} // End Column
