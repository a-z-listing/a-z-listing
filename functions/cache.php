<?php
/**
 * A-Z Listing caching mechanism
 *
 * @package  a-z-listing
 */

/**
 * Clear the cache of A-Z Listing queries.
 *
 * @since 2.0.0
 */
function a_z_listing_clear_cache() {
	$transient_queries = get_transient( 'A_Z_Queries' );
	if ( ! $transient_queries ) {
		return;
	}

	delete_transient( 'A_Z_Queries' );
}
add_action( 'save_post', 'a_z_listing_clear_cache', 10, 3 );
