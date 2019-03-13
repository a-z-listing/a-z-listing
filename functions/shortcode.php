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
 * @since 2.0.0 Add parent-term and hide-empty parameters.
 * @param  array $attributes Provided by WordPress core. Contains the shortcode attributes.
 * @return string The A-Z Listing HTML.
 */
function a_z_shortcode_handler( $attributes ) {
	$attributes = shortcode_atts(
		array(
			'alphabet'         => '',
			'display'          => 'posts',
			'exclude-posts'    => '',
			'exclude-terms'    => '',
			'get-all-children' => 'false',
			'group-numbers'    => '',
			'grouping'         => '',
			'hide-empty-terms' => 'false',
			'numbers'          => 'hide',
			'parent-post'      => '',
			'parent-term'      => '',
			'post-type'        => 'page',
			'return'           => 'listing',
			'target'           => '',
			'taxonomy'         => '',
			'terms'            => '',
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
	if ( ! empty( $attributes['group-numbers'] ) && a_z_listing_is_truthy( $attributes['group-numbers'] ) ) {
		$group_numbers = true;
	}

	if ( 'numbers' === $grouping ) {
		$group_numbers = true;
		$grouping      = 0;
	} else {
		$grouping = intval( $grouping );
		if ( 1 < $grouping && empty( $attributes['group-numbers'] ) ) {
			$group_numbers = true;
		}
	}

	$grouping_obj = new A_Z_Listing_Grouping( $grouping );
	$numbers_obj  = new A_Z_Listing_Numbers( $attributes['numbers'], $group_numbers );

	if ( 'terms' === $attributes['display'] && ! empty( $attributes['taxonomy'] ) ) {
		$taxonomy = '' !== $attributes['taxonomy'] ? $attributes['taxonomy'] : 'category';
		$query    = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => isset( $attributes['hide_empty'] ) && a_z_listing_is_truthy( $attributes['hide-empty'] ),
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
			$terms = explode( ',', $terms_string );
			$terms = array_map( 'trim', $terms );
			$terms = array_map( 'intval', $terms );
			$terms = array_filter(
				$terms,
				function( $value ) {
					return 0 < $value;
				}
			);
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
			$hide_empty = a_z_listing_is_truthy( $attributes['hide-empty-terms'] );

			$query = wp_parse_args(
				$query,
				array(
					'hide_empty' => $hide_empty,
				)
			);
		}

		$a_z_query = new A_Z_Listing( $query, 'terms' );
	} else {
		$post_type = explode( ',', $attributes['post-type'] );
		$post_type = array_map( 'trim', $post_type );

		$query = array(
			'post_type' => $post_type,
		);

		if ( ! empty( $attributes['exclude-posts'] ) ) {
			$exclude_posts = explode( ',', $attributes['exclude-posts'] );
			$exclude_posts = array_map( 'trim', $exclude_posts );
			$exclude_posts = array_map( 'intval', $exclude_posts );
			$exclude_posts = array_filter(
				$exclude_posts,
				function( $value ) {
					return 0 < $value;
				}
			);
			$exclude_posts = array_unique( $exclude_posts );

			if ( ! empty( $exclude_posts ) ) {
				$query = wp_parse_args( $query, array( 'post__not_in' => $exclude_posts ) );
			}
		}

		if ( ! empty( $attributes['parent-post'] ) ) {
			if ( a_z_listing_is_truthy( $attributes['get-all-children'] ) ) {
				$child_query = array( 'child_of' => $attributes['parent-post'] );
			} else {
				$child_query = array( 'post_parent' => $attributes['parent-post'] );
			}
			$query = wp_parse_args( $query, $child_query );
		}

		$taxonomy  = '' !== $attributes['taxonomy'] ? $attributes['taxonomy'] : 'category';
		$tax_query = array();
		if ( ! empty( $attributes['terms'] ) ) {
			$terms = explode( ',', $attributes['terms'] );
			$terms = array_map( 'trim', $terms );
			$terms = array_filter(
				$terms,
				function( $value ) {
					return ! empty( $value );
				}
			);
			$terms = array_unique( $terms );

			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $terms,
				'operator' => 'IN',
			);
		}
		if ( ! empty( $attributes['exclude-terms'] ) ) {
			$ex_terms = explode( ',', $attributes['exclude-termsterms'] );
			$ex_terms = array_map( 'trim', $ex_terms );
			$ex_terms = array_filter(
				$ex_terms,
				function( $value ) {
					return ! empty( $value );
				}
			);
			$ex_terms = array_unique( $ex_terms );

			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $ex_terms,
				'operator' => 'NOT IN',
			);
		}

		if ( ! empty( $tax_query ) ) {
			$query['tax_query'] = $tax_query;
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
	$numbers_obj->teardown();

	return $ret;
}
add_shortcode( 'a-z-listing', 'a_z_shortcode_handler' );
