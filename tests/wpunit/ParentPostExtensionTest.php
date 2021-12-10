<?php

declare(strict_types=1);

class AZ_Listing_ParentPost_Extension_Tests extends \Codeception\TestCase\WPTestCase {
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    public function setUp()
    {
        // Before...
        parent::setUp();

        // Your set up methods here.
    }

    public function tearDown()
    {
        // Your tear down methods here.

        // Then...
        parent::tearDown();
    }

    public function testParentPost() {
        $display = 'posts';
        $key = 'parent-post';
        $value = '256';
        $attributes = array( $key => $value );

        $expected = array( 'post_parent' => $value );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testParentPostGetAllChildren() {
        $display = 'posts';
        $key = 'parent-post';
        $value = '256';
        $attributes = array( $key => $value, 'get-all-children' => 'true' );

        $expected = array( 'child_of' => $value );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }
}
