<?php

declare(strict_types=1);

class AZ_Listing_Taxonomy_Extension_Tests extends \Codeception\TestCase\WPTestCase {
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

    public function testTaxonomyIsCategoryForTermsDisplay() {
        $display = 'terms';
        $key = 'taxonomy';
        $value = 'category';
        $attributes = array( $key => $value );

        $expected = array( 'taxonomy' => array( 'category' ) );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testTaxonomyIsPostTagForTermsDisplay() {
        $display = 'terms';
        $key = 'taxonomy';
        $value = 'post_tag';
        $attributes = array( $key => $value );

        $expected = array( 'taxonomy' => array( 'post_tag' ) );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testTaxonomyIsMultipleForTermsDisplay() {
        $display = 'terms';
        $key = 'taxonomy';
        $value = 'category,post_tag';
        $attributes = array( $key => $value );

        $expected = array( 'taxonomy' => array( 'category', 'post_tag' ) );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }
}
