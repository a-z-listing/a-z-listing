<?php

/**
 * Enqueue A-Z default styling on pages where either the widget or the shortcode are active.
 *
 * @since 0.7
 * @param bool $force Set this to true if you want the styling to always be enqueued.
 */
function a_z_listing_enqueue_styles( $force = false ) {
	global $post;
	if ( ! ( $force || is_active_widget( false, false, 'bh_az_widget', true ) || ( is_singular() && has_shortcode( $post->post_content, 'a-z-listing' ) ) ) ) {
		return;
	}
	wp_enqueue_style( 'a-z-listing' );
}

/**
 * Forcibly enqueue styling. This is a helper function which can be hooked in-place of the default hook added in `a_z_listing_add_styling`.
 *
 * @since 1.3.0
 */
function a_z_listing_force_enqueue_styles() {
	a_z_listing_enqueue_styles( true );
}

/**
 * Replace the default styling enqueue function with `a_z_listing_force_enqueue_styles` to always add the styling to pages.
 *
 * @since 1.3.0
 */
function a_z_listing_force_enable_styles() {
	if ( has_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' ) ) {
		remove_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' );
	}
	add_action( 'wp_enqueue_scripts', 'a_z_listing_force_enqueue_styles' );
}

/**
 * Register default A-Z stylesheet and add our enqueue function to the `wp_enqueue_scripts` action.
 *
 * @since 0.7
 */
function a_z_listing_add_styling() {
	wp_register_style( 'a-z-listing', plugins_url( 'css/a-z-listing-default.css', dirname( __FILE__ ) ), array( 'dashicons' ) );

	$add_styles = get_option( 'a-z-listing-add-styling', true );
	/**
	 * Determine whether to add default listing styling
	 *
	 * @param bool True to add default styling, False to disable
	 */
	$add_styles = apply_filters( 'a_z_listing_add_styling', $add_styles );
	/**
	 * Determine whether to add default listing styling
	 *
	 * @param bool True to add default styling, False to disable
	 * @since 1.7.1
	 */
	$add_styles = apply_filters( 'a-z-listing-add-styling', $add_styles );

	if ( AZLISTINGLOG ) {
		do_action( 'log', 'A-Z Listing: Add Styles', $add_styles );
	}
	if ( true === $add_styles && ! has_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' ) ) {
		add_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' );
	}
}
add_action( 'init', 'a_z_listing_add_styling' );
