<?php
/**
 * PI theme class
 *
 * @package puffin-innovations
 */

namespace PI;

/* If file is called directly abort. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Imports
 */

use PI\Common\Blocks\Index as Blocks;
use PI\Common\Blocks\Hero;
use PI\Common\Blocks\Container;
use Formation\Formation as FRM;
use Formation\Utils;
use Formation\Admin\Settings\Theme;
use Formation\Common\Blocks\Contact_Form;
use function Formation\additional_script_data;

/**
 * Class
 */

class PI extends FRM {

	/**
	 * Variables
	 */

	public static $colors = [];

	/* Assets path for svgs */

	public static $svg_assets_path = '';

	/* A11y classes */

	public static $a11y_class = [
		'visually_hide' => 'a11y-visually-hidden',
		'hide'          => 'a11y-hide-input',
	];

	/* Editor style classes */

	public static $editor_classes = 't-list e-underline e-underline-thick';

	/* Static markup */

	public static $html = [
		'result' => [
			'error'   => [
				'default' => '',
				'summary' => '',
			],
			'success' => '',
		],
	];

	/* Padding options */

	public static $padding_options = [
		[
			'label' => 'None',
			'value' => '',
		],
		[
			'label' => '5px',
			'value' => '5xs',
		],
		[
			'label' => '10px',
			'value' => '4xs',
		],
		[
			'label' => '15px',
			'value' => '3xs',
		],
		[
			'label' => '20px',
			'value' => '2xs',
		],
		[
			'label' => '30px',
			'value' => 'xs',
		],
		[
			'label' => '40px',
			'value' => 's',
		],
		[
			'label' => '60px',
			'value' => 'm',
		],
		[
			'label' => '80px',
			'value' => 'l',
		],
		[
			'label' => '100px',
			'value' => 'xl',
		],
		[
			'label' => '120px',
			'value' => '2xl',
		],
		[
			'label' => '140px',
			'value' => '3xl',
		],
	];

	/* Html element options */

	public static $html_options = [
		[
			'label' => 'Div',
			'value' => 'div',
		],
		[
			'label' => 'Unordered List',
			'value' => 'ul',
		],
		[
			'label' => 'Ordered List',
			'value' => 'ol',
		],
		[
			'label' => 'List Item',
			'value' => 'li',
		],
		[
			'label' => 'Figure',
			'value' => 'figure',
		],
		[
			'label' => 'Figcaption',
			'value' => 'figcaption',
		],
		[
			'label' => 'Blockquote',
			'value' => 'blockquote',
		],
		[
			'label' => 'Section',
			'value' => 'section',
		],
		[
			'label' => 'Article',
			'value' => 'article',
		],
		[
			'label' => 'Aside',
			'value' => 'aside',
		],
		[
			'label' => 'Header',
			'value' => 'header',
		],
		[
			'label' => 'Footer',
			'value' => 'footer',
		],
		[
			'label' => 'Address',
			'value' => 'address',
		],
	];

	/* Hero theme */

	public static $hero_theme = 'background-light';

	/**
	 * Constructor
	 */

