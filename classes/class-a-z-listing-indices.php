<?php
/**
 * Default index parsing functions.
 *
 * @package a-z-listing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class A_Z_Indices
 */
class A_Z_Listing_Indices extends A_Z_Listing_Singleton {
	/**
	 * Bind the index parsing functions to their respective filters.
	 */
	final public function initialize() {
		add_filter( '_a-z-listing-extract-item-indices', array( $this, 'get_item_indices' ), 1, 3 );
	}

	/**
	 * Find and return the index letter for a post
	 *
	 * @since 2.0.0
	 * @param array       $indices Previously discovered indices.
	 * @param int|WP_Term $item ID of the item whose index letters we want to find.
	 * @param string      $type Type of listing - terms or posts.
	 * @return array The post's index letters (usually matching the first character of the post title)
	 */
	public static function get_item_indices( $indices, $item, $type ) {
		if ( 'terms' === $type ) {
			$title     = $item->name;
			$item_id   = $item->term_id;
			$permalink = get_term_link( $item );
		} else {
			$title     = get_the_title( $item );
			/** @noinspection PhpUndefinedFieldInspection */
			$item_id   = $item->ID;
			$permalink = get_the_permalink( $item );
		}

		$index = mb_substr( $title, 0, 1, 'UTF-8' );

		$indices[ $index ][] = array(
			'title' => $title,
			'item'  => ( 'terms' === $type ) ? "term:{$item_id}" : "post:{$item_id}",
			'link'  => $permalink,
		);

		if ( 'terms' === $type ) {
			/**
			 * Modify the indice(s) to group this term under
			 *
			 * @deprecated Use a_z_listing_item_indices
			 * @see a_z_listing_item_indices
			 */
			$indices = apply_filters_deprecated( 'a_z_listing_term_indices', array( $indices, $item ), '1.0.0', 'a_z_listing_item_indices' );
		} else {
			/**
			 * Modify the indice(s) to group this post under
			 *
			 * @deprecated Use a_z_listing_item_indices
			 * @see a_z_listing_item_indices
			 */
			$indices = apply_filters_deprecated( 'a_z_listing_post_indices', array( $indices, $item ), '1.5.0', 'a_z_listing_item_indices' );
		} // End if.

		/**
		 * Modify the indice(s) to group this item under
		 *
		 * @param array           $indices The current indices
		 * @param WP_Term|WP_Post $item The item
		 * @param string          $item_type The type of the item. Either 'posts' or 'terms'.
		 */
		$indices = apply_filters( 'a_z_listing_item_indices', $indices, $item, $type );

		/**
		 * Modify the indice(s) to group this item under
		 *
		 * @since 1.7.1
		 * @param array           $indices The current indices
		 * @param WP_Term|WP_Post $item The item
		 * @param string          $item_type The type of the item. Either 'posts' or 'terms'.
		 */
		$indices = apply_filters( 'a-z-listing-item-indices', $indices, $item, $type );

		if ( AZLISTINGLOG > 2 ) {
			do_action( 'log', 'Item indices', $indices );
		}

		return $indices;
	}
}
