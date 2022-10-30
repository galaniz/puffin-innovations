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
			'attr'             => [
				'ids'      => ['type' => 'string'],
				'label'    => ['type' => 'string'],
				'selected' => ['type' => 'string'],
				'loop'     => ['type' => 'boolean'],
				'width'    => ['type' => 'string'],
			],
			'default'          => [
				'ids'      => '',
				'label'    => '',
				'selected' => '0',
				'loop'     => false,
				'width'    => '100%',
			],
			'provides_context' => [
				'slider/loop'     => 'loop',
				'slider/label'    => 'label',
				'slider/selected' => 'selected',
				'slider/width'    => 'width',
			],
			'render'           => [__CLASS__, 'render_slider'],
			'handle'           => 'slider',
			'script'           => 'slider/slider.js',
		],
		'slide'  => [
			'attr'         => [
				'internal_name' => ['type' => 'string'],
				'index'         => ['type' => 'integer'],
				'id'            => ['type' => 'string'],
			],
			'default'      => [
				'internal_name' => '',
				'index'         => 0,
				'id'            => '',
			],
			'uses_context' => [
				'slider/loop',
				'slider/label',
				'slider/selected',
				'slider/width',
			],
			'render'       => [__CLASS__, 'render_slide'],
			'handle'       => 'slide',
			'script'       => 'slider/slide.js',
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
			'ids'      => $ids,
			'label'    => $label,
			'selected' => $selected,
			'loop'     => $loop,
			'width'    => $width,
		] = $attr;

		/* Ids and label required */

		if ( ! $ids || ! $label ) {
			return '';
		}

		$ids    = explode( ',', $ids );
		$length = count( $ids );

		if ( ! $length ) {
			return '';
		}

		/* Selected */

		$selected_index = (int) $selected;

		/* Create tablist */

		$tablist   = '';
		$math      = round( 100 / $length ) . 'vw - ' . ( round( 100 / $length ) / 16 ) . 'rem';
		$max_width = " style='max-width:calc($math)'";

		foreach ( $ids as $i => $id ) {
			$index    = $i + 1;
			$selected = $i === $selected_index ? 'true' : 'false';
			$tabindex = $i === $selected_index ? 0 : -1;

			$tab_label = "Go to $label group $index";

			$tablist .= (
				'<li class="l-flex" role="presentation">' .
					"<button class='o-slider__tab l-padding-left-5xs l-padding-right-5xs l-padding-top-5xs l-padding-bottom-5xs' type='button' role='tab' tabindex='$tabindex' aria-selected='$selected' aria-label='$tab_label'$max_width>" .
						'<span class="l-block b-radius-100-pc b-all"></span>' .
					'</button>' .
				'</li>'
			);
		}

		/* Slider attributes */

		$slider_attr = '';

		$slider_data = [
			'0'    => 1,
			'600'  => 1,
			'900'  => 1,
			'1200' => 2,
			's'    => '1-1',
			'm'    => '1-1',
			'xl'   => '1-1',
		];

		if ( '50%' === $width ) {
			$slider_data['900']  = 2;
			$slider_data['1200'] = 2;
			$slider_data['m']    = '1-2';
			$slider_data['xl']   = '1-2';
		}

		if ( '33%' === $width ) {
			$slider_data['600']  = 2;
			$slider_data['900']  = 2;
			$slider_data['1200'] = 3;
			$slider_data['s']    = '1-2';
			$slider_data['m']    = '1-2';
			$slider_data['xl']   = '1-3';
		}

		if ( '25%' === $width ) {
			$slider_data['600']  = 2;
			$slider_data['900']  = 3;
			$slider_data['1200'] = 4;
			$slider_data['s']    = '1-2';
			$slider_data['m']    = '1-3';
			$slider_data['xl']   = '1-4';
		}

		foreach ( $slider_data as $k => $v ) {
			$slider_attr .= " data-$k='$v'";
		}

		/* Gap */

		$gap = 's';

		if ( '50%' === $width || '33%' === $width ) {
			$gap = 'm';

			$slider_attr .= ' data-gap-l="m"';
		}

		$gap_class = "l-gap-margin-$gap-l";

		/* Offset */

		$offset = '';

		if ( ! $loop ) {
			$offset = '<div class="o-slider__offset l-flex-shrink-0"></div>';
		}

		/* Arrow icons */

		/* phpcs:ignore */
		$arrow_left = file_get_contents( PI::$svg_assets_path . 'arrow-left.svg' );  // Ignore: local path

		/* phpcs:ignore */
		$arrow_right = file_get_contents( PI::$svg_assets_path . 'arrow-right.svg' );  // Ignore: local path

		/* Output */

		return (
			"<div class='o-slider l-flex l-flex-column l-margin-auto l-relative' role='group'$slider_attr>" .
				'<div class="o-slider__main l-overflow-hidden"' . ( $loop ? ' data-loop="true"' : '' ) . '>' .
					'<div class="o-slider__track l-overflow-x-auto l-padding-bottom-s l-relative" tabindex="-1">' .
						"<div class='l-flex l-gap-margin-xs $gap_class'>" .
							$content .
							$offset .
						'</div>' .
					'</div>' .
				'</div>' .
				'<nav>' .
					"<ul class='o-slider__tabs l-flex l-justify-center l-padding-top-s l-padding-top-m-l l-margin-0 t-list-style-none' role='tablist' aria-label='Select $label group to show'>" .
						$tablist .
					'</ul>' .
					'<button type="button" class="o-slider__prev t-current l-width-s l-height-s l-svg l-absolute l-left-0 l-none outline-tight" aria-label="Go to previous group" data-prev disabled>' .
						$arrow_left .
					'</button>' .
					'<button type="button" class="o-slider__next t-current l-width-s l-height-s l-svg l-absolute l-right-0 l-none outline-tight" aria-label="Go to next group" data-next>' .
						$arrow_right .
					'</button>' .
				'</nav>' .
			'</div>'
		);
	}

	public static function render_slide( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['slide']['default'], $attributes );

		/* Destructure */

		[
			'index' => $index,
			'id'    => $id,
		] = $attr;

		/* Label required */

		$label = $block->context[ PI::$namespace . '/slider/label' ] ?? false;

		if ( ! $label ) {
			return '';
		}

		$i     = $index + 1;
		$label = ucfirst( $label ) . " group $i";

		/* Loop */

		$loop = $block->context[ PI::$namespace . '/slider/loop' ] ?? false;

		/* Selected */

		$selected_index = $block->context[ PI::$namespace . '/slider/selected' ] ?? 0;
		$selected_index = (int) $selected_index;

		$selected  = $index === $selected_index ? 'true' : 'false';
		$tab_index = $index === $selected_index ? 0 : -1;
		$hidden    = $index !== $selected_index ? 'true' : 'false';

		/* Class */

		$suffix = $loop ? 'item' : 'group';

		/* Gap */

		$width = $block->context[ PI::$namespace . '/slider/width' ] ?? '100%';

		$gap_large = 'l-gap-margin-s-l';

		if ( '50%' === $width || '33%' === $width ) {
			$gap_large = 'l-gap-margin-m-l';
		}

		/* Output */

		return (
			"<div class='o-slider__$suffix l-flex-shrink-0 l-relative' id='$id' role='tabpanel' tabindex='$tab_index' aria-hidden='$hidden' aria-label='$label' data-selected='$selected'>" .
				'<div class="o-slider__view l-flex l-before l-relative ' . ( ! $loop ? "l-gap-margin-xs $gap_large" : 'l-flex-column' ) . '">' .
					( ! $loop ? '<div class="o-slider__inner l-flex l-flex-shrink-0 l-flex-column">' : '' ) .
						'<div class="o-slider__content outline-tight">' .
							$content .
						'</div>' .
					( ! $loop ? '</div>' : '' ) .
				'</div>' .
			'</div>'
		);
	}

} // End Slider