	public function __construct() {
		/* Namespace */

		self::$namespace = 'pi';

		/* Paths */

		self::$svg_assets_path = get_template_directory() . '/assets/public/svg/';

		/* Markup */

		/* phpcs:disable */
		$error_icon = file_get_contents( PI::$svg_assets_path . 'error.svg' );
		$success_icon = file_get_contents( PI::$svg_assets_path . 'success.svg' );
		/* phpcs:enable */

		self::$html['result']['success'] = (
			'<div class="o-form-result__positive l-width-100-pc l-none outline-none" role="alert" tabindex="-1">' .
				'<div class="o-form__positive l-padding-left-2xs l-padding-right-2xs l-padding-top-2xs l-padding-bottom-2xs">' .
					'<div class="l-flex l-gap-margin-3xs">' .
						'<div>' .
							'<div class="l-width-s l-height-s l-svg">' .
								$success_icon .
							'</div>' .
						'</div>' .
						'<div>' .
							'<h2 class="t-h4 l-margin-0 l-padding-top-5xs l-padding-bottom-5xs o-form-result__primary"></h2>' .
							'<p class="t t-current o-form-result__secondary"></p>' .
						'</div>' .
					'</div>' .
				'</div>' .
			'</div>'
		);

		self::$html['result']['error']['default'] = (
			'<div class="o-form-result__negative l-width-100-pc l-none outline-none" role="alert" tabindex="-1">' .
				'<div class="o-form__negative l-padding-left-2xs l-padding-right-2xs l-padding-top-2xs l-padding-bottom-2xs">' .
					'<div class="l-flex l-gap-margin-3xs">' .
						'<div>' .
							'<div class="l-width-s l-height-s l-svg">' .
								$error_icon .
							'</div>' .
						'</div>' .
						'<div>' .
							'<h2 class="t-h4 l-margin-0 l-padding-top-5xs l-padding-bottom-5xs o-form-result__primary"></h2>' .
							'<p class="t t-current o-form-result__secondary"></p>' .
						'</div>' .
					'</div>' .
				'</div>' .
			'</div>'
		);

		self::$html['result']['error']['summary'] = (
			'<div class="o-form-error__summary l-width-100-pc l-none outline-none" tabindex="-1">' .
				'<div class="o-form__negative l-padding-left-2xs l-padding-right-2xs l-padding-top-2xs l-padding-bottom-2xs">' .
					'<div class="l-flex l-gap-margin-3xs">' .
						'<div>' .
							'<div class="l-width-s l-height-s l-svg">' .
								$error_icon .
							'</div>' .
						'</div>' .
						'<div>' .
							'<h2 class="t-h4 l-margin-0 l-padding-top-5xs l-padding-bottom-5xs">There is a problem</h2>' .
							'<ul class="l-flex l-flex-column l-margin-bottom-5xs-all l-margin-0-last t t-link-current t-list-style-none e-underline o-form-error__list" role="list"></ul>' .
						'</div>' .
					'</div>' .
				'</div>' .
			'</div>'
		);

		/* Styles and scripts */

		self::$width_options = [
			[
				'label' => 'None',
				'value' => '',
			],
			[
				'label' => 'Auto',
				'value' => 'auto',
			],
			[
				'label' => '100%',
				'value' => '1-1',
			],
			[
				'label' => '80%',
				'value' => '4-5',
			],
			[
				'label' => '75%',
				'value' => '3-4',
			],
			[
				'label' => '66%',
				'value' => '2-3',
			],
			[
				'label' => '60%',
				'value' => '3-5',
			],
			[
				'label' => '50%',
				'value' => '1-2',
			],
			[
				'label' => '40%',
				'value' => '2-5',
			],
			[
				'label' => '33%',
				'value' => '1-3',
			],
			[
				'label' => '25%',
				'value' => '1-4',
			],
			[
				'label' => '20%',
				'value' => '1-5',
			],
		];

		self::$gap_options = [
			[
				'label' => 'None',
				'value' => '',
			],
			[
				'label' => '10px',
				'value' => '4xs',
			],
			[
				'label' => '15px',
				'value' => '3xs',
			],
			[
				'label' => '20px',
				'value' => '2xs',
			],
			[
				'label' => '30px',
				'value' => 'xs',
			],
			[
				'label' => '40px',
				'value' => 's',
			],
			[
				'label' => '50px',
				'value' => 'sm',
			],
			[
				'label' => '60px',
				'value' => 'm',
			],
			[
				'label' => '80px',
				'value' => 'l',
			],
			[
				'label' => '100px',
				'value' => 'xl',
			],
		];

		self::$styles = [
			[
				'handle' => 'style',
				'url'    => get_template_directory_uri() . '/style.css',
			],
		];

		self::$scripts = [
			[
				'handle' => 'script-compat',
				'url'    => get_template_directory_uri() . '/assets/public/js/' . self::$namespace . '-compat.js',
			],
			[
				'handle' => 'script',
				'url'    => get_template_directory_uri() . '/assets/public/js/' . self::$namespace . '.js',
			],
		];

		self::$script_ver = '1.0.0';

		self::$dequeue_gutenberg = true;

		self::$script_attributes = [
			'script-compat' => 'nomodule',
			'script'        => 'type="module"',
		];

		self::$defer_script_handles = [
			self::$namespace . 'script-compat',
			self::$namespace . 'script',
		];

		/* Theme colors */

		self::$colors = [
			[
				'name'  => 'Orange',
				'slug'  => 'primary-base',
				'color' => '#e05920',
			],
			[
				'name'  => 'Yellow',
				'slug'  => 'primary-light',
				'color' => '#ffc14a',
			],
			[
				'name'  => 'Blue',
				'slug'  => 'primary-tint',
				'color' => '#4c94d5',
			],
			[
				'name'  => 'Blue Dark',
				'slug'  => 'primary-dark',
				'color' => '#1b62a4',
			],
			[
				'name'  => 'Black',
				'slug'  => 'foreground-dark',
				'color' => '#141415',
			],
			[
				'name'  => 'Grey Light',
				'slug'  => 'background-base',
				'color' => '#e9e9e9',
			],
			[
				'name'  => 'White',
				'slug'  => 'background-light',
				'color' => '#ffffff',
			],
			[
				'name'  => 'Blue Light',
				'slug'  => 'background-dark',
				'color' => '#cbe0f3',
			],
		];

		self::$editor_color_palette = self::$colors;

		self::$editor_style = '/PI/Admin/assets/public/css/editor.css';

		/* Embed variations */

		self::$embed_variations = [
			'facebook',
			'instagram',
			'twitter',
			'tiktok',
			'vimeo',
			'youtube',
		];

		/* Image sizes */

		self::$image_sizes = [
			'2000w' => 2000,
			'1600w' => 1600,
			'1200w' => 1200,
			'600w'  => 600,
		];

		/* Navigations */

		self::$nav_menus = [
			'main'   => 'Main',
			'footer' => 'Footer',
			'social' => 'Social',
			'legal'  => 'Legal',
		];

		/* Parent constructor */

		parent::__construct();

		/* Blocks */

		$contact_form_blocks = new Contact_Form();
		$blocks              = new Blocks();

		/* Settings */

		if ( is_admin() ) {
			$theme_settings = new Theme(
				[
					'mailchimp' => true,
					'fields'    => [
						[
							'name'    => 'tagline',
							'label'   => 'Tagline',
							'type'    => 'textarea',
							'section' => 'logo',
							'tab'     => 'General',
							'attr'    => [
								'rows'      => 3,
								'data-full' => '',
							],
						],
					],
				]
			);
		}

		/* Pass data to front end */

		additional_script_data( self::$namespace, ['padding_options' => self::$padding_options ], true, true );
		additional_script_data( self::$namespace, ['html_options' => self::$html_options ], true, true );

		/* Actions */

		add_action( 'widgets_init', [$this, 'register_widgets'] );
		add_action( 'wp', [$this, 'wp'] );

		/* Filters */

		add_filter( 'paginate_links_output', [$this, 'filter_paginate_links_output'], 10, 2 );
		add_filter( 'formation_contact_form_args', [$this, 'filter_contact_form_args'], 10, 2 );
		add_filter( 'formation_contact_form_field_args', [$this, 'filter_contact_field_args'], 10, 2 );
		add_filter( 'formation_contact_form_group_classes', [$this, 'filter_contact_group_classes'], 10, 3 );
	}

