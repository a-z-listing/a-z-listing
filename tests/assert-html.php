<?php

class AZ_UnitTestCase extends WP_UnitTestCase {
	protected function assertHTMLEquals( $expected, $actual ) {
		$expected_dom = new DomDocument( $expected );
		$actual_dom   = new DomDocument( $actual );

		$expected_dom->preserveWhiteSpace = false;
		$actual_dom->preserveWhiteSpace   = false;

		$this->assertEquals( $expected_dom->saveHTML(), $actual_dom->saveHTML() );
	}
}
