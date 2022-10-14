<?php
/**
 * Container block
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

class Container {

	/**
	 * Variables
	 */

	public static $blocks = [
		'container' => [
			'attr'    => [
				'outer_tag'             => ['type' => 'string'],
				'tag'                   => ['type' => 'string'],
				'column'                => ['type' => 'boolean'],
				'wrap'                  => ['type' => 'boolean'],
				'fill_width'            => ['type' => 'boolean'],
				'align'                 => ['type' => 'string'],
				'justify'               => ['type' => 'string'],
				'gap_mobile'            => ['type' => 'string'],
				'gap'                   => ['type' => 'boolean'],
				'padding_top_mobile'    => ['type' => 'string'],
				'padding_top'           => ['type' => 'string'],
				'padding_bottom_mobile' => ['type' => 'string'],
				'padding_bottom'        => ['type' => 'string'],
				'bg_color'              => ['type' => 'string'],
				'bg_color_slug'         => ['type' => 'string'],
			],
			'default' => [
				'outer_tag'             => 'section',
				'tag'                   => 'div',
				'column'                => true,
				'wrap'                  => true,
				'fill_width'            => false,
				'align'                 => '',
				'justify'               => '',
				'gap_mobile'            => 'm',
				'gap'                   => 'm',
				'padding_top_mobile'    => 'l',
				'padding_top'           => '2xl',
				'padding_bottom_mobile' => 'l',
				'padding_bottom'        => '2xl',
				'bg_color'              => '',
				'bg_color_slug'         => 'background-light',
			],
			'render'  => [__CLASS__, 'render_container'],
			'handle'  => 'container',
			'script'  => 'container.js',
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

	public static function render_container( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['container']['default'], $attributes );

		/* Destructure */

		[
			'outer_tag'             => $outer_tag,
			'tag'                   => $tag,
			'column'                => $column,
			'wrap'                  => $wrap,
			'fill_width'            => $fill_width,
			'align'                 => $align,
			'justify'               => $justify,
			'gap_mobile'            => $gap_mobile,
			'gap'                   => $gap,
			'padding_top_mobile'    => $padding_top_mobile,
			'padding_top'           => $padding_top,
			'padding_bottom_mobile' => $padding_bottom_mobile,
			'padding_bottom'        => $padding_bottom,
			'bg_color_slug'         => $bg_color_slug,
		] = $attr;

		/* Classes */

		$outer_classes = '';
		$classes       = 'l-flex l-flex-column';

		/* Wrap */

		if ( $wrap ) {
			$classes .= ' l-flex-wrap';
		}

		/* Row */

		if ( ! $column ) {
			$classes .= ' l-flex-row-s';
		}

		/* Gap */

		if ( $gap_mobile ) {
			$classes .= " l-gap-margin-$gap";
		}

		if ( $gap ) {
			$classes .= " l-gap-margin-$gap-l";
		}

		/* Padding */

		if ( $padding_top_mobile ) {
			$outer_classes .= " l-padding-top-$padding_top_mobile";
		}

		if ( $padding_top ) {
			$outer_classes .= " l-padding-top-$padding_top-l";
		}

		if ( $padding_bottom_mobile ) {
			$outer_classes .= " l-padding-bottom-$padding_bottom_mobile";
		}

		if ( $padding_bottom ) {
			$outer_classes .= " l-padding-bottom-$padding_bottom-l";
		}

		/* Background */

		if ( $bg_color_slug ) {
			$outer_classes .= " bg-$bg_color_slug";
		}

		/* Output */

		if ( $outer_classes ) {
			$outer_classes = " class='$outer_classes'";
		}

		return (
			"<$outer_tag$outer_classes>" .
				( ! $fill_width ? '<div class="l-container">' : '' ) .
					"<$tag class='$classes'>" .
						$content .
					"</$tag>" .
				( ! $fill_width ? '</div>' : '' ) .
			"</$outer_tag>"
		);
	}

} // End Container
