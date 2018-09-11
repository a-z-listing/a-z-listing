<?php
/**
 * Contains the A-Z Index shortcode functionality
 *
 * @package a-z-listing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle the a-z-listing shortcode
 *
 * @since 1.0.0
 * @since 1.7.0 Add numbers attribute to append or prepend numerics to the listing.
 * @since 1.8.0 Fix numbers attribute when selecting to display terms. Add grouping to numbers via attribute. Add alphabet override via new attribute.
 * @param  array $attributes Provided by WordPress core. Contains the shortcode attributes.
 * @return string The A-Z Listing HTML.
 */
function a_z_shortcode_handler( $attributes ) {
	$attributes = shortcode_atts(
		array(
			'alphabet'         => '',
			'display'          => 'posts',
			'group-numbers'    => false,
			'grouping'         => '',
			'numbers'          => 'hide',
			'post-type'        => 'page',
			'taxonomy'         => '',
			'terms'            => '',
			'exclude-terms'    => '',
			'hide-empty-terms' => false,
			'parent-term'      => '',
			'return'           => 'listing',
			'target'           => '',
		),
		$attributes,
		'a-z-listing'
	);

	if ( ! empty( $attributes['alphabet'] ) ) {
		$override = $attributes['alphabet'];
		add_filter(
			'a-z-listing-alphabet',
			function( $alphabet ) use ( $override ) {
				return $override;
			}
		);
	}

	$grouping      = $attributes['grouping'];
	$group_numbers = false;
	if ( 'numbers' === $grouping ) {
		$group_numbers = true;
		$grouping      = 0;
	} else {
		$grouping = intval( $grouping );
		if ( 1 < $grouping ) {
			$group_numbers = true;
		}
	}

	if ( true === $attributes['group-numbers'] ) {
		$group_numbers = true;
	}

	$grouping_obj = new A_Z_Listing_Grouping( $grouping );

	$numbers_obj = null;
	if ( ! empty( $attributes['numbers'] ) && 'hide' !== $attributes['numbers'] ) {
		$numbers_obj = add_a_z_numbers( $attributes['numbers'], $group_numbers );
	}

	if ( 'terms' === $attributes['display'] && ! empty( $attributes['taxonomy'] ) ) {
		$taxonomy = '' !== $attributes['taxonomy'] ? $attributes['taxonomy'] : 'category';
		$query    = array(
			'taxonomy' => $taxonomy,
		);

		$terms_string  = '';
		$terms_process = 'include';

		if ( ! empty( $attributes['terms'] ) ) {
			$terms_string = $attributes['terms'];
		} elseif ( ! empty( $attributes['exclude-terms'] ) ) {
			$terms_string  = $attributes['exclude-terms'];
			$terms_process = 'exclude';
		}

		if ( ! empty( $terms_string ) ) {
			$terms = mb_split( ',', $terms_string );
			$terms = array_map( 'trim', $terms );
			$terms = array_unique( $terms );

			$query = wp_parse_args(
				$query,
				array(
					$terms_process => $terms,
				)
			);
		}

		if ( ! empty( $attributes['parent-term'] ) ) {
			$query = wp_parse_args(
				$query,
				array(
					'child_of' => $attributes['parent-term'],
				)
			);
		}

		if ( ! empty( $attributes['hide-empty-terms'] ) ) {
			$hide_empty = false;
			if ( 'true' === $attributes['hide-empty-terms'] || '1' === $attributes['hide-empty-terms'] ) {
				$hide_empty = true;
			}
			$query = wp_parse_args(
				$query,
				array(
					'hide_empty' => $hide_empty,
				)
			);
		}

		$a_z_query = new A_Z_Listing( $query, 'terms' );
	} else {
		$post_types = mb_split( ',', $attributes['post-type'] );
		$post_types = array_map( 'trim', $post_types );
		$post_types = array_unique( $post_types );

		$query = array(
			'post_type' => $post_types,
		);

		if ( ! empty( $attributes['terms'] ) ) {
			$taxonomy = '' !== $attributes['taxonomy'] ? $attributes['taxonomy'] : 'category';
			$terms    = mb_split( ',', $attributes['terms'] );
			$terms    = array_map( 'trim', $terms );
			$terms    = array_unique( $terms );

			$query = wp_parse_args(
				$query,
				array(
					'tax_query' => array(
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $terms,
						),
					),
				)
			);
		}

		$a_z_query = new A_Z_Listing( $query, 'posts' );
	}

	$target = '';
	if ( ! empty( $attributes['target'] ) ) {
		if ( intval( $attributes['target'] ) > 0 ) {
			$target = get_permalink( $attributes['target'] );
		} else {
			$target = $attributes['target'];
		}
	}

	if ( 'letters' === $attributes['return'] ) {
		$ret = '<div class="az-letters">' . $a_z_query->get_the_letters( $target ) . '</div>';
	} else {
		$ret = $a_z_query->get_the_listing();
	}

	$grouping_obj->teardown();
	if ( null != $numbers_obj ) {
		$numbers_obj->teardown();
	}

	return $ret;
}
add_shortcode( 'a-z-listing', 'a_z_shortcode_handler' );
