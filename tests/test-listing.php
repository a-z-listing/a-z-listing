<?php
class AZ_Listing_Tests extends WP_UnitTestCase {
	function test_empty() {
		$letters = get_the_az_letters();
		$this->assertStringEqualsFile( 'tests/default-letters.txt', $letters );

		$listing = get_the_az_listing();
		$this->assertStringEqualsFile( 'tests/default-listing.txt', $listing );
	}
	function test_populated() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );

		$q = new WP_Query( array( 'post_type' => 'page' ) );

		$letters = get_the_az_letters( $q );
		$this->assertStringEqualsFile( 'tests/populated-letters.txt', $letters );
		$letters = get_the_az_letters( $q, '/test-path' );
		$this->assertStringEqualsFile( 'tests/populated-letters-linked.txt', $letters );

		$listing = get_the_az_listing( $q );
		$expected = file_get_contents( 'tests/populated-listing.txt' );
		$this->assertEquals( sprintf( $expected, $p ), $listing );
	}
}
