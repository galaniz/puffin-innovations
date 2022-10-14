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
		new Blocks(
			[
				'folder_url' => get_template_directory_uri() . '/PI/Common/assets/public/js/blocks/',
			]
		);

		/* Filters */

		add_filter( 'allowed_block_types_all', [$this, 'allowed_block_types'], 10, 2 );

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
			'core/pullquote',
			'core/image',
			'core/video',
			'core/embed',
			'core/file',
			'core/button',
			'core/buttons',
			'core/table',
			'core/separator',
			'core/shortcode',
			"$n/contact-form",
			"$n/contact-form-group",
			"$n/contact-form-field",
			"$n/hero",
			"$n/container",
			"$n/column",
		];
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
