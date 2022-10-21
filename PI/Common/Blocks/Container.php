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
				'internal_name'         => ['type' => 'string'],
				'tag'                   => ['type' => 'string'],
				'layout'                => ['type' => 'string'],
				'wrap'                  => ['type' => 'boolean'],
				'contain'               => ['type' => 'boolean'],
				'align'                 => ['type' => 'string'],
				'justify'               => ['type' => 'string'],
				'gap_mobile'            => ['type' => 'string'],
				'gap'                   => ['type' => 'string'],
				'padding_top_mobile'    => ['type' => 'string'],
				'padding_top'           => ['type' => 'string'],
				'padding_bottom_mobile' => ['type' => 'string'],
				'padding_bottom'        => ['type' => 'string'],
				'bg_color'              => ['type' => 'string'],
				'bg_color_slug'         => ['type' => 'string'],
				'bg_color_custom'       => ['type' => 'string'],
				'bg_seamless'           => ['type' => 'boolean'],
				'quote_mark'            => ['type' => 'boolean'],
				'editor_styles'         => ['type' => 'boolean'],
			],
			'default' => [
				'internal_name'         => '',
				'tag'                   => 'div',
				'layout'                => 'block',
				'wrap'                  => true,
				'contain'               => false,
				'align'                 => '',
				'justify'               => '',
				'gap_mobile'            => '',
				'gap'                   => '',
				'padding_top_mobile'    => '',
				'padding_top'           => '',
				'padding_bottom_mobile' => '',
				'padding_bottom'        => '',
				'bg_color'              => '',
				'bg_color_slug'         => '',
				'bg_color_custom'       => '',
				'bg_seamless'           => false,
				'quote_mark'            => false,
				'editor_styles'         => false,
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
			'tag'                   => $tag,
			'layout'                => $layout,
			'wrap'                  => $wrap,
			'contain'               => $contain,
			'align'                 => $align,
			'justify'               => $justify,
			'gap_mobile'            => $gap_mobile,
			'gap'                   => $gap,
			'padding_top_mobile'    => $padding_top_mobile,
			'padding_top'           => $padding_top,
			'padding_bottom_mobile' => $padding_bottom_mobile,
			'padding_bottom'        => $padding_bottom,
			'bg_color_slug'         => $bg_color_slug,
			'bg_color_custom'       => $bg_color_custom,
			'bg_seamless'           => $bg_seamless,
			'quote_mark'            => $quote_mark,
			'editor_styles'         => $editor_styles,
		] = $attr;

		/* Classes */

		$classes = '';

		/* Attributes */

		$atr = '';

		/* Layout */

		$flex = 'column' === $layout || 'row' === $layout;

		if ( 'block' === $layout ) {
			$classes = 'l-block';
		}

		if ( 'column' === $layout ) {
			$classes = 'l-flex l-flex-column';
		}

		if ( 'row' === $layout ) {
			$classes = 'l-flex';
		}

		/* Tag */

		$list = 'ul' === $tag || 'ol' === $tag;

		if ( $list ) {
			$classes .= ' t-list-style-none';
			$atr     .= ' role="list"';
		}

		if ( 'blockquote' === $tag ) {
			$classes .= ' l-margin-0';
		}

		/* Wrap */

		if ( $wrap && 'row' === $layout ) {
			$classes .= ' l-flex-wrap';
		}

		/* Flex properties */

		if ( $flex ) {
			/* Gap */

			if ( $gap_mobile ) {
				$classes .= " l-gap-margin-$gap_mobile";
			}

			if ( $gap && $gap !== $gap_mobile ) {
				$classes .= " l-gap-margin-$gap-l";
			}

			/* Align */

			if ( $align ) {
				$classes .= " l-align-$align";
			}

			/* Justify */

			if ( $justify ) {
				$classes .= " l-justify-$justify";
			}
		}

		/* Padding */

		if ( $padding_top_mobile ) {
			$classes .= " l-padding-top-$padding_top_mobile";
		}

		if ( $padding_top && $padding_top !== $padding_top_mobile ) {
			$classes .= " l-padding-top-$padding_top-l";
		}

		if ( $padding_bottom_mobile ) {
			$classes .= " l-padding-bottom-$padding_bottom_mobile";
		}

		if ( $padding_bottom && $padding_bottom !== $padding_bottom_mobile ) {
			$classes .= " l-padding-bottom-$padding_bottom-l";
		}

		/* Background */

		if ( $bg_color_slug || $bg_color_custom ) {
			$classes .= ' b-radius-xl';
		}

		if ( $bg_color_slug ) {
			$classes .= " bg-$bg_color_slug";

			if ( PI::is_text_light( $bg_color_slug ) ) {
				$classes .= ' t-light';
			}
		}

		if ( $bg_color_custom ) {
			$atr .= " style='background-color:$bg_color_custom'";
		}

		/* Seamless */

		if ( $bg_seamless ) {
			$classes .= ' l-relative l-before bg-seamless';
		}

		/* Quote mark */

		if ( 'blockquote' === $tag && $quote_mark ) {
			$atr .= ' data-quote';
		}

		/* Editor styles */

		if ( $editor_styles ) {
			$classes .= ' t-list e-underline e-underline-thick';
		}

		/* Output */

		$classes = trim( $classes );

		if ( $classes ) {
			$classes = " class='$classes'";
		}

		return (
			"<$tag$classes$atr>" .
				( 'block' === $layout && $contain ? '<div class="l-container">' : '' ) .
					$content .
				( 'block' === $layout && $contain ? '</div>' : '' ) .
			"</$tag>"
		);
	}

} // End Container
