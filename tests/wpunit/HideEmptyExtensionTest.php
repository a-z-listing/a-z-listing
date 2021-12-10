<?php

declare(strict_types=1);

class AZ_Listing_HideEmpty_Extension_Tests extends \Codeception\TestCase\WPTestCase {
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

    public function testHideEmptyIsTrue() {
        $display = 'terms';
        $key = 'hide-empty';
        $value = 'true';
        $attributes = array( $key => $value );

        $expected = array( 'hide_empty' => true );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testHideEmptyIsOn() {
        $display = 'terms';
        $key = 'hide-empty';
        $value = 'on';
        $attributes = array( $key => $value );

        $expected = array( 'hide_empty' => true );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testHideEmptyIsOne() {
        $display = 'terms';
        $key = 'hide-empty';
        $value = '1';
        $attributes = array( $key => $value );

        $expected = array( 'hide_empty' => true );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testHideEmptyIsFalse() {
        $display = 'terms';
        $key = 'hide-empty';
        $value = 'false';
        $attributes = array( $key => $value );

        $expected = array( 'hide_empty' => false );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testHideEmptyIsOff() {
        $display = 'terms';
        $key = 'hide-empty';
        $value = 'off';
        $attributes = array( $key => $value );

        $expected = array( 'hide_empty' => false );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }

    public function testHideEmptyIsZero() {
        $display = 'terms';
        $key = 'hide-empty';
        $value = '0';
        $attributes = array( $key => $value );

        $expected = array( 'hide_empty' => false );
        $actual   = apply_filters( "a_z_listing_shortcode_query_for_display__{$display}__and_attribute__{$key}", array(), $display, $key, $value, $attributes );

        $this->assertEquals( $expected, $actual );
    }
}
