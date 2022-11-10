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

				$block_content = "<div class='b-radius-s b-all l-overflow-x-auto' data-table>$block_content</div>";
			}
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
