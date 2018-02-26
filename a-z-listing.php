<?php
/**
 * Plugin Name:     A-Z Listing
 * Plugin URI:      https://a-z-listing.com/
 * Description:     Display an A to Z listing of posts
 * Author:          Daniel Llewellyn
 * Author URI:      https://bowlhat.net/
 * Text Domain:     a-z-listing
 * Domain Path:     /languages
 * Version:         2.0.0
 *
 * @package         A_Z_Listing
 */

if ( ! defined( 'AZLISTINGLOG' ) ) {
	define( 'AZLISTINGLOG', false );
}

require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'functions', 'i18n.php' ) ) );
require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'functions', 'helpers.php' ) ) );
require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'functions', 'styling.php' ) ) );
require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'functions', 'shortcode.php' ) ) );
require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'functions', 'cache.php' ) ) );
require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'classes', 'class-a-z-listing.php' ) ) );
function a_z_listing_register_widget() {
	require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'widgets', 'class-a-z-widget.php' ) ) );
	register_widget( 'A_Z_Widget' );
}
add_action( 'widgets_init', 'a_z_listing_register_widget' );
function a_z_listing_register_block_import() {
	if ( function_exists( 'register_block_type' ) ) {
		require( join( DIRECTORY_SEPARATOR, array( __DIR__, 'blocks', 'a-z-listing.php' ) ) );
	}
}
add_action( 'plugins_loaded', 'a_z_listing_register_block_import' );
