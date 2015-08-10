<?php

add_shortcode('a-z-listing', 'az_shortcode_handler');
function az_shortcode_handler( $atts ) {
	$args = shortcode_atts( array(
		'column-count' => 1,
		'minimum-per-column' => 10,
		'heading-level' => 2,
	), $atts );
	
	return get_the_az_listing(null, $args['column-count'], $args['minimum-per-column'], $args['heading-level']);
}
