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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'AZLISTINGLOG' ) ) {
	define( 'AZLISTINGLOG', false );
}

add_action( 'plugins_loaded', function() {
	require "functions/i18n.php";
	require "functions/helpers.php";
	require "functions/styles.php";
	require "functions/scripts.php";
	require "functions/shortcode.php";

	require "interfaces/interface-a-z-listing-extension.php";

	require "classes/class-a-z-listing.php";
	require "classes/class-a-z-listing-singleton.php";
	require "classes/class-a-z-listing-grouping.php";
	require "classes/class-a-z-listing-numbers.php";
	require "classes/class-a-z-listing-indices.php";
	require "widgets/class-a-z-listing-widget.php";

	add_action( 'init', function() {
		A_Z_Listing_Indices::instance()->activate( __FILE__ )->initialize();
	}, 5 );
}, 5 );
