<?php
/**
 * Contains the A-Z Index shortcode functionality.
 * @package  a-z-listing
 */

/**
 * Handle the a-z-listing shortcode.
 *
 * @since 1.0.0
 * @since 1.7.0 Add numbers attribute to append or prepend numerics to the listing.
 * @since 1.8.0 Fix numbers attribute when selecting to display terms. Add grouping to numbers via attribute. Add alphabet override via new attribute.
 * @param  array $attributes Provided by WordPress core. Contains the shortcode attributes.
 * @return string      The A-Z Listing HTML.
 */
function a_z_shortcode_handler( $attributes ) {
	$attributes = shortcode_atts(
		array(
			'column-count'       => 1,
			'minimum-per-column' => 10,
			'heading-level'      => 2,
			'alphabet'           => '',
			'display'            => 'posts',
			'grouping'           => '',
			'numbers'            => '',
			'post-type'          => 'page',
			'taxonomy'           => '',
			'terms'              => '',
		), $attributes, 'a-z-listing'
	);

	if ( ! empty( $attributes['alphabet'] ) ) {
		$override = $attributes['alphabet'];
		add_filter(
			'a-z-listing-alphabet', function( $alphabet ) use ( $override ) {
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

	if ( 1 < $grouping ) {
		add_filter(
			'a-z-listing-alphabet', function( $alphabet ) use ( $grouping ) {
				$headings = array();
				$letters  = mb_split( ',', $alphabet );

				$i = 0;
				$j = 0;

				$groups = array_reduce(
					$letters, function( $carry, $letter ) use ( $grouping, &$headings, &$i, &$j ) {
						if ( ! isset( $carry[ $j ] ) ) {
							$carry[ $j ] = $letter;
						} else {
							$carry[ $j ] = $carry[ $j ] . $letter;
						}
						$headings[ $j ][] = mb_substr( $letter, 0, 1 );

						if ( $i + 1 === $grouping ) {
							$i = 0;
							$j++;
						} else {
							$i++;
						}

						return $carry;
					}
				);

				$headings = array_reduce(
					$headings, function( $carry, $heading ) {
						$carry[ mb_substr( $heading[0], 0, 1 ) ] = $heading;
						return $carry;
					}
				);

				$heading_filter = function( $title ) use ( $headings ) {
					if ( isset( $headings[ $title ] ) && is_array( $headings[ $title ] ) ) {
						$first = array_shift( $headings[ $title ] );
						$last  = array_pop( $headings[ $title ] );
						return $first . '-' . $last;
					}

					return $title;
				};

				if ( has_filter( 'the-a-z-letter-title', $heading_filter ) ) {
					remove_filter( 'the-a-z-letter-title', $heading_filter );
				}
					add_filter( 'the-a-z-letter-title', $heading_filter, 5 );

					return join( ',', $groups );
			}, 2
		);
	}

	if ( ! empty( $attributes['numbers'] ) ) {
		add_a_z_numbers( $attributes['numbers'], $group_numbers );
		if ( $group_numbers ) {
			$numbers_titlefunc = function ( $title ) {
				if ( '0' === strval( $title ) ) {
					return '0-9';
				}
				return $title;
			};
			if ( has_filter( 'the-a-z-letter-title', $numbers_titlefunc ) ) {
				remove_filter( 'the-a-z-letter-title', $numbers_titlefunc );
			}
			add_filter( 'the-a-z-letter-title', $numbers_titlefunc, 5 );
		}
	}

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

	if ( ! empty( $attributes['terms'] ) ) {
		$taxonomy = '' !== $attributes['taxonomy'] ? $attributes['taxonomy'] : 'category';
		$terms    = explode( ',', $attributes['terms'] );
		$terms    = array_map( 'trim', $terms );
		$terms    = array_unique( $terms );

		$query = array_merge(
			$query, array(
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

	$a_z_query = new A_Z_Listing( $query );
	return $a_z_query->get_the_listing();
}
add_shortcode( 'a-z-listing', 'a_z_shortcode_handler' );
