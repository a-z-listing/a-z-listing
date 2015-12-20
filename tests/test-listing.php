<?php
class AZ_Listing_Tests extends WP_UnitTestCase {
	function test_empty_letters() {
		$letters = get_the_az_letters();
		$this->assertStringEqualsFile( 'tests/default-letters.txt', $letters );
	}
	function test_empty_listing() {
		$listing = get_the_az_listing();
		$this->assertStringEqualsFile( 'tests/default-listing.txt', $listing );
	}

	function test_populated_letters() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );
		$q = new WP_Query( array( 'post_type' => 'page' ) );
		$letters = get_the_az_letters( $q );
		$this->assertStringEqualsFile( 'tests/populated-letters.txt', $letters );
	}

	function test_populated_letters_linked() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );
		$q = new WP_Query( array( 'post_type' => 'page' ) );
		$letters = get_the_az_letters( $q, '/test-path' );
		$this->assertStringEqualsFile( 'tests/populated-letters-linked.txt', $letters );
	}

	function test_populated_listing() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );
		$q = new WP_Query( array( 'post_type' => 'page' ) );
		$listing = get_the_az_listing( $q );
		$expected = sprintf( file_get_contents( 'tests/populated-listing.txt' ), $p );
		$this->assertEquals( $expected, $listing );
	}
}
