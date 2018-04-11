<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package a-z-listing
 */

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */
function a_z_listing_block_init() {
	$dir = dirname( __FILE__ );

	$block_js = 'a-z-listing/block.js';
	wp_register_script(
		'a-z-listing-block-editor',
		plugins_url( $block_js, __FILE__ ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		),
		filemtime( "$dir/$block_js" )
	);

	$editor_css = 'a-z-listing/editor.css';
	wp_register_style(
		'a-z-listing-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(
			'wp-blocks',
		),
		filemtime( "$dir/$editor_css" )
	);

	register_block_type( 'a-z-listing/a-z-listing', array(
		'editor_script'   => 'a-z-listing-block-editor',
		'editor_style'    => 'a-z-listing-block-editor',
		'style'           => 'a-z-listing',
		'render_callback' => 'a_z_shortcode_handler',
	) );
}
add_action( 'init', 'a_z_listing_block_init' );
