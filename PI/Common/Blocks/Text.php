<?php
/**
 * Text block
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

class Text {

	/**
	 * Variables
	 */

	public static $blocks = [
		'text' => [
			'attr'         => [
				'text'                  => ['type' => 'string'],
				'tag'                   => ['type' => 'string'],
				'style'                 => ['type' => 'string'],
				'padding_top_mobile'    => ['type' => 'string'],
				'padding_top'           => ['type' => 'string'],
				'padding_bottom_mobile' => ['type' => 'string'],
				'padding_bottom'        => ['type' => 'string'],
				'is_link'               => ['type' => 'boolean'],
				'link'                  => ['type' => 'string'],
			],
			'default'      => [
				'text'                  => '',
				'tag'                   => 'p',
				'style'                 => '',
				'padding_top_mobile'    => '',
				'padding_top'           => '',
				'padding_bottom_mobile' => '',
				'padding_bottom'        => '',
				'is_link'               => false,
				'link'                  => '',
			],
			'uses_context' => [
				'card/exists',
			],
			'render'       => [__CLASS__, 'render_text'],
			'handle'       => 'text',
			'script'       => 'text.js',
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

	public static function render_text( $attributes, $content, $block ) {
		$attr       = array_replace_recursive( self::$blocks['text']['default'], $attributes );
		$class_name = $attributes['className'] ?? '';

		/* Destructure */

		[
			'text'                  => $text,
			'tag'                   => $tag,
			'style'                 => $style,
			'padding_top_mobile'    => $padding_top_mobile,
			'padding_top'           => $padding_top,
			'padding_bottom_mobile' => $padding_bottom_mobile,
			'padding_bottom'        => $padding_bottom,
			'is_link'               => $is_link,
			'link'                  => $link
		] = $attr;

		/* Text required */

		if ( ! $text ) {
			return '';
		}

		/* Outer classes */

		$outer_classes = '';

		/* Padding */

		if ( $padding_top_mobile ) {
			$outer_classes .= " l-padding-top-$padding_top_mobile";
		}

		if ( $padding_top && $padding_top !== $padding_top_mobile ) {
			$outer_classes .= " l-padding-top-$padding_top-l";
		}

		if ( $padding_bottom_mobile ) {
			$outer_classes .= " l-padding-bottom-$padding_bottom_mobile";
		}

		if ( $padding_bottom && $padding_bottom !== $padding_bottom_mobile ) {
			$outer_classes .= " l-padding-bottom-$padding_bottom-l";
		}

		/* Text classes */

		$classes = '';

		/* Style */

		if ( $style ) {
			$classes = $style;
		}

		/* Link */

		if ( $is_link && $link ) {
			$external  = PI::is_external_url( $link );
			$link_attr = $external ? ' target="_blank" rel="noopener noreferrer"' : '';

			$in_card = $block->context[ PI::$namespace . '/card/exists' ] ?? false;

			if ( $in_card ) {
				$link_attr .= ' class="l-block l-before"';
			}

			$link_attr = trim( $link_attr );

			$text = "<a href='$link'$link_attr>$text</a>";
		}

		/* Class name */

		if ( $class_name ) {
			$classes .= " $class_name";
		}

		/* Classes */

		$classes = trim( $classes );

		if ( $classes ) {
			$classes = " class='$classes'";
		}

		$outer_classes = trim( $outer_classes );

		if ( $outer_classes ) {
			$outer_classes = " class='$outer_classes'";
		}

		/* Output */

		return (
			( $outer_classes ? "<div$outer_classes>" : '' ) .
				"<$tag$classes>" .
					$text .
				"</$tag>" .
			( $outer_classes ? '</div>' : '' )
		);
	}

} // End Text
