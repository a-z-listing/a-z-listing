<?php

function a_z_listing_enqueue_styles() {
	wp_enqueue_style( 'a-z-listing', dirname( __FILE__ ) . '/../css/a-z-listing-default.css' );
}

function a_z_listing_add_styling() {
	if ( true === get_option( 'a-z-listing-add-styling' ) ) {
		add_action( 'wp-enqueue-scripts', 'a_z_listing_enqueue_styles' );
	}
}
add_action( 'init', 'a_z_listing_add_styling' );
