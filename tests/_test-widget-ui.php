<?php
class AZ_Widget_UI_Tests extends PHPUnit_Extensions_SeleniumTestCase {
    public static $browsers = array(
        array(
            'name'    => 'Firefox on Linux',
            'browser' => '*firefox',
            'timeout' => 30000,
        ),
        array(
            'name'    => 'Chromium on Linux',
            'browser' => '*chromium',
            'timeout' => 30000,
        ),
        array(
            'name'    => 'Safari on MacOS X',
            'browser' => '*safari',
            'timeout' => 30000,
        ),
    );

    protected function setUp() {
        $this->setBrowserUrl( 'http://www.example.com/' );
    }

    public function testTitle() {
        $this->open( 'http://www.example.com/' );
        $this->assertEquals( 'title', $this->title() );
    }
}
