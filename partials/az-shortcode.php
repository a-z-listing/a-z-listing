<?php
/**
 * Contains the A-Z Index shortcode functionality.
 * @package  a-z-listing
 */

/**
 * Handle the a-z-listing shortcode.
 * @param  array $atts Provided by WordPress core. Contains the shortcode attributes.
 * @return string      The A-Z Listing HTML.
 */
function a_z_shortcode_handler( $atts ) {
	$atts = shortcode_atts( array(
		'column-count' => 1,
		'minimum-per-column' => 10,
		'heading-level' => 2,
		'post-type' => 'page',
	), $atts, 'a-z-listing' );

	$query = array( 'post_type' => $atts['post-type'] );
	$a_z_query = new A_Z_Listing( $query );
	return $a_z_query->get_the_listing();
}
add_shortcode( 'a-z-listing', 'a_z_shortcode_handler' );
