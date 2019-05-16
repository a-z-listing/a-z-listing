<?php
/**
 * Health Check functionality
 *
 * @package a-z-listing
 */

/**
 * Add A-Z Listing Health Checks
 *
 * @param array $tests The health checks.
 * @return array The health checks.
 */
function a_z_listing_add_health_check( $tests ) {
	$tests['direct']['a_z_listing'] = array(
		'mbstring_module' => array(
			'label' => __( 'A to Z Listing plugin' ),
			'test'  => 'a_z_listing_mbstring_health_check',
		),
	);
	return $tests;
}
add_filter( 'site_status_tests', 'a_z_listing_add_health_check' );

/**
 * The mbstring health check
 *
 * @return array The health check results.
 */
function a_z_listing_mbstring_health_check() {
	$result = array(
		'label'       => __( 'PHP mbstring module is enabled' ),
		'status'      => 'good',
		'badge'       => array(
			'label' => __( 'mbstring' ),
			'color' => 'green',
		),
		'description' => sprintf(
			'<p>%s</p>',
			__( 'The mbstring PHP module improves support for non-latin languages.' )
		),
		'actions'     => '',
		'test'        => 'a_z_listing_mbstring_health_check',
	);

	if ( ! extension_loaded( 'mbstring' ) ) {
		$result['status']         = 'recommended';
		$result['label']          = __( 'PHP mbstring module is not enabled' );
		$result['badge']['color'] = 'orange';
		$result['description']    = sprintf(
			'<p>%s</p>',
			__( 'The mbstring PHP module is not enabled on your server. This module improves support for non-latin languages.' )
		);
		$result['actions']        = __( 'Contact your web host to request that the mbstring PHP module is enabled for your site.' );
	}

	return $result;
}
