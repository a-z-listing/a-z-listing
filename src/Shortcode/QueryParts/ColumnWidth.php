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
 * Column Width Query Part extension
 */
class ColumnWidth extends Extension {
	/**
	 * The attribute for this Query Part.
	 *
	 * @since 4.0.0
	 * @var string
	 */
	public $attribute_name = 'column-width';

	/**
	 * The column width.
	 *
	 * @var string
	 */
	public $column_width = '15em';

	/**
	 * Update the query with this extension's additional configuration.
	 *
	 * @param \A_Z_Listing\Query $query      The query.
	 * @param string             $display    The display/query type.
	 * @param string             $key        The name of the attribute.
	 * @param mixed              $value      The shortcode attribute value.
	 * @param array              $attributes The complete set of shortcode attributes.
	 * @return mixed The updated query.
	 */
	public function shortcode_query( $query, string $display, string $key, $value, array $attributes ) {
		$this->column_width = $value;
		$this->add_hook( 'filter', 'a_z_listing_styles', array( $this, 'return_styles' ), 10, 3 );
		return $query;
	}

	/**
	 * Return the stylesheet for this instance.
	 *
	 * @param string             $styles      The stylesheet.
	 * @param \A_Z_Listing\Query $a_z_listing The A-Z Listing Query object.
	 * @param string             $instance_id The instance ID.
	 * @return string
	 */
	public function return_styles( $styles, $a_z_listing, $instance_id ): string {
		return "$styles\n#a-z-listing-$instance_id { --a-z-listing-column-width: $this->column_width; }";
	}
}
