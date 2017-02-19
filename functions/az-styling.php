<?php

function a_z_listing_enqueue_styles( $force = false ) {
	global $post;
	if ( ! ( $force || is_active_widget( false, false, 'bh_az_widget', true ) || ( is_single() && has_shortcode( $post->post_content, 'a-z-listing' ) ) ) ) {
		return;
	}
	wp_enqueue_style( 'a-z-listing' );
}

function a_z_listing_force_enqueue_styles() {
	a_z_listing_enqueue_styles( true );
}

function a_z_listing_force_enable_styles() {
	if ( has_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' ) ) {
		remove_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' );
	}
	add_action( 'wp_enqueue_scripts', 'a_z_listing_force_enqueue_styles' );
}

function a_z_listing_add_styling() {
	wp_register_style( 'a-z-listing', plugins_url( 'css/a-z-listing-default.css', dirname( __FILE__ ) ) );
	$add_styles = apply_filters( 'a_z_listing_add_styling', get_option( 'a-z-listing-add-styling', true ) );
	if ( AZLISTINGLOG ) {
		do_action( 'log', 'A-Z Listing: Add Styles', $add_styles );
	}
	if ( true === $add_styles && ! has_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' ) ) {
		add_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' );
	}
}
add_action( 'init', 'a_z_listing_add_styling' );
