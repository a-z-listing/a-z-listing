<?php
/**
 * Contains the A-Z Index shortcode functionality.
 * @package  a-z-listing
 */

/**
 * Handle the a-z-listing shortcode.
 *
 * @since 1.0.0
 * @param  array $attributes Provided by WordPress core. Contains the shortcode attributes.
 * @return string      The A-Z Listing HTML.
 */
function a_z_shortcode_handler( $attributes ) {
	$attributes = shortcode_atts(
		array(
			'column-count' => 1,
			'minimum-per-column' => 10,
			'heading-level' => 2,
			'display' => 'posts',
			'post-type' => 'page',
			'taxonomy' => '',
			'terms' => '',
		), $attributes, 'a-z-listing'
	);

	if ( ! empty( $attributes['taxonomy'] ) && 'terms' === $attributes['display'] ) {
		$a_z_query = new A_Z_Listing( $attributes['taxonomy'] );
		return $a_z_query->get_the_listing();
	}

	$post_types = explode( ',', $attributes['post-type'] );
	$post_types = array_map( 'trim', $post_types );
	$post_types = array_unique( $post_types );

	$query = array(
		'post_type' => $post_types,
	);

	if ( '' !== $attributes['terms'] ) {
		$taxonomy = '' !== $attributes['taxonomy'] ? $attributes['taxonomy'] : 'category';
		$terms = explode( ',', $attributes['terms'] );
		$terms = array_map( 'trim', $terms );
		$terms = array_unique( $terms );

		$query = array_merge( $query, array(
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $terms,
				)
			)
		) );
	}

	$a_z_query = new A_Z_Listing( $query );
	return $a_z_query->get_the_listing();
}
add_shortcode( 'a-z-listing', 'a_z_shortcode_handler' );