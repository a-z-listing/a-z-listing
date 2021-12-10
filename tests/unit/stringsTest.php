<?php

declare( strict_types=1 );

class stringsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testMBStringToArray() {
        $input = "é€á";
        $expected = ["\xc3\xa9","\xe2\x82\xac","\xc3\xa1"];
        $received = \A_Z_Listing\Strings::mb_string_to_array( $input );
        $this->assertSame( $expected, $received );
    }

    public function testMaybeMBSubstring() {
        $input = "é€á";
        $expected = "\xc3\xa1";
        $received = \A_Z_Listing\Strings::maybe_mb_substr( $input, 2, 1 );
        $this->assertSame( $expected, $received );
    }
}