	/**
	 * Get text color with sufficient contrast.
	 */

	public static function get_text_color( $bg = 'foreground-dark' ) {
		$text_color = 'foreground-dark';

		if ( 'foreground-dark' === $bg || 'primary-dark' === $bg ) {
			$text_color = 'background-light';
		}

		return $text_color;
	}

	/**
	 * Output loader.
	 */

	public static function render_loader( $size = 'xs', $hide = true, $aria_hidden = true, $a11y_hide_text = '' ) {
		$attr = '';

		if ( $hide ) {
			$attr .= ' data-hide';
		}

		if ( $aria_hidden ) {
			$attr .= ' aria-hidden="true"';
		}

		if ( $a11y_hide_text ) {
			$a11y_hide_text = "<span class='a11y-visually-hidden reduce-motion-hide'>$a11y_hide_text</span>";
		}

		return (
			"<span class='o-loader l-absolute l-top-0 l-left-0 l-right-0 l-bottom-0 l-flex l-align-center l-justify-center e-transition outline-none'$attr>" .
				"<span class='l-width-$size l-height-$size b-radius-100-pc reduce-motion-hide'></span>" .
				'<span class="l-none reduce-motion-show">Loading</span>' .
				$a11y_hide_text .
				"<span class='l-width-$size l-height-$size b-radius-100-pc l-absolute l-top-0 l-left-0 l-right-0 l-bottom-0 l-margin-auto reduce-motion-hide'></span>" .
			'</span>'
		);
	}

	/**
	 * Output list from loop.
	 */

