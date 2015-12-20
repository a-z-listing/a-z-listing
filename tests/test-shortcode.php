<?php
class AZ_Shortcode_Tests extends WP_UnitTestCase {
	function test_empty() {
		$this->assertStringEqualsFile( 'tests/default-listing.txt', do_shortcode( '[a-z-listing]' ) );
	}

	function test_populated() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );
		$this->assertEquals( sprintf( file_get_contents( 'tests/populated-listing.txt' ), $p ), do_shortcode( '[a-z-listing]' ) );
	}
}
