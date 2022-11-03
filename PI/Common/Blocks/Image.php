<?php
/**
 * Image block
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

class Image {

	/**
	 * Variables
	 */

	public static $flex_positions = [
		'left-top'      => 'l-justify-start l-align-start',
		'left-center'   => 'l-justify-start l-align-center',
		'left-bottom'   => 'l-justify-start l-align-end',
		'center-top'    => 'l-justify-center l-align-start',
		'center-center' => 'l-justify-center l-align-center',
		'center-bottom' => 'l-justify-center l-align-end',
		'right-top'     => 'l-justify-end l-align-start',
		'right-center'  => 'l-justify-end l-align-center',
		'right-bottom'  => 'l-justify-end l-align-end',
	];

	public static $blocks = [
		'image' => [
			'attr'         => [
				'id'            => ['type' => 'integer'],
				'width_mobile'  => ['type' => 'string'],
				'width'         => ['type' => 'string'],
				'height'        => ['type' => 'string'],
				'inner_width'   => ['type' => 'string'],
				'inner_height'  => ['type' => 'string'],
				'aspect_ratio'  => ['type' => 'string'],
				'border_radius' => ['type' => 'string'],
				'cover'         => ['type' => 'boolean'],
				'order_first'   => ['type' => 'boolean'],
				'is_link'       => ['type' => 'boolean'],
				'link'          => ['type' => 'string'],
				'opacity'       => ['type' => 'integer'],
				'position'      => ['type' => 'string'],
				'bg_color'      => ['type' => 'string'],
				'bg_color_slug' => ['type' => 'string'],
			],
			'default'      => [
				'id'            => 0,
				'width_mobile'  => '',
				'width'         => '',
				'height'        => '',
				'inner_width'   => '1-1',
				'inner_height'  => '100-pc',
				'aspect_ratio'  => '',
				'border_radius' => '',
				'cover'         => true,
				'order_first'   => false,
				'is_link'       => false,
				'link'          => '',
				'opacity'       => 100,
				'position'      => 'center-center',
				'bg_color'      => '',
				'bg_color_slug' => '',
			],
			'uses_context' => [
				'card/exists',
			],
			'render'       => [__CLASS__, 'render_image'],
			'handle'       => 'image',
			'script'       => 'image.js',
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

	public static function render_image( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['image']['default'], $attributes );

		/* Destructure */

		[
			'id'            => $id,
			'width_mobile'  => $width_mobile,
			'width'         => $width,
			'height'        => $height,
			'inner_width'   => $inner_width,
			'inner_height'  => $inner_height,
			'aspect_ratio'  => $aspect_ratio,
			'border_radius' => $border_radius,
			'cover'         => $cover,
			'order_first'   => $order_first,
			'is_link'       => $is_link,
			'link'          => $link,
			'opacity'       => $opacity,
			'position'      => $position,
			'bg_color_slug' => $bg_color_slug,
		] = $attr;

		/* Id required */

		if ( ! $id ) {
			return '';
		}

		/* Outer classes */

		$outer_classes = '';
		$outer_attr    = '';

		/* Width */

		if ( $width_mobile ) {
			$outer_classes .= " l-width-$width_mobile";
		}

		if ( $width && $width !== $width_mobile ) {
			$outer_classes .= " l-width-$width-l";
		}

		/* Height */

		if ( $height ) {
			$outer_classes .= " l-height-$height";
		}

		/* Aspect ratio */

		if ( $aspect_ratio ) {
			$outer_classes .= " l-relative l-overflow-hidden l-aspect-ratio-$aspect_ratio";
		}

		/* Border radius */

		if ( $border_radius ) {
			if ( ! $aspect_ratio ) {
				$outer_classes .= ' l-overflow-hidden';
			}

			$outer_classes .= " b-radius-$border_radius";

			if ( 'xl' === $border_radius ) {
				$outer_classes .= '-fluid';
			}
		}

		/* Image */

		$image        = PI::get_image( $id, 'medium' );
		$image_output = '';

		if ( $image ) {
			$src           = esc_attr( $image['url'] );
			$alt           = esc_attr( $image['alt'] );
			$srcset        = esc_attr( $image['srcset'] );
			$sizes         = esc_attr( $image['sizes'] );
			$w             = esc_attr( $image['width'] );
			$h             = esc_attr( $image['height'] );
			$image_classes = "l-width-$inner_width l-height-$inner_height";

			if ( '100-pc' !== $inner_height ) {
				$outer_classes .= ' l-flex ' . self::$flex_positions[ $position ];
			}

			if ( $aspect_ratio ) {
				$image_classes .= ' l-absolute l-top-0 l-left-0';
			}

			$image_classes .= ' l-object-' . ( $cover ? 'cover' : 'contain' ) . " l-object-$position";

			$image_output = "<img class='$image_classes' src='$src' alt='$alt' srcset='$srcset' sizes='$sizes' width='$w' height='$h' loading='lazy'>";
		}

		/* Order */

		if ( $order_first ) {
			$outer_classes .= ' l-order-first';
		}

		/* Opacity */

		if ( 100 !== $opacity ) {
			$opacity = $opacity / 100;

			$outer_attr = " style='opacity:$opacity'";
		}

		/* Background color */

		if ( $bg_color_slug ) {
			$outer_classes .= " bg-$bg_color_slug";
		}

		/* Classes */

		$outer_classes = trim( $outer_classes );

		if ( $outer_classes ) {
			$outer_classes = " class='$outer_classes'";
		}

		/* Link */

		if ( $is_link && $link && $image_output ) {
			$external  = PI::is_external_url( $link );
			$link_attr = $external ? ' target="_blank" rel="noopener noreferrer"' : '';

			$in_card = $block->context[ PI::$namespace . '/card/exists' ] ?? false;

			if ( $in_card ) {
				return (
					"<a href='$link'$link_attr class='l-before'>" .
						"<div$outer_classes>" .
							$image_output .
						'</div>' .
					'</a>'
				);
			} else {
				$image_output = "<a href='$link'$link_attr>$image_output</a>";
			}
		}

		/* Output */

		return (
			"<div$outer_classes$outer_attr>" .
				$image_output .
			'</div>'
		);
	}

} // End Image
