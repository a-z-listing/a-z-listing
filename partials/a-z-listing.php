<?php
/**
 * Support functions for the A-Z Index page
 * @package  a-z-listing
 */

/**
 * Replies whether the query has any letters left
 * @param  array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 * @return bool                                       whether there are letters still to be iterated-over
 */
function have_a_z_letters( $query = null ) {
	return a_z_listing_cache( $query )->have_letters();
}
/**
 * @deprecated use have_a_z_items()
 */
function have_a_z_posts() {
	return have_a_z_items();
}
/**
 * Replies whether the query has any posts left for the current letter
 * @param  array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 * @return bool                                       whether there are still posts available
 */
function have_a_z_items( $query = null ) {
	return a_z_listing_cache( $query )->have_items();
}

/**
 * Proceed to the next letter
 * @param array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter( $query = null ) {
	a_z_listing_cache( $query )->the_letter();
}
/**
 * @deprecated use the_a_z_item()
 */
function the_a_z_post() {
	the_a_z_item();
}
/**
 * Proceed to the next post
 * @param array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_item( $query = null ) {
	a_z_listing_cache( $query )->the_item();
}

/**
 * @deprecated use get_the_a_z_letter_count
 */
function num_a_z_letters() {
	return get_the_a_z_letter_count();
}
/**
 * @deprecated use get_the_a_z_letter_count() or the_a_z_letter_count()
 */
function num_a_z_posts() {
	/** @noinspection PhpDeprecationInspection */
	return num_a_z_items();
}
/**
 * @deprecated use get_the_a_z_letter_count() or the_a_z_letter_count()
 */
function num_a_z_items() {
	return get_the_a_z_letter_count();
}
/**
 * Print the number of letters for the query
 * @param array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter_count( $query = null ) {
	a_z_listing_cache( $query )->the_letter_count();
}
/**
 * Get the number of letters for the query
 * @param  array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 * @return int                                        the number of letters
 */
function get_the_a_z_letter_count( $query = null ) {
	return a_z_listing_cache( $query )->get_the_letter_count();
}

/**
 * print the current letter ID
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter_id( $query = null ) {
	a_z_listing_cache( $query )->the_letter_id();
}
/**
 * Get the current letter ID
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return String                                     the current letter ID
 */
function get_the_a_z_letter_id( $query = null ) {
	return a_z_listing_cache( $query )->get_the_letter_id();
}
/**
 * Print the current letter title
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_letter_title( $query = null ) {
	a_z_listing_cache( $query )->the_letter_title();
}
/**
 * Get the current letter title
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return string                                     the letter title
 */
function get_the_a_z_letter_title( $query = null ) {
	return a_z_listing_cache( $query )->get_the_letter_title();
}
/**
 * Print the current item title
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_item_title( $query = null ) {
	a_z_listing_cache( $query )->the_title();
}
/**
 * Get the current item title
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return string                                     the post or taxonomy-term title
 */
function get_the_a_z_item_title( $query = null ) {
	return a_z_listing_cache( $query )->get_the_title();
}
/**
 * Print the current item permalink
 * @param array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_item_permalink( $query = null ) {
	a_z_listing_cache( $query )->the_permalink();
}
/**
 * Get the current item permalink
 * @param  array|string|WP_Query|A_Z_Listing  $query  either a valid WordPress query or an A_Z_Listing instance
 * @return string                                     the permalink
 */
function get_the_a_z_item_permalink( $query = null ) {
	return a_z_listing_cache( $query )->get_the_permalink();
}

/**
 * @deprecated use the_a_z_listing()
 * @param null|string|WP_Query|A_Z_Listing $query
 */
function the_az_listing( $query = null ) {
	the_a_z_listing( $query );
}
/**
 * Print the A-Z Index page content.
 * @param array|string|WP_Query|A_Z_Listing  $query      a valid WordPress query or an A_Z_Listing instance
 */
function the_a_z_listing( $query = null ) {
	echo get_the_a_z_listing( $query ); // WPCS: XSS OK.
}

/**
 * @deprecated use get_the_a_z_listing()
 * @param array|string|WP_Query|A_Z_Listing  $query      a valid WordPress query or an A_Z_Listing instance
 * @return string
 */
function get_the_az_listing( $query = null ) {
	return get_the_a_z_listing( $query );
}
/**
 * Return the index of posts ordered and segmented alphabetically.
 * @param  array|string|WP_Query|A_Z_Listing  $query      a valid WordPress query or an A_Z_Listing instance
 * @return string                                         The listing html content ready for echoing to the page.
 */
function get_the_a_z_listing( $query = null ) {
	return a_z_listing_cache( $query )->get_the_listing();
}

/**
 * @deprecated use the_a_z_letters()
 * @param array|string|WP_Query|A_Z_Listing  $query      a valid WordPress query or an A_Z_Listing instance
 * @param bool|string $target URL of the page to send the browser when a letter is clicked.
 * @param bool $styling
 */
function the_az_letters( $query = null, $target = false, $styling = false ) {
	the_a_z_letters( $query, $target, $styling );
}

/**
 * Prints the A-Z Letter list.
 * @param array|string|WP_Query|A_Z_Listing $query a valid WordPress query or an A_Z_Listing instance
 * @param bool|string $target URL of the page to send the browser when a letter is clicked.
 * @param bool $styling
 */
function the_a_z_letters( $query = null, $target = false, $styling = false ) {
	echo get_the_a_z_letters( $query, $target, $styling ); // WPCS: XSS OK.
}

/**
 * @deprecated use get_the_a_z_letters()
 * @param array|string|WP_Query|A_Z_Listing $query a valid WordPress query or an A_Z_Listing instance
 * @param bool|string $target URL of the page to send the browser when a letter is clicked.
 * @param bool $styling
 * @return string
 */
function get_the_az_letters( $query = null, $target = false, $styling = false ) {
	return get_the_a_z_letters( $query, $target, $styling );
}

/**
 * Returns the A-Z Letter list.
 * @param  array|string|WP_Query|A_Z_Listing $query a valid WordPress query or an A_Z_Listing instance
 * @param bool|string $target URL of the page to send the browser when a letter is clicked.
 * @param bool $styling
 * @return string HTML ready for echoing containing the list of A-Z letters with anchor links to the A-Z Index page.
 */
function get_the_a_z_letters( $query = null, $target = false, $styling = false ) {
	return a_z_listing_cache( $query )->get_the_letters( $target, $styling );
}
