<?php
/**
 * Extension support
 *
 * @package a-z-listing
 */

/**
 * A-Z Listing Extension Interface
 */
interface A_Z_Listing_Extension {
	/**
	 * Singleton
	 */
	public static function instance();

	/**
	 * Activate
	 *
	 * @param string $plugin path of plugin file.
	 * @return A_Z_Listing_Extension the activated extension.
	 */
	public function activate( $plugin );

	/**
	 * Initialize
	 */
	public function initialize();
}
