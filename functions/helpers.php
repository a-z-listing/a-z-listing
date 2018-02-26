<?php
/**
 * Support functions for the A-Z Index page
 * @package  a-z-listing
 */

/**
 * Replies whether the query has any letters left
 *
 * @since 0.7
 * @param  array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 * @return bool                                       whether there are letters still to be iterated-over
 */
function have_a_z_letters( $query = null ) {
	return a_z_listing_cache( $query )->have_letters();
}

/**
 * @since 0.7
 * @see have_a_z_items()
 * @deprecated use have_a_z_items()
 */
function have_a_z_posts() {
	_deprecated_function( __FUNCTION__, '0.8.0', 'have_a_z_items' );
	return have_a_z_items();
}

/**
 * Replies whether the query has any posts left for the current letter
 *
 * @since 0.8.0
 * @param  array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 * @return bool                                       whether there are still posts available
 */
function have_a_z_items( $query = null ) {
	return a_z_listing_cache( $query )->have_items();
}

/**
 * Proceed to the next letter
 *
 * @since 0.7
 * @param array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter( $query = null ) {
	a_z_listing_cache( $query )->the_letter();
}

/**
 * @since 0.7
 * @see the_a_z_item()
 * @deprecated use the_a_z_item()
 */
function the_a_z_post() {
	_deprecated_function( __FUNCTION__, '0.8.0', 'the_a_z_item' );
	the_a_z_item();
}

/**
 * Proceed to the next post
 *
 * @since 0.8.0
 * @param array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_item( $query = null ) {
	a_z_listing_cache( $query )->the_item();
}

/**
 * @since 0.7
 * @see get_the_a_z_letter_count()
 * @deprecated use get_the_a_z_letter_count()
 */
function num_a_z_letters() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'get_the_a_z_letter_count' );
	return get_the_a_z_letter_count();
}

/**
 * @since 0.7
 * @see get_the_a_z_letter_count()
 * @deprecated use get_the_a_z_letter_count()
 */
function num_a_z_posts() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'get_the_a_z_letter_count' );
	return get_the_a_z_letter_count();
}

/**
 * @since 0.7
 * @see get_the_a_z_letter_count()
 * @deprecated use get_the_a_z_letter_count()
 */
function num_a_z_items() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'get_the_a_z_letter_count' );
	return get_the_a_z_letter_count();
}

/**
 * Print the number of letters for the query
 *
 * @since 1.0.0
 * @param array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter_count( $query = null ) {
	a_z_listing_cache( $query )->the_letter_count();
}
/**
 * Get the number of letters for the query
 *
 * @since 1.0.0
 * @param  array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 * @return int                                        the number of letters
 */
function get_the_a_z_letter_count( $query = null ) {
	return a_z_listing_cache( $query )->get_the_letter_count();
}

/**
 * print the current letter ID
 *
 * @since 0.7
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter_id( $query = null ) {
	a_z_listing_cache( $query )->the_letter_id();
}

/**
 * Get the current letter ID
 *
 * @since 0.7
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return String                                     the current letter ID
 */
function get_the_a_z_letter_id( $query = null ) {
	return a_z_listing_cache( $query )->get_the_letter_id();
}

/**
 * Print the current letter title
 *
 * @since 0.7
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter_title( $query = null ) {
	a_z_listing_cache( $query )->the_letter_title();
}

/**
 * Get the current letter title
 *
 * @since 0.7
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return string                                     the letter title
 */
function get_the_a_z_letter_title( $query = null ) {
	return a_z_listing_cache( $query )->get_the_letter_title();
}

/**
 * Print the current item title
 *
 * @since 0.8.0
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_item_title( $query = null ) {
	a_z_listing_cache( $query )->the_title();
}

/**
 * Get the current item title
 *
 * @since 0.8.0
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return string                                     the post or taxonomy-term title
 */
function get_the_a_z_item_title( $query = null ) {
	return a_z_listing_cache( $query )->get_the_title();
}

/**
 * Print the current item permalink
 *
 * @since 0.8.0
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_item_permalink( $query = null ) {
	a_z_listing_cache( $query )->the_permalink();
}

/**
 * Get the current item permalink
 *
 * @since 0.8.0
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return string                                     the permalink
 */
