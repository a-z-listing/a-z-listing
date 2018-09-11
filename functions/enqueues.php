<?php
/**
 * A-Z Listing Styles and Scripts enqueue functions
 *
 * @package a-z-listing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register default A-Z stylesheet, jQuery-UI Tabs script and add our enqueue
 * functions to the `wp_enqueue_scripts` action
 *
 * @since 2.0.0 Renamed from a_z_listing_add_styling. Added jQuery-UI Tabs support.
 */
function a_z_listing_do_enqueue() {
	wp_register_style( 'a-z-listing', plugins_url( 'css/a-z-listing-default.css', dirname( __FILE__ ) ), array( 'dashicons' ) );
	wp_register_script( 'a-z-listing-tabs', plugins_url( 'js/a-z-listing-tabs.js', dirname( __FILE__ ) ), array( 'jquery', 'jquery-ui-tabs' ) );

	$add_styles = get_option( 'a-z-listing-add-styling', true );
	/**
	 * Determine whether to add default listing styling
	 *
	 * @param bool True to add default styling, False to disable.
     * @since 1.7.1
	 */
	$add_styles = apply_filters( 'a_z_listing_add_styling', $add_styles );
	/**
	 * Determine whether to add default listing styling
	 *
	 * @param bool True to add default styling, False to disable.
	 * @since 1.7.1
	 */
	$add_styles = apply_filters( 'a-z-listing-add-styling', $add_styles );

	if ( AZLISTINGLOG ) {
		do_action( 'log', 'A-Z Listing: Add Styles', $add_styles );
	}
	if ( true === $add_styles && ! has_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' ) ) {
		add_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' );
	}

	$tabify = get_option( 'a-z-listing-add-tabs', false );
	/**
	 * Determine whether to add jQuery-UI Tabs
	 *
	 * @param bool True to add jQuery-UI Tabs, False to disable.
	 * @since 2.0.0
	 */
	$tabify = apply_filters( 'a_z_listing_tabify', $tabify );
	/**
	 * Determine whether to add jQuery-UI Tabs
	 *
	 * @param bool True to add jQuery-UI Tabs, False to disable.
	 * @since 2.0.0
	 */
	$tabify = apply_filters( 'a-z-listing-tabify', $tabify );

	if ( AZLISTINGLOG ) {
		do_action( 'log', 'A-Z Listing: Tabify', $tabify );
	}
	if ( true === $tabify && ! has_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_tabs' ) ) {
		add_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_tabs' );
	}
}
add_action( 'init', 'a_z_listing_enqueues' );
