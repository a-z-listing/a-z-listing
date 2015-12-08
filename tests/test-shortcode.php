<?php
class AZ_Shortcode_Tests extends WP_UnitTestCase {
	function test_empty() {
		$listing = get_the_az_listing();
		$this->assertStringEqualsFile( 'tests/default-listing.txt', $listing );
	}

	function test_populated() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );

		$listing = get_the_az_listing( new WP_Query( array( 'post_type' => 'page' ) ) );
		$this->assertEquals( sprintf( file_get_contents( 'tests/populated-listing.txt' ), $p ), $listing );
	}
}
