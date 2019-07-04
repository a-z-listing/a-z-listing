<?php
/**
 * A-Z Listing singleton
 *
 * @package a-z-listing
 */

namespace A_Z_Listing;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A_Z_Listing_Singleton
 */
abstract class Singleton {
	/**
	 * Instances
	 *
	 * @var array
	 */
	private static $_instances = array();

	/**
	 * Singleton
	 *
	 * @return Extension extension object.
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
	 * @param string $file   the plugin file.
	 * @param string $plugin the plugin details.
	 *
	 * @return Singleton
	 */
	public function activate( $file = '', $plugin = '' ) {
		return $this;
	}

	/**
	 * Initialize
	 */
	abstract public function initialize();
}