	public static function render_list() {
		global $wp_query;

		$output = '<ul class="t-list-style-none l-flex l-flex-column l-gap-margin-s l-gap-margin-m-l e-underline e-underline-thick" role="list">';

		/* The loop */

		while ( have_posts() ) {
			the_post();

			$heading_classes = 't-h3 t-link';

			$id      = get_the_ID();
			$link    = get_the_permalink();
			$title   = get_the_title();
			$excerpt = self::get_excerpt(
				[
					'post_id' => $id,
					'words'   => true,
					'length'  => 30,
				]
			);

			if ( $excerpt ) {
				$excerpt = "<p class='t'>$excerpt</p>";

				$heading_classes .= ' l-margin-0 l-margin-bottom-3xs';
			}

			$output .= (
				'<li>' .
					"<h2 class='$heading_classes'>" .
						"<a href='$link'>$title</a>" .
					'</h2>' .
					$excerpt .
				'</li>'
			);
		}

		$output .= '</ul>';

		/* Pagination */

		$big = 999999999; // Need an unlikely integer

		/* phpcs:ignore */
		$arrow_left = file_get_contents( PI::$svg_assets_path . 'arrow-left.svg' ); // Ignore: local path

		/* phpcs:ignore */
		$arrow_right = file_get_contents( PI::$svg_assets_path . 'arrow-right.svg' ); // Ignore: local path

		$pagination = paginate_links(
			[
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'type'      => 'list',
				'next_text' => (
					'<span class="a11y-visually-hidden">Next</span>' .
					'<div class="l-svg l-height-100-pc l-width-100-pc">' .
						$arrow_right .
					'</div>'
				),
				'prev_text' => (
					'<span class="a11y-visually-hidden">Next</span>' .
					'<div class="l-svg l-height-100-pc l-width-100-pc">' .
						$arrow_left .
					'</div>'
				),
			]
		);

		if ( $pagination ) {
			$output .= "<nav class='c-pagination l-padding-top-m l-padding-top-l-l' aria-label='Pagination'>$pagination</nav>";
		}

		/* Output */

		return $output;
	}

	/**
	 * Output no content found.
	 */

	public static function render_content_none() {
		$is_404 = is_404();

		/* Message */

		$message = 'Looks like nothing was found in this location. Maybe try a search?';

		if ( is_search() ) {
			$message = 'Sorry, but nothing matched your search terms.';
		}

		/* Hero */

		self::$hero_theme = 'primary-base';

		$hero = Hero::render_hero(
			[
				'title_large'   => $is_404 ? '404' : 'Nothing Found',
				'text'          => $message,
				'bg_color_slug' => 'primary-base',
			]
		);

		/* Content */

		$content = Container::render_container(
			[
				'tag'                   => 'section',
				'bg_color_slug'         => 'background-light',
				'contain'               => true,
				'padding_top_mobile'    => 'm',
				'padding_top'           => 'l',
				'padding_bottom_mobile' => 'xl',
				'padding_bottom'        => '2xl',
			],
			'<div class="l-width-1-1 l-width-3-4-l">' .
				self::render_form_search(
					[
						'form_class'   => 'o-form o-form-round o-form-search l-relative',
						'field_class'  => '',
						'input_class'  => 'l-height-m',
						'button_class' => 'l-absolute l-right-0 l-bottom-0 l-top-0 l-flex l-align-center l-justify-center l-width-m l-height-m t-current',
						'icon_class'   => 'l-flex l-width-xs l-height-xs l-svg',
						'icon_path'    => self::$svg_assets_path . 'search.svg',
						'a11y_class'   => 'a11y-visually-hidden',
					]
				) .
			'</div>'
		);

		/* Output */

		return $hero . $content;
	}

	/**
	 * Check if light text required.
	 */

	public static function is_text_light( $bg = 'background-light' ) {
		return 'foreground-dark' === $bg || 'primary-dark' === $bg;
	}

	/**
	 * Check if extra dark text required.
	 */

	public static function is_text_dark( $bg = 'background-light' ) {
		return 'primary-base' === $bg || 'primary-tint' === $bg;
	}

	/**
	 * Register widget area.
	 */

	public function register_widgets() {
		$n = self::$namespace;

		register_sidebar(
			[
				'name'          => 'Footer Contact Form',
				'id'            => "$n-footer-contact-form",
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
			]
		);
	}

	/**
	 * After WP object is set up.
	 */

