<?php

declare(strict_types=1);

class AZ_Listing_Terms_Extension_Tests extends \Codeception\TestCase\WPTestCase {
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

    public function testIncludeTermIdsForDisplayPosts() {
        $display = 'posts';
        $key = 'terms';
        $value = '2,4,6';
        $attributes = array( $key => $value, 'taxonomy' => 'category' );

        $expected = array(
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy'         => $attributes['taxonomy'],
                    'field'            => 'term_id',
                    'terms'            => array( '2', '4', '6' ),
                    'operator'         => 'IN',
                    'include_children' => false
                ),
            ),
        );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testExcludeTermIdsForDisplayPosts() {
        $display = 'posts';
        $key = 'terms';
        $value = '-2,-4,-6';
        $attributes = array( $key => $value, 'taxonomy' => 'category' );

        $expected = array(
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy'         => $attributes['taxonomy'],
                    'field'            => 'term_id',
                    'terms'            => array( '2', '4', '6' ),
                    'operator'         => 'NOT IN',
                    'include_children' => false
                ),
            ),
        );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testIncludeAndExcludeTermIdsForDisplayPosts() {
        $display = 'posts';
        $key = 'terms';
        $value = '-2,4,-6';
        $attributes = array( $key => $value, 'taxonomy' => 'category' );

        $expected = array(
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy'         => $attributes['taxonomy'],
                    'field'            => 'term_id',
                    'terms'            => array( '4' ),
                    'operator'         => 'IN',
                    'include_children' => false
                ),
                array(
                    'taxonomy'         => $attributes['taxonomy'],
                    'field'            => 'term_id',
                    'terms'            => array( '2', '6' ),
                    'operator'         => 'NOT IN',
                    'include_children' => false
                ),
            ),
        );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testIncludeTermIdsForDisplayTerms() {
        $display = 'terms';
        $key = 'terms';
        $value = '2,4,6';
        $attributes = array( $key => $value, 'taxonomy' => 'category' );

        $expected = array(
            'include' => array( '2', '4', '6' ),
        );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testExcludeTermIdsForDisplayTerms() {
        $display = 'terms';
        $key = 'terms';
        $value = '-2,-4,-6';
        $attributes = array( $key => $value, 'taxonomy' => 'category' );

        $expected = array(
            'exclude' => array( '2', '4', '6' ),
        );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testIncludeAndExcludeTermIdsForDisplayTerms() {
        $display = 'terms';
        $key = 'terms';
        $value = '-2,4,-6';
        $attributes = array( $key => $value, 'taxonomy' => 'category' );

        $expected = array(
            'include' => array( '4' ),
        );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }
}
