<?php

declare(strict_types=1);

class AZ_Listing_Alphabet_Extension_Tests extends \Codeception\TestCase\WPTestCase {
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

    public $defaultAlphabet = 'AÁÀÄÂaáàäâ,Bb,CÇcç,Dd,EÉÈËÊeéèëê,Ff,Gg,Hh,IÍÌÏÎiíìïî,Jj,Kk,Ll,Mm,Nn,OÓÒÖÔoóòöô,Pp,Qq,Rr,Ssß,Tt,UÚÙÜÛuúùüû,Vv,Ww,Xx,Yy,Zz';

    public function testAlphabetIsBlank() {
        $expected = $this->defaultAlphabet;
        $actual   = apply_filters( 'a-z-listing-alphabet', $this->defaultAlphabet );

        $this->assertEquals( $expected, $actual );
    }

    public function testAlphabet() {
        $display = 'posts';
        $key = 'alphabet';
        $value = 'abc123';
        $attributes = array( $key => $value );

        apply_filters( "a_z_listing_shortcode_query_for_attribute__{$key}", array(), $display, $key, $value, $attributes );
        $expected = $value;
        $actual   = apply_filters( 'a-z-listing-alphabet', $this->defaultAlphabet );

        $this->assertEquals( $expected, $actual );
    }
}