	public function wp() {
		global $post;

		$hero_theme = '';

		if ( is_object( $post ) && isset( $post->ID ) ) {
			$id = $post->ID;

			if ( is_home() ) {
				$id = (int) get_option( 'page_for_posts' );
			}

			$hero_theme = get_post_meta( $id, self::$namespace . '_hero_theme', true );
		}

		if ( is_search() ) {
			$hero_theme = 'background-base';
		}

		if ( is_404() ) {
			$hero_theme = 'primary-base';
		}

		if ( $hero_theme ) {
			self::$hero_theme = $hero_theme;
		}
	}

	/**
	 * Filter pagination output.
	 */

	public function filter_paginate_links_output( $output, $args ) {
		$link_classes = 'l-flex l-align-center l-justify-center l-width-xs l-width-s-m l-height-xs l-height-s-m t-h5 b-radius-100-pc';

		$output = str_replace(
			[
				'page-numbers current',
				'page-numbers dots"',
				"class='page-numbers'",
				'<ul',
				'<a class="',
				'b-all e-transition-border-radius next',
				'b-all e-transition-border-radius prev',
			],
			[
				$link_classes . ' bg-foreground-dark t-background-light',
				$link_classes . '" aria-hidden="true"',
				'',
				'<ul class="t-list-style-none l-flex l-justify-center l-gap-margin-3xs l-gap-margin-2xs-m" role="list"',
				'<a class="' . $link_classes . ' b-all e-transition-border-radius ',
				'e-scale e-transition next',
				'e-scale e-transition prev',
			],
			$output
		);

		return $output;
	}

	/**
	 * Filter contact form args.
	 */

	public function filter_contact_form_args( $args, $attr ) {
		$form_class         = 'o-form';
		$fields_class       = 'l-flex l-flex-column l-flex-row-xl l-flex-wrap l-align-end-xl';
		$button_class       = 'o-button-primary l-width-100-pc';
		$button_field_class = '';
		$gap                = 'l-gap-margin-xs l-gap-margin-s-m';

		if ( 'mailchimp' === $attr['type'] ) {
			$form_class        .= ' o-form-small o-form-round';
			$button_field_class = 'l-margin-top-auto';
			$gap                = 'l-gap-margin-2xs';
		} else {
			$button_class .= ' o-button-large';
		}

		$fields_class .= " $gap";

		$args['form_class']         = $form_class;
		$args['fields_class']       = $fields_class;
		$args['button_class']       = $button_class;
		$args['button_field_class'] = $button_field_class;
		$args['button_loader']      = self::render_loader();
		$args['error_summary']      = self::$html['result']['error']['summary'];
		$args['error_result']       = self::$html['result']['error']['default'];
		$args['success_result']     = self::$html['result']['success'];
		$args['a11y_class']         = self::$a11y_class['visually_hide'];

		return $args;
	}

	/**
	 * Filter contact group classes.
	 */

	public function filter_contact_group_classes( $classes, $attr, $block ) {
		$classes['container_class'] = 'l-width-100-pc';
		$classes['fieldset_class']  = 'o-form__group';
		$classes['fields_class']    = 'l-flex l-flex-column l-gap-margin-2xs';

		return $classes;
	}

	/**
	 * Filter contact field args.
	 */

	public function filter_contact_field_args( $field, $attr ) {
		$old_field_class = $field['field_class'] ?? '';
		$field_class     = 'l-flex-grow-1 l-relative';
		$type            = $field['type'] ?? '';
		$radio           = strpos( $type, 'radio' ) !== false;
		$checkbox        = strpos( $type, 'checkbox' ) !== false;
		$width           = $attr['width'] ?? false;

		/* Field class */

		if ( $width ) {
			if ( '1-1' === $width ) {
				$field_class .= ' l-width-100-pc';
			} else {
				$field_class .= " l-width-$width-xl";
			}
		}

		if ( 'radio-select' === $type || 'radio-text' === $type ) {
			$field_class .= ' l-flex l-flex-column l-flex-row-m l-align-center-m l-gap-margin-2xs';
		}

		if ( $old_field_class ) {
			$field_class = $old_field_class . " $field_class";
		}

		$field['field_class'] = $field_class;

		/* Class */

		if ( $radio || $checkbox ) {
			$class     = self::$a11y_class['hide'];
			$old_class = $field['class'] ?? '';

			if ( $old_class ) {
				$class = $old_class . " $class";
			}

			$field['class'] = $class;
		}

		return $field;
	}

} // End PI
