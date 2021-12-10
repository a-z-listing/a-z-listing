<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Wpunit extends \Codeception\Module
{
    public function seeHTMLEquals( $expected, $actual ) {
		$expected_dom = new \DomDocument();
        $expected_dom->preserveWhiteSpace = false;
		$actual_dom   = new \DomDocument();
        $actual_dom->preserveWhiteSpace = false;

        $re = '/\>\s+([^<]*?)\s+\</m';
        $expected = preg_replace( $re, '>$1<', $expected );
        $actual = preg_replace( $re, '>$1<', $actual );

        $actual = trim( $actual );
		$this->assertNotEmpty( $actual );
		
		$expected_dom->loadXML( '<test>' . $expected . '</test>' );
		$actual_dom->loadXML( '<test>' . $actual . '</test>' );

		$this->assertXMLStringEqualsXMLString( $expected_dom->saveXML(), $actual_dom->saveXML() );
	}
}
