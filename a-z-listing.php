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

$dir = __DIR__;
require "$dir/functions/i18n.php";
require "$dir/functions/helpers.php";
require "$dir/functions/styling.php";
require "$dir/functions/shortcode.php";
require "$dir/classes/class-a-z-listing.php";
require "$dir/classes/class-a-z-grouping.php";
require "$dir/classes/class-a-z-numbers.php";

/**
 * Register the A-Z Listing Widget
 *
 * @since 2.0.0
 */
function a_z_listing_register_widget() {
	$dir = __DIR__;
	require "$dir/widgets/class-a-z-widget.php";
	register_widget( 'A_Z_Widget' );
}
add_action( 'widgets_init', 'a_z_listing_register_widget' );