function get_the_a_z_item_permalink( $query = null ) {
	return a_z_listing_cache( $query )->get_the_permalink();
}

/**
 * @since 0.1
 * @see the_a_z_listing()
 * @deprecated use the_a_z_listing()
 */
function the_az_listing( $query = null ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'the_a_z_listing' );
	the_a_z_listing( $query );
}

/**
 * Print the A-Z Index page content.
 *
 * @since 0.8.0
 * @param array|string|WP_Query|A_Z_Listing  $query      a valid WordPress query or an A_Z_Listing instance
 * @param bool                               $use_cache  use the plugin's in-built query cache
 */
function the_a_z_listing( $query = null, $use_cache = true ) {
	echo get_the_a_z_listing( $query, $use_cache ); // WPCS: XSS OK.
}

/**
 * @since 0.1
 * @see get_the_a_z_listing()
 * @deprecated use get_the_a_z_listing()
 */
function get_the_az_listing( $query = null ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'get_the_a_z_listing' );
	return get_the_a_z_listing( $query );
}

/**
 * Return the index of posts ordered and segmented alphabetically.
 *
 * @since 0.8.0
 * @param  array|string|WP_Query|A_Z_Listing  $query      a valid WordPress query or an A_Z_Listing instance
 * @param  bool                               $use_cache  use the plugin's in-built query cache
 * @return string                                         The listing html content ready for echoing to the page.
 */
function get_the_a_z_listing( $query = null, $use_cache = true ) {
	return a_z_listing_cache( $query, $use_cache )->get_the_listing();
}

/**
 * @since 0.7
 * @see the_a_z_letters()
 * @deprecated use the_a_z_letters()
 */
function the_az_letters( $query = null, $target = false, $styling = false ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'the_a_z_letters' );
	the_a_z_letters( $query, $target, $styling );
}

/**
 * Prints the A-Z Letter list.
 *
 * @since 0.8.0
 * @param array|string|WP_Query|A_Z_Listing $query a valid WordPress query or an A_Z_Listing instance
 * @param bool|string $target URL of the page to send the browser when a letter is clicked.
 * @param bool $styling
 */
function the_a_z_letters( $query = null, $target = false, $styling = false ) {
	echo get_the_a_z_letters( $query, $target, $styling ); // WPCS: XSS OK.
}

/**
 * @since 0.7
 * @see get_the_a_z_letters()
 * @deprecated use get_the_a_z_letters()
 */
function get_the_az_letters( $query = null, $target = false, $styling = false ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'get_the_a_z_letters' );
	return get_the_a_z_letters( $query, $target, $styling );
}

/**
 * Returns the A-Z Letter list.
 *
 * @since 0.8.0
 * @param  array|string|WP_Query|A_Z_Listing $query a valid WordPress query or an A_Z_Listing instance
 * @param bool|string $target URL of the page to send the browser when a letter is clicked.
 * @param bool $styling
 * @return string HTML ready for echoing containing the list of A-Z letters with anchor links to the A-Z Index page.
 */
function get_the_a_z_letters( $query = null, $target = false, $styling = false ) {
	return a_z_listing_cache( $query )->get_the_letters( $target, $styling );
}

/**
 * Returns a function for use in the `a_z_listing_alphabet` filter.
 *
 * @since 1.7.0
 * @since 1.8.0 Add $group parameter and functionality to group numbers into a single collection.
 * @param string $position set to before to place the numbers first. Any other value will place them last.
 * @param bool   $group    group the numbers in a single collection rather than individually
 */
function add_a_z_numbers( $position = 'after', $group = false ) {
	add_filter(
		'a-z-listing-alphabet', function( $alphabet ) use ( $position, $group ) {
			$numbers = '0,1,2,3,4,5,6,7,8,9';
			if ( true === $group ) {
				$numbers = '0123456789';
				add_filter(
					'the-a-z-letter-title', function( $letter ) {
						if ( '0' === $letter ) {
							return '0-9';
						}
						return $letter;
					}
				);
			}
			if ( 'before' === $position ) {
				return join( ',', array( $numbers, $alphabet ) );
			}
			return join( ',', array( $alphabet, $numbers ) );
		}
	);
}
