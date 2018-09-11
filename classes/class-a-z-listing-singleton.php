<?php
/**
 * A-Z Listing singleton
 *
 * @package a-z-listing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A_Z_Listing_Singleton
 */
abstract class A_Z_Listing_Singleton implements A_Z_Listing_Extension {
	/**
	 * Instances
	 *
	 * @var array
	 */
	private static $_instances = array();

	/**
	 * Singleton
	 *
	 * @return A_Z_Listing_Extension extension object.
	 */
	final public static function instance() {
		$class = get_called_class();
		if ( ! isset( self::$_instances[ $class ] ) ) {
			self::$_instances[ $class ] = new $class();
		}
		return self::$_instances[ $class ];
	}

	/**
	 * Activate
	 *
	 * @param string $plugin the plugin path.
	 *
	 * @return A_Z_Listing_Singleton
	 */
	public function activate( $plugin = '' ) {
		return $this; // no-op.
	}

	/**
	 * Initialize
	 */
	public function initialize() {
		return; // no-op.
	}
}
