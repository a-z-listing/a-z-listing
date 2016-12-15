<?php

function a_z_listing_enqueue_styles() {
	$url = plugins_url( 'css/a-z-listing-default.css', dirname( __FILE__ ) );
	wp_enqueue_style( 'a-z-listing', $url );
}

function a_z_listing_add_styling() {
	$add_styles = apply_filters( 'a_z_listing_add_styling', get_option( 'a-z-listing-add-styling', true ) );
	if ( AZLISTINGLOG ) {
		do_action( 'log', 'A-Z Listing: Add Styles', $add_styles );
	}
	if ( true === $add_styles ) {
		add_action( 'wp_enqueue_scripts', 'a_z_listing_enqueue_styles' );
	}
}
add_action( 'init', 'a_z_listing_add_styling' );
