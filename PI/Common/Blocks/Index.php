<?php
/**
 * Custom gutenberg blocks for common use
 *
 * @package puffin-innovations
 */

namespace PI\Common\Blocks;

/**
 * Imports
 */

use PI\PI as PI;
use PI\Common\Blocks\Hero;
use PI\Common\Blocks\Container;
use PI\Common\Blocks\Column;
use PI\Common\Blocks\Text;
use PI\Common\Blocks\Image;
use PI\Common\Blocks\Card;
use PI\Common\Blocks\Number;
use PI\Common\Blocks\Collapsible;
use PI\Common\Blocks\Slider;
use Formation\Common\Blocks\Blocks;
use function Formation\additional_script_data;

/**
 * Class
 */

class Index {

	/**
	 * Variables
	 */

	public static $insert_blocks = [];
	public static $insert_hero   = ['post', 'page'];

	/**
	 * Constructor
	 */

	public function __construct() {
		/* Add blocks */

		new Hero();
		new Container();
		new Column();
		new Text();
		new Image();
		new Card();
		new Number();
		new Collapsible();
		new Slider();
		new Blocks(
			[
				'folder_url' => get_template_directory_uri() . '/PI/Common/assets/public/js/blocks/',
			]
		);

		/* Filters */

		add_filter( 'allowed_block_types_all', [$this, 'allowed_block_types'], 10, 2 );
		add_filter( 'render_block', [$this, 'render_block'], 10, 2 );

		/* Insert block data */

		add_action( 'admin_enqueue_scripts', [$this, 'default_block_data'] );
	}

	/**
	 * Limit Gutenberg blocks.
	 */

	public function allowed_block_types( $allowed_blocks, $block_editor_context ) {
		$n = PI::$namespace;

		return [
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/html',
			'core/quote',
			'core/image',
			'core/video',
			'core/embed',
			'core/file',
			'core/button',
			'core/buttons',
			'core/table',
			'core/separator',
			'core/shortcode',
			'core/block',
			"$n/contact-form",
			"$n/contact-form-group",
			"$n/contact-form-field",
			"$n/hero",
			"$n/container",
			"$n/column",
			"$n/text",
			"$n/image",
			"$n/card",
			"$n/number",
			"$n/collapsibles",
			"$n/collapsible",
			"$n/slider",
			"$n/slide",
			"$n/slide-content",
		];
	}

	/**
	 * Filter Gutenberg block output.
	 */

