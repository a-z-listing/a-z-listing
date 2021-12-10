<?php

declare(strict_types=1);

class AZ_Listing_ExcludePosts_Extension_Tests extends \Codeception\TestCase\WPTestCase {
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

    public function testExclusion() {
        $display = 'posts';
        $key = 'exclude-posts';
        $value = '2,4,6';
        $attributes = array( $key => $value );

        $expected = array( 'post__not_in' => array( 2, 4, 6 ) );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }
}
