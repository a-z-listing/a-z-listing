<?php
/**
 * Plugin Name:     A-Z Listing
 * Plugin URI:      https://a-z-listing.com/
 * Description:     Display an A to Z listing of posts
 * Author:          Daniel Llewellyn
 * Author URI:      https://bowlhat.net/
 * Text Domain:     a-z-listing
 * Domain Path:     /languages
 * Version:         3.0.2
 *
 * @package         A_Z_Listing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'AZLISTINGLOG' ) ) {
	define( 'AZLISTINGLOG', false );
}

require 'vendor/autoload.php';

/**
 * Initialize the plugin.
 */
function a_z_listing_init() {
	\A_Z_Listing\Shortcode::instance()->activate( __FILE__, [] )->initialize();
	\A_Z_Listing\Indices::instance()->activate( __FILE__, [] )->initialize();
}

/**
 * Load all the plugin code.
 */
function a_z_listing_plugins_loaded() {
	require 'functions/i18n.php';
	require 'functions/health-check.php';
	require 'functions/helpers.php';
	require 'functions/styles.php';
	require 'functions/scripts.php';
	require 'functions/enqueues.php';

	require 'widgets/class-a-z-listing-widget.php';

	add_action( 'init', 'a_z_listing_init', 5 );
}
add_action( 'plugins_loaded', 'a_z_listing_plugins_loaded', 5 );
