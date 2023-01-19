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
		'slider'        => [
			'attr'             => [
				'ids'       => ['type' => 'string'],
				'titles'    => ['type' => 'string'],
				'tab_texts' => ['type' => 'string'],
				'type'      => ['type' => 'string'],
				'label'     => ['type' => 'string'],
				'selected'  => ['type' => 'string'],
				'loop'      => ['type' => 'boolean'],
				'width'     => ['type' => 'string'],
				'length'    => ['type' => 'integer'],
			],
			'default'          => [
				'ids'       => '',
				'titles'    => '',
				'tab_texts' => '',
				'type'      => 'group',
				'label'     => '',
				'selected'  => '0',
				'loop'      => false,
				'width'     => '100%',
				'length'    => 1,
			],
			'provides_context' => [
				'slider/type'     => 'type',
				'slider/label'    => 'label',
				'slider/selected' => 'selected',
				'slider/width'    => 'width',
				'slider/length'   => 'length',
			],
			'render'           => [__CLASS__, 'render_slider'],
			'handle'           => 'slider',
			'script'           => 'slider/slider.js',
		],
		'slide'         => [
			'attr'             => [
				'internal_name' => ['type' => 'string'],
				'index'         => ['type' => 'integer'],
				'id'            => ['type' => 'string'],
				'title'         => ['type' => 'string'],
				'title_tag'     => ['type' => 'string'],
				'length'        => ['type' => 'integer'],
				'tab_text'      => ['type' => 'string'],
			],
			'default'          => [
				'internal_name' => '',
				'index'         => 0,
				'id'            => '',
				'title'         => '',
				'title_tag'     => 'h3',
				'length'        => 1,
				'tab_text'      => '',
			],
			'provides_context' => [
				'slide/title' => 'title',
			],
			'uses_context'     => [
				'slider/type',
				'slider/label',
				'slider/selected',
				'slider/width',
				'slider/length',
			],
			'render'           => [__CLASS__, 'render_slide'],
			'handle'           => 'slide',
			'script'           => 'slider/slide.js',
		],
		'slide-content' => [
			'attr'         => [
				'internal_name' => ['type' => 'string'],
				'index'         => ['type' => 'integer'],
			],
			'default'      => [
				'internal_name' => '',
				'index'         => 0,
			],
			'uses_context' => [
				'slide/title',
				'slider/type',
			],
			'render'       => [__CLASS__, 'render_slide_content'],
			'handle'       => 'slide-content',
			'script'       => 'slider/slide-content.js',
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
			'ids'       => $ids,
			'titles'    => $titles,
			'tab_texts' => $tab_texts,
			'type'      => $type,
			'label'     => $label,
			'selected'  => $selected,
			'loop'      => $loop,
			'width'     => $width,
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

		/* Titles */

		if ( 'group-flex' === $type && $titles ) {
			$titles = explode( ',', $titles );
		} else {
			$titles = [];
		}

		/* Tab texts */

		if ( $tab_texts ) {
			$tab_texts = explode( ',', $tab_texts );
		} else {
			$tab_texts = [];
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

			$tab_label = "$label group $index";

			if ( isset( $titles[ $i ] ) ) {
				$tab_label = $titles[ $i ] . ' group';
			}

			$tab_text_span = '';

			if ( isset( $tab_texts[ $i ] ) ) {
				$tab_text_span = '<span class="t-xs l-margin-top-4xs">' . $tab_texts[ $i ] . '</span>';
			}

			$tablist .= (
				'<li class="l-flex" role="presentation">' .
					"<button class='o-slider__tab t-current l-flex l-flex-column l-align-center l-padding-left-5xs l-padding-right-5xs l-padding-top-5xs l-padding-bottom-5xs' type='button' role='tab' tabindex='$tabindex' aria-selected='$selected' aria-label='$tab_label'$max_width>" .
						'<span class="l-block b-radius-100-pc b-all"></span>' .
						$tab_text_span .
					'</button>' .
				'</li>'
			);
		}

		/* Slider attributes */

		$slider_attr = " data-type='$type'";

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
				"<div class='o-slider__nav'>" .
					"<ul class='o-slider__tabs l-flex l-justify-center l-padding-top-s l-padding-top-m-l l-margin-0 t-list-style-none' role='tablist' aria-label='$label controls'>" .
						$tablist .
					'</ul>' .
					"<button type='button' class='o-slider__prev t-current l-width-s l-height-s l-svg l-absolute l-left-0 l-none outline-tight' aria-label='Previous $label group' data-prev disabled>" .
						$arrow_left .
					'</button>' .
					"<button type='button' class='o-slider__next t-current l-width-s l-height-s l-svg l-absolute l-right-0 l-none outline-tight' aria-label='Next $label group' data-next>" .
						$arrow_right .
					'</button>' .
				'</div>' .
			'</div>'
		);
	}

	public static function render_slide( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['slide']['default'], $attributes );

		/* Destructure */

		[
			'index'     => $index,
			'id'        => $id,
			'title'     => $title,
			'title_tag' => $title_tag,
			'length'    => $length,
		] = $attr;

		/* Type */

		$type = $block->context[ PI::$namespace . '/slider/type' ] ?? 'group';

		/* Label required */

		$label = $block->context[ PI::$namespace . '/slider/label' ] ?? false;

		if ( ! $label ) {
			return '';
		}

		$i     = $index + 1;
		$label = ucfirst( $label ) . " group $i";

		if ( $title && 'group-flex' === $type ) {
			$label = "$title group";
		}

		/* Selected */

		$selected_index = $block->context[ PI::$namespace . '/slider/selected' ] ?? 0;
		$selected_index = (int) $selected_index;

		$selected  = $index === $selected_index ? 'true' : 'false';
		$tab_index = $index === $selected_index ? 0 : -1;
		$hidden    = $index !== $selected_index ? 'true' : 'false';

		/* Classes */

		$suffix = 'group';

		if ( 'item' === $type ) {
			$suffix = 'item';
		}

		$classes = "o-slider__$suffix l-flex-shrink-0 l-relative outline-none";

		/* Gap */

		$width = $block->context[ PI::$namespace . '/slider/width' ] ?? '100%';

		$gap_large = 'l-gap-margin-s-l';

		if ( '50%' === $width || '33%' === $width ) {
			$gap_large = 'l-gap-margin-m-l';
		}

		/* Inner output & slide attributes */

		$slide_content = '';
		$slide_attr    = '';

		if ( 'group' === $type ) {
			$slide_content = (
				"<div class='o-slider__insert o-slider__trans o-slider__focus l-flex l-before l-relative l-gap-margin-xs $gap_large'>" .
					'<div class="o-slider__inner l-flex l-flex-shrink-0 l-flex-column">' .
						'<div class="outline-tight">' .
							$content .
						'</div>' .
					'</div>' .
				'</div>'
			);
		} elseif ( 'group-flex' === $type ) {
			$is_last_index = false;

			if ( isset( $block->context[ PI::$namespace . '/slider/length' ] ) ) {
				$total = $block->context[ PI::$namespace . '/slider/length' ];

				$is_last_index = $index === $total - 1;
			}

			$length_s  = $length;
			$length_m  = $length;
			$length_xl = $length;

			if ( $is_last_index ) {
				if ( '50%' === $width ) {
					$length_m  = max( $length, 2 );
					$length_xl = max( $length, 2 );
				}

				if ( '33%' === $width ) {
					$length_s  = max( $length, 2 );
					$length_m  = max( $length, 2 );
					$length_xl = max( $length, 3 );
				}

				if ( '25%' === $width ) {
					$length_s  = max( $length, 2 );
					$length_m  = max( $length, 3 );
					$length_xl = max( $length, 4 );
				}
			}

			$slide_attr = " style='--l:$length;--l-s:$length_s;--l-m:$length_m;--l-xl:$length_xl;'";
			$classes   .= ' l-flex l-flex-column';

			$title = $title ? "<$title_tag class='o-slider__trans l-align-self-start l-sticky l-top-0 l-left-0'>$title</$title_tag>" : '';

			$slide_content = (
				'<div class="o-slider__focus l-before l-flex l-flex-grow-1 l-flex-column">' .
					$title .
					"<div class='l-flex l-flex-grow-1 l-gap-margin-xs $gap_large'>" .
						$content .
					'</div>' .
				'</div>'
			);
		} else { // Item
			$slide_content = (
				'<div class="o-slider__trans o-slider__focus l-flex l-before l-relative l-flex-column">' .
					'<div class="outline-tight">' .
						$content .
					'</div>' .
				'</div>'
			);
		}

		/* Output */

		return (
			"<div class='$classes' id='$id' role='tabpanel' tabindex='$tab_index' aria-hidden='$hidden' aria-label='$label' data-selected='$selected'$slide_attr>" .
				$slide_content .
			'</div>'
		);
	}

	public static function render_slide_content( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['slide-content']['default'], $attributes );

		/* Destructure */

		['index' => $index ] = $attr;

		/* Type */

		$type = $block->context[ PI::$namespace . '/slider/type' ] ?? 'group';

		/* Title */

		$title = $block->context[ PI::$namespace . '/slide/title' ] ?? '';

		/* Border in group-flex */

		$border = false;

		if ( 0 === $index && 'group-flex' === $type && $title ) {
			$border = true;
		}

		/* Output */

		$output = $content;

		if ( 'group-flex' === $type ) {
			$output = (
				'<div class="o-slider__inner l-flex l-flex-column l-flex-shrink-0">' .
					'<div class="' . ( $border ? 'o-slider__sep ' : '' ) . 'o-slider__trans l-flex l-flex-grow-1">' .
						'<div class="l-width-100-pc">' .
							$content .
						'</div>' .
					'</div>' .
				'</div>'
			);
		} else {
			$output = "<div>$content</div>";
		}

		return $output;
	}

} // End Slider
