<?php
/**
 * Collapsible block
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

class Collapsible {

	/**
	 * Variables
	 */

	public static $blocks = [
		'collapsibles' => [
			'attr'             => [
				'accordion'    => ['type' => 'boolean'],
				'accordion_id' => ['type' => 'string'],
			],
			'default'          => [
				'accordion'    => false,
				'accordion_id' => '',
			],
			'provides_context' => [
				'collapsibles/accordion'    => 'accordion',
				'collapsibles/accordion_id' => 'accordion_id',
			],
			'render'           => [__CLASS__, 'render_collapsibles'],
			'handle'           => 'collapsibles',
			'script'           => 'collapsibles/collapsibles.js',
		],
		'collapsible'  => [
			'attr'         => [
				'title'         => ['type' => 'string'],
				'heading_level' => ['type' => 'string'],
				'open'          => ['type' => 'boolean'],
			],
			'default'      => [
				'title'         => '',
				'heading_level' => 'h3',
				'open'          => false,
			],
			'uses_context' => [
				'collapsibles/accordion',
				'collapsibles/accordion_id',
			],
			'render'       => [__CLASS__, 'render_collapsible'],
			'handle'       => 'collapsible',
			'script'       => 'collapsibles/collapsible.js',
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

	public static function render_collapsibles( $attributes, $content, $block ) {
		return (
			'<div class="l-flex l-flex-column l-gap-margin-2xs l-gap-margin-xs-m">' .
				$content .
			'</div>'
		);
	}

	public static function render_collapsible( $attributes, $content, $block ) {
		$attributes = array_replace_recursive( self::$blocks['collapsible']['default'], $attributes );

		/* Destructure */

		[
			'title'         => $title,
			'heading_level' => $heading_level,
			'open'          => $open,
		] = $attributes;

		/* Id for aria controls */

		$id = 'c-' . uniqid();

		/* Attributes */

		$attr = '';

		$accordion    = $block->context[ PI::$namespace . '/collapsibles/accordion' ] ?? false;
		$accordion_id = $block->context[ PI::$namespace . '/collapsibles/accordion_id' ] ?? false;

		if ( $accordion && $accordion_id ) {
			$attr = " data-accordion='$accordion_id'";
		}

		/* Main classes */

		$main_classes = 'l-padding-top-2xs t t-inherit l-margin-bottom-2xs-all l-margin-0-last ' . PI::$editor_classes;

		/* Arrow */

		/* phpcs:ignore */
		$arrow_icon = file_get_contents( PI::$svg_assets_path . 'arrow-down.svg' ); // Ignore: local path

		/* Output */

		return (
			'<div>' .
				"<div class='o-collapsible l-padding-top-2xs l-padding-bottom-2xs l-padding-right-2xs l-padding-left-2xs l-padding-top-xs-l l-padding-bottom-xs-l l-padding-right-xs-l l-padding-left-xs-l b-radius-m b-all b-primary-tint e-transition e-bg-primary-tint-15'$attr>" .
					"<$heading_level class='t-h4 l-margin-0'>" .
						"<button class='o-collapsible__toggle l-flex l-width-100-pc t-align-left t-current' type='button' aria-controls='$id'>" .
							'<span class="l-flex l-flex-grow-1 l-align-center l-justify-between l-gap-margin-2xs">' .
								"<span>$title</span>" .
								'<span>' .
									'<span class="o-collapsible__icon l-width-xs l-height-xs l-svg l-flex e-transition">' .
										$arrow_icon .
									'</span>' .
								'</span>' .
							'</span>' .
						'</button>' .
					"</$heading_level>" .
					"<div class='o-collapsible__main e-transition' id='$id'>" .
						"<div class='$main_classes'>" .
							$content .
						'</div>' .
					'</div>' .
				'</div>' .
			'</div>'
		);
	}

} // End Collapsible
