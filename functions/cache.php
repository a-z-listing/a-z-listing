<?php

function a_z_listing_clear_cache( $post_id ) {
	$transient_queries = get_transient('A_Z_Queries');
	if ( ! $transient_queries ) {
		return;
	}

	$key = "post:$post_id";
	$update_transient = false;

	$copy_queries = $transient_queries;
	foreach( $transient_queries as $query => $items ) {
		foreach( $items as $item ) {
			if ( $item['item'] === $key ) {
				unset( $copy_queries[ $query ] );
				$update_transient = true;
				break;
			}
		}
	}

	if ( true === $update_transient ) {
		set_transient( 'A_Z_Queries', $transient_queries, 7 * 24 * 60 * 60 );
	}
}
add_action( 'save_post', 'a_z_listing_clear_cache', 10, 3 );
