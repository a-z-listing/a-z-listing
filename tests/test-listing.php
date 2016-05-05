<?php
class AZ_Listing_Tests extends WP_UnitTestCase {
	function test_empty_letters() {
		$expected = file_get_contents( 'tests/default-letters.txt' );
		$actual = get_the_a_z_letters();

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}
	function test_empty_listing() {
		$expected = file_get_contents( 'tests/default-listing.txt' );
		$actual = get_the_a_z_listing();

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated_letters() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );
		$q = new WP_Query( array( 'post_type' => 'page' ) );

		$expected = file_get_contents( 'tests/populated-letters.txt' );
		$actual = get_the_a_z_letters( $q );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated_letters_linked() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );
		$q = new WP_Query( array( 'post_type' => 'page' ) );

		$expected = file_get_contents( 'tests/populated-letters-linked.txt' );
		$actual = get_the_a_z_letters( $q, '/test-path' );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated_listing() {
		$p = $this->factory->post->create( array( 'post_title' => 'Test Page', 'post_type' => 'page' ) );
		$q = new WP_Query( array( 'post_type' => 'page' ) );

		$expected = sprintf( file_get_contents( 'tests/populated-listing.txt' ), $p );
		$actual = get_the_a_z_listing( $q );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}
}
