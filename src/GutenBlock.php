<?php
/**
 * Server-side rendering of the `a-z-listing` block.
 *
 * @package WordPress
 */

declare(strict_types=1);

namespace A_Z_Listing;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Server-side rendering of the `a-z-listing` block implementation class.
 *
 * @package WordPress
 */
class GutenBlock extends Singleton implements Extension {
	/**
	 * Render the block.
	 *
	 * @since 4.0.0
	 * @param array $attributes The block configured attributes.
	 * @return string The block content.
	 */
	public function render( $attributes ) {
		global $shortcode_tags;
		if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) || ! array_key_exists( 'a-z-listing', $shortcode_tags ) ) {
			return 'The A-Z Listing plugin has been disabled.';
		}

		return call_user_func( $shortcode_tags['a-z-listing'], $attributes );
	}

	/**
	 * Register and initialize the block.
	 *
	 * @since 4.0.0
	 * @return void
	 * @throws \Error When the plugin has not been correctly built.
	 */
	final public function initialize() {
		$script_asset_path = dirname( A_Z_LISTING_PLUGIN_FILE ) . '/build/index.asset.php';
		if ( ! file_exists( $script_asset_path ) ) {
			throw new \Error(
				'You need to run `npm start` or `npm run build` for the "a-z-listing/block" block first.'
			);
		}
		$index_js     = 'build/index.js';
		$script_asset = require $script_asset_path;
		wp_register_script(
			'a-z-listing-block-editor',
			plugins_url( $index_js, A_Z_LISTING_PLUGIN_FILE ),
			$script_asset['dependencies'],
			A_Z_LISTING_VERSION,
			true
		);

		$editor_css = 'css/editor.css';
		wp_register_style(
			'a-z-listing-block-editor',
			plugins_url( $editor_css, A_Z_LISTING_PLUGIN_FILE ),
			array(),
			A_Z_LISTING_VERSION
		);

		$style_css = 'css/a-z-listing-default.css';
		wp_register_style(
			'a-z-listing-block',
			plugins_url( $style_css, A_Z_LISTING_PLUGIN_FILE ),
			array(),
			A_Z_LISTING_VERSION
		);

		$attributes = json_decode( file_get_contents( dirname( A_Z_LISTING_PLUGIN_FILE ) . '/scripts/blocks/attributes.json' ), true );  //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$attributes = apply_filters( 'a_z_listing_get_gutenberg_attributes', $attributes );

		register_block_type(
			'a-z-listing/block',
			array(
				'editor_script'   => 'a-z-listing-block-editor',
				'editor_style'    => 'a-z-listing-block-editor',
				'style'           => 'a-z-listing-block',
				'render_callback' => array( $this, 'render' ),
				'attributes'      => $attributes,
			)
		);
	}
}
