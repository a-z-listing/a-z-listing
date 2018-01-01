<?php

class AZ_UnitTestCase extends WP_UnitTestCase {
	function assertHTMLEquals( $expected, $actual ) {
		$expectedDom = new DomDocument( $expected );
		$actualDom = new DomDocument( $actual );

		$expectedDom->preserveWhiteSpace = false;
		$actualDom->preserveWhiteSpace = false;

		$this->assertEquals( $expectedDom->saveHTML(), $actualDom->saveHTML() );
	}
}