	public function render_block( $block_content, $block ) {
		$name = $block['blockName'];

		/* Button */

		if ( 'core/button' === $name ) {
			$class_name       = $block['attrs']['className'] ?? '';
			$background_color = $block['attrs']['backgroundColor'] ?? 'foreground-dark';
			$text_color       = $block['attrs']['textColor'] ?? 'foreground-dark';

			/* Primary or secondary */

			$primary = strpos( $class_name, 'is-style-outline' ) !== false ? false : true;

			if ( $primary ) {
				$text_color = PI::get_text_color( $background_color );
			} else {
				$background_color = '';
			}

			/* Classes */

			$classes = '';

			if ( $text_color ) {
				$classes .= " t-$text_color";
			}

			if ( $background_color ) {
				$classes .= " bg-$background_color";
			}

			/* Insert */

			$block_content = str_replace( 'wp-block-button__link', "wp-block-button__link$classes", $block_content );
		}

		/* Table */

		if ( 'core/table' === $name ) {
			$block_content = str_replace(
				'<th>',
				'<th scope="col">',
				$block_content
			);

			/* Regex */

			$table_match = preg_match( '/<figure[^>]*>(.*?)<\/figure>/', $block_content, $matches );
			$table       = $matches[1] ?? '';

			if ( $table ) {
				$block_content = $table;

				$caption_match = preg_match( '/<figcaption[^>]*>(.*?)<\/figcaption>/', $block_content, $matches );
				$caption       = $matches[1] ?? '';

				if ( $caption ) {
					$table_array   = explode( '<figcaption', $block_content );
					$block_content = $table_array[0];

					$block_content = str_replace(
						'<table>',
						"<table><caption>$caption</caption>",
						$block_content
					);
				}

				$block_content = (
					'<div class="l-overflow-hidden l-isolate b-radius-s b-all l-relative" data-table>' .
						'<div class="l-overflow-x-auto l-width-100-pc l-before l-after o-overflow">' .
							$block_content .
						'</div>' .
					'</div>'
				);
			}
		}

		/* External link */

		if ( strpos( $block_content, 'rel="noreferrer noopener"' ) !== false && strpos( $block_content, 'svg data-external' ) === false ) {
			$block_content = str_replace(
				'</a>',
				' <svg data-external width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg"><path d="M14.167 10.8333V15.8333C14.167 16.0633 14.0745 16.2708 13.9228 16.4225C13.7712 16.5741 13.5637 16.6666 13.3337 16.6666H4.16699C3.93699 16.6666 3.72949 16.5741 3.57783 16.4225C3.42616 16.2708 3.33366 16.0633 3.33366 15.8333V6.66663C3.33366 6.43663 3.42616 6.22913 3.57783 6.07746C3.72949 5.92579 3.93699 5.83329 4.16699 5.83329H9.16699C9.62699 5.83329 10.0003 5.45996 10.0003 4.99996C10.0003 4.53996 9.62699 4.16663 9.16699 4.16663H4.16699C3.47699 4.16663 2.85033 4.44746 2.39949 4.89913C1.94866 5.35079 1.66699 5.97663 1.66699 6.66663V15.8333C1.66699 16.5233 1.94783 17.15 2.39949 17.6008C2.85116 18.0516 3.47699 18.3333 4.16699 18.3333H13.3337C14.0237 18.3333 14.6503 18.0525 15.1012 17.6008C15.552 17.1491 15.8337 16.5233 15.8337 15.8333V10.8333C15.8337 10.3733 15.4603 9.99996 15.0003 9.99996C14.5403 9.99996 14.167 10.3733 14.167 10.8333ZM8.92283 12.2558L16.667 4.51163V7.49996C16.667 7.95996 17.0403 8.33329 17.5003 8.33329C17.9603 8.33329 18.3337 7.95996 18.3337 7.49996V2.49996C18.3337 2.38663 18.3112 2.27913 18.2703 2.18079C18.2295 2.08246 18.1695 1.99079 18.0903 1.91163C18.0895 1.91079 18.0895 1.91079 18.0887 1.90996C18.012 1.83329 17.9203 1.77163 17.8195 1.72996C17.7212 1.68913 17.6137 1.66663 17.5003 1.66663H12.5003C12.0403 1.66663 11.667 2.03996 11.667 2.49996C11.667 2.95996 12.0403 3.33329 12.5003 3.33329H15.4887L7.74449 11.0775C7.41866 11.4033 7.41866 11.9308 7.74449 12.2558C8.07033 12.5808 8.59783 12.5816 8.92283 12.2558V12.2558Z" fill="currentColor"/></svg></a>',
				$block_content
			);
		}

		/* Output */

		return $block_content;
	}

	/**
	 * Pass insert block data to front end.
	 */

	public function default_block_data( $hook ) {
		if ( ! in_array( $hook, ['post.php', 'post-new.php'], true ) ) {
			return;
		}

		$screen = get_current_screen();
		$pt     = $screen->post_type;
		$n      = PI::$namespace;

		foreach ( self::$insert_hero as $h ) {
			if ( $pt === $h ) {
				self::$insert_blocks[] = [
					'name' => "$n/hero",
				];
			}
		}

		if ( ! count( self::$insert_blocks ) ) {
			return;
		}

		additional_script_data( $n, ['insert_blocks' => self::$insert_blocks], true, true );
	}

} // End Index
