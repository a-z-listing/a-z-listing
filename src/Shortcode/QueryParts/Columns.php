<?php
/**
 * Alphabet Query Part.
 *
 * @package a-z-listing
 */

declare(strict_types=1);

namespace A_Z_Listing\Shortcode\QueryParts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \A_Z_Listing\Shortcode\Extension;

/**
 * Instance ID Query Part extension
 */
class Columns extends Extension {
	/**
	 * The attribute for this Query Part.
	 *
	 * @since 4.0.0
	 * @var string
	 */
	public $attribute_name = 'columns';

	/**
	 * The instance_id.
	 *
	 * @var string
	 */
	public $columns = 3;

	/**
	 * Update the query with this extension's additional configuration.
	 *
	 * @param mixed  $query      The query.
	 * @param string $display    The display/query type.
	 * @param string $attribute  The name of the attribute.
	 * @param mixed  $value      The shortcode attribute value.
	 * @param array  $attributes The complete set of shortcode attributes.
	 * @return mixed The updated query.
	 */
	public function shortcode_query( $query, string $display, string $attribute, $value, array $attributes ) {
		$this->columns = $value;
		$this->add_hook( 'filter', 'a_z_listing_styles', array( $this, 'return_styles' ), 10, 3 );
		return $query;
	}

	/**
	 * Return the ID for this instance.
	 *
	 * @return string
	 */
	public function return_styles( $styles, $a_z_listing, $instance_id ): string {
		return "$styles\n#a-z-listing-$instance_id { --a-z-listing-column-count: $this->columns; }";
	}
}
