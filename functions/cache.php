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
	$cache_keys = get_option( 'A_Z_Query_Caches', array() );
	if ( isset( $cache_keys ) || ! empty( $cache_keys ) && is_array( $cache_keys ) ) {
		foreach ( $cache_keys as $key ) {
			delete_transient( "A_Z_Query:$key" );
		}
	}
	update_option( 'A_Z_Query_Caches', array() );
}
add_action( 'save_post', 'a_z_listing_clear_cache', 10, 3 );
