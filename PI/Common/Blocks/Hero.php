<?php
/**
 * Hero block
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

class Hero {

	/**
	 * Variables
	 */

	public static $blocks = [
		'hero' => [
			'attr'    => [
				'title_small'         => ['type' => 'string'],
				'title_large'         => ['type' => 'string'],
				'text'                => ['type' => 'string'],
				'bg_color'            => ['type' => 'string'],
				'bg_color_slug'       => ['type' => 'string'],
				'bg_color_slug_meta'  => ['type' => 'string'],
				'video'               => ['type' => 'boolean'],
				'video_link'          => ['type' => 'string'],
				'primary_link'        => ['type' => 'string'],
				'primary_link_text'   => ['type' => 'string'],
				'secondary_link'      => ['type' => 'string'],
				'secondary_link_text' => ['type' => 'string'],
			],
			'default' => [
				'title_small'         => '',
				'title_large'         => '',
				'text'                => '',
				'bg_color'            => '',
				'bg_color_slug'       => 'background-light',
				'bg_color_slug_meta'  => 'background-light',
				'video'               => false,
				'video_link'          => '',
				'primary_link'        => '',
				'primary_link_text'   => '',
				'secondary_link'      => '',
				'secondary_link_text' => '',
			],
			'render'  => [__CLASS__, 'render_hero'],
			'handle'  => 'hero',
			'script'  => 'hero.js',
		],
	];

	/**
	 * Constructor
	 */

	public function __construct() {
		/* Meta */

		$meta = [
			[
				'name'  => PI::$namespace . '_hero_theme',
				'label' => 'bg_color_slug_meta',
				'type'  => 'string',
				'def'   => '',
			],
		];

		foreach ( $meta as $m ) {
			$label = $m['label'];

			self::$blocks['hero']['attr'][ $label ] = [
				'source' => 'meta',
				'type'   => $m['type'],
				'meta'   => $m['name'],
			];

			self::$blocks['hero']['default'][ $label ] = $m['def'];
		}

		/* Register meta */

		add_action(
			'rest_api_init',
			function() use ( $meta ) {
				foreach ( $meta as $m ) {
					register_meta(
						'post',
						$m['name'],
						[
							'show_in_rest' => true,
							'single'       => true,
							'type'         => $m['type'],
						]
					);
				}
			}
		);

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

	public static function render_hero( $attributes, $content, $block ) {
		$attr = array_replace_recursive( self::$blocks['hero']['default'], $attributes );

		/* Destructure */

		[
			'title_small'         => $title_small,
			'title_large'         => $title_large,
			'text'                => $text,
			'bg_color'            => $bg_color,
			'bg_color_slug'       => $bg_color_slug,
			'video'               => $video,
			'video_link'          => $video_link,
			'primary_link'        => $primary_link,
			'primary_link_text'   => $primary_link_text,
			'secondary_link'      => $secondary_link,
			'secondary_link_text' => $secondary_link_text,
		] = $attr;

		/* Id */

		$id = get_the_ID();

		/* Layout type */

		$layout = 'full';

		/* Widths for media and text */

		$text_width  = '1-2';
		$media_width = '1-2';

		/* Media */

		$media_output = '';
		$media_video  = $video && $video_link;
		$image_id     = (int) get_post_thumbnail_id( $id );

		if ( $image_id ) {
			$image  = PI::get_image( $image_id, '1200w' );
			$button = '';

			if ( $image ) {
				$src           = esc_attr( $image['url'] );
				$alt           = esc_attr( $image['alt'] );
				$srcset        = esc_attr( $image['srcset'] );
				$sizes         = esc_attr( $image['sizes'] );
				$width         = esc_attr( $image['width'] );
				$height        = esc_attr( $image['height'] );
				$image_classes = 'l-absolute l-top-0 l-left-0 l-width-100-pc l-height-100-pc l-object-cover';

				$media_output = "<img class='$image_classes' src='$src' alt='$alt' srcset='$srcset' sizes='$sizes' width='$width' height='$height'>";
			}

			if ( $media_video ) {
				$text_width  = '1-3';
				$media_width = '2-3';

				/* phpcs:ignore */
				$play_icon = file_get_contents( PI::$svg_assets_path . 'play.svg' ); // Ignore: local path

				$button = (
					'<div class="l-absolute l-top-0 l-left-0 l-right-0 l-bottom-0 l-margin-auto l-flex l-align-center l-justify-center l-z-index-1 l-width-1-5 l-max-width-2xl">' .
						'<button type="button" class="l-width-100-pc l-margin-auto l-relative l-aspect-ratio-100 l-flex l-align-center l-justify-center b-radius-100-pc t-background-light bg-background-light-30">' .
							"<span class='l-absolute l-width-4-5 l-svg'>$play_icon</span>" .
							'<span class="a11y-visually-hidden">Play video</span>' .
						'</button>' .
					'</div>'
				);
			}

			if ( $media_output ) {
				$ar             = $media_video ? 56 : 66;
				$figure_classes = "l-aspect-ratio-$ar l-relative l-overflow-hidden b-radius-m b-radius-xl-l bg-background-base";

				if ( $media_video ) {
					$figure_classes .= ' l-after bg-overlay l-flex l-align-center l-justify-center';
				}

				$media_output = (
					"<div class='l-width-100-pc l-width-$media_width-l'>" .
						"<figure class='$figure_classes'>" .
							$media_output .
							$button .
						'</figure>' .
					'</div>'
				);
			}
		}

		/* Title */

		$title_output = '';

		if ( ! $title_small && ! $title_large ) {
			$title_large = get_the_title();
		}

		if ( $title_small ) {
			$title_output .= "<span class='t-h5 l-block'>$title_small </span>";
		}

		if ( $title_large ) {
			$title_output .= (
				'<span class="t-h1 l-block' . ( $title_small ? ' l-padding-top-2xs l-padding-top-xs-l' : '' ) . '">' .
					$title_large .
				'</span>'
			);
		}

		if ( $title_output ) {
			$title_output = "<h1>$title_output</h1>";
		}

		/* Background classes */

		$bg_classes = 'l-padding-bottom-l l-before l-relative c-hero-bg' . ( ! $media_output ? ' l-padding-bottom-xl-l' : '' );

		/* Text */

		$text_output = '';

		if ( $text ) {
			$text_classes = 't-l ' . $bg_classes;
			$text_output  = "<p class='$text_classes'>$text</p>";

			if ( ! $media_output ) {
				$text_output = "<div class='l-width-3-4-xl l-padding-top-xs'>$text_output</div>";
				$layout      = 'partial-left';
			} else {
				$text_output = "<div class='l-width-100-pc l-width-$text_width-l'>$text_output</div>";
				$layout      = 'partial-right';
			}
		} else {
			if ( $media_output ) {
				$text_output = "<div class='l-width-100-pc l-width-$text_width-l $bg_classes'></div>";
				$layout      = 'partial-right';
			}
		}

		/* Section classes */

		$section_classes = 'l-padding-top-s l-padding-top-m-l';

		if ( 'foreground-dark' === $bg_color_slug || 'primary-dark' === $bg_color_slug ) {
			$section_classes .= ' t-light';
		}

		if ( ! $text_output && ! $media_output ) {
			$section_classes .= " $bg_classes";
		}

		/* Output */

		return (
			"<section class='$section_classes' style='--theme:var(--$bg_color_slug)' data-layout='$layout'>" .
				'<div class="l-container">' .
					$title_output .
					( $text_output && $media_output ? '<div class="l-flex l-flex-wrap l-align-start l-gap-margin-s l-gap-margin-m-l l-padding-top-s l-padding-top-m-l">' : '' ) .
						$media_output .
						$text_output .
					( $text_output && $media_output ? '</div>' : '' ) .
				'</div>' .
			'</section>'
		);
	}

} // End Hero
