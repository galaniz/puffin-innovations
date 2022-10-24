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

	public static $blocks = [
		'image' => [
			'attr'         => [
				'id'            => ['type' => 'integer'],
				'width_mobile'  => ['type' => 'string'],
				'width'         => ['type' => 'string'],
				'height'        => ['type' => 'string'],
				'aspect_ratio'  => ['type' => 'string'],
				'border_radius' => ['type' => 'string'],
				'cover'         => ['type' => 'boolean'],
				'order_first'   => ['type' => 'boolean'],
				'is_link'       => ['type' => 'boolean'],
				'link'          => ['type' => 'string'],
				'opacity'       => ['type' => 'integer'],
				'position'      => ['type' => 'string'],
			],
			'default'      => [
				'id'            => 0,
				'width_mobile'  => '',
				'width'         => '',
				'height'        => '',
				'aspect_ratio'  => '',
				'border_radius' => '',
				'cover'         => true,
				'order_first'   => false,
				'is_link'       => false,
				'link'          => '',
				'opacity'       => 100,
				'position'      => 'center-center',
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
			'aspect_ratio'  => $aspect_ratio,
			'border_radius' => $border_radius,
			'cover'         => $cover,
			'order_first'   => $order_first,
			'is_link'       => $is_link,
			'link'          => $link,
			'opacity'       => $opacity,
			'position'      => $position,
		] = $attr;

		/* Id required */

		if ( ! $id ) {
			return '';
		}

		/* Figure classes */

		$figure_classes = '';
		$figure_attr    = '';

		/* Width */

		if ( $width_mobile ) {
			$figure_classes .= " l-width-$width_mobile";
		}

		if ( $width && $width !== $width_mobile ) {
			$figure_classes .= " l-width-$width-l";
		}

		/* Height */

		if ( $height ) {
			$figure_classes .= " l-height-$height";
		}

		/* Aspect ratio */

		if ( $aspect_ratio ) {
			$figure_classes .= " l-relative l-overflow-hidden l-aspect-ratio-$aspect_ratio";
		}

		/* Border radius */

		if ( $border_radius ) {
			$figure_classes .= " b-radius-$border_radius";

			if ( 'xl' === $border_radius ) {
				$figure_classes .= '-fluid';
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
			$width         = esc_attr( $image['width'] );
			$height        = esc_attr( $image['height'] );
			$image_classes = 'l-width-100-pc';

			if ( $aspect_ratio ) {
				$image_classes .= ' l-absolute l-top-0 l-left-0 l-height-100-pc';
			}

			if ( $height && ! $aspect_ratio ) {
				$image_classes .= ' l-height-100-pc';
			}

			$image_classes .= ' l-object-' . ( $cover ? 'cover' : 'contain' ) . " l-object-$position";

			$image_output = "<img class='$image_classes' src='$src' alt='$alt' srcset='$srcset' sizes='$sizes' width='$width' height='$height'>";
		}

		/* Order */

		if ( $order_first ) {
			$figure_classes .= ' l-order-first';
		}

		/* Opacity */

		if ( 100 !== $opacity ) {
			$opacity = $opacity / 100;

			$figure_attr = " style='opacity:$opacity'";
		}

		/* Classes */

		$figure_classes = trim( $figure_classes );

		if ( $figure_classes ) {
			$figure_classes = " class='$figure_classes'";
		}

		/* Link */

		if ( $is_link && $link && $image_output ) {
			$external  = PI::is_external_url( $link );
			$link_attr = $external ? ' target="_blank" rel="noopener noreferrer"' : '';

			$in_card = $block->context[ PI::$namespace . '/card/exists' ] ?? false;

			if ( $in_card ) {
				return (
					"<a href='$link'$link_attr class='l-before'>" .
						"<div$figure_classes>" .
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
			"<figure$figure_classes$figure_attr>" .
				$image_output .
			'</figure>'
		);
	}

} // End Image
