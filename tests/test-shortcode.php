<?php
class AZ_Shortcode_Tests extends WP_UnitTestCase {
	function test_empty() {
		$expected = file_get_contents( 'tests/default-listing.txt' );
		$actual = do_shortcode( '[a-z-listing]' );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );

		$expected = sprintf( file_get_contents( 'tests/populated-listing.txt' ), $p );
		$actual = do_shortcode( '[a-z-listing]' );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}
}
