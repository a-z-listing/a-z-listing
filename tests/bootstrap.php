<?php

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/a-z-listing.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require_once $_tests_dir . '/includes/bootstrap.php';

require 'assert-html.php';
