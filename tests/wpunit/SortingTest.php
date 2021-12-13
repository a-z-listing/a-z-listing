<?php

// Load a-z-listing-specific test extension

class AZ_Sorting_Tests extends \Codeception\TestCase\WPTestCase {
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

	public function testPopulatedListingWithPostsInitiallyInOrder() {
		$atitle = 'Test aaaa';
		$apost  = static::factory()->post->create(
			array(
				'post_title' => $atitle,
				'post_type'  => 'page',
			)
		);
		$aurl = sprintf( '?page_id=%s', $apost );

		$btitle = 'Test aaba';
		$bpost  = static::factory()->post->create(
			array(
				'post_title' => $btitle,
				'post_type'  => 'page',
			)
		);
		$burl = sprintf( '?page_id=%s', $bpost );

		$ctitle = 'Test aaca';
		$cpost  = static::factory()->post->create(
			array(
				'post_title' => $ctitle,
				'post_type'  => 'page',
			)
		);
		$curl = sprintf( '?page_id=%s', $cpost );

		$expected = sprintf(
			file_get_contents( 'tests/_data/populated-listing-sorting-test.txt' ),
			$atitle,
			$aurl,
			$btitle,
			$burl,
			$ctitle,
			$curl
		);
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingWithPostsInitiallyOutOfOrder() {
		$btitle = 'Test aaba';
		$bpost  = static::factory()->post->create(
			array(
				'post_title' => $btitle,
				'post_type'  => 'page',
			)
		);
		$burl = sprintf( '?page_id=%s', $bpost );

		$atitle = 'Test aaaa';
		$apost  = static::factory()->post->create(
			array(
				'post_title' => $atitle,
				'post_type'  => 'page',
			)
		);
		$aurl = sprintf( '?page_id=%s', $apost );

		$ctitle = 'Test aaca';
		$cpost  = static::factory()->post->create(
			array(
				'post_title' => $ctitle,
				'post_type'  => 'page',
			)
		);
		$curl = sprintf( '?page_id=%s', $cpost );

		$expected = sprintf(
			file_get_contents( 'tests/_data/populated-listing-sorting-test.txt' ),
			$atitle,
			$aurl,
			$btitle,
			$burl,
			$ctitle,
			$curl
		);
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingWithNumbersAndPostsInitiallyInOrder() {
		$atitle = 'Test aa1a';
		$apost  = static::factory()->post->create(
			array(
				'post_title' => $atitle,
				'post_type'  => 'page',
			)
		);
		$aurl = sprintf( '?page_id=%s', $apost );

		$btitle = 'Test aa2a';
		$bpost  = static::factory()->post->create(
			array(
				'post_title' => $btitle,
				'post_type'  => 'page',
			)
		);
		$burl = sprintf( '?page_id=%s', $bpost );

		$ctitle = 'Test aa3a';
		$cpost  = static::factory()->post->create(
			array(
				'post_title' => $ctitle,
				'post_type'  => 'page',
			)
		);
		$curl = sprintf( '?page_id=%s', $cpost );

		$expected = sprintf(
			file_get_contents( 'tests/_data/populated-listing-sorting-test.txt' ),
			$atitle,
			$aurl,
			$btitle,
			$burl,
			$ctitle,
			$curl
		);
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingWithNumbersAndPostsInitiallyOutOfOrder() {
		$btitle = 'Test aa2a';
		$bpost  = static::factory()->post->create(
			array(
				'post_title' => $btitle,
				'post_type'  => 'page',
			)
		);
		$burl = sprintf( '?page_id=%s', $bpost );

		$atitle = 'Test aa1a';
		$apost  = static::factory()->post->create(
			array(
				'post_title' => $atitle,
				'post_type'  => 'page',
			)
		);
		$aurl = sprintf( '?page_id=%s', $apost );

		$ctitle = 'Test aa3a';
		$cpost  = static::factory()->post->create(
			array(
				'post_title' => $ctitle,
				'post_type'  => 'page',
			)
		);
		$curl = sprintf( '?page_id=%s', $cpost );

		$expected = sprintf(
			file_get_contents( 'tests/_data/populated-listing-sorting-test.txt' ),
			$atitle,
			$aurl,
			$btitle,
			$burl,
			$ctitle,
			$curl
		);
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingWithSymbolsDifferingByAlpha() {
		$atitle = 'Test aa%a';
		$apost  = static::factory()->post->create(
			array(
				'post_title' => $atitle,
				'post_type'  => 'page',
			)
		);
		$aurl = sprintf( '?page_id=%s', $apost );

		$btitle = 'Test aa%b';
		$bpost  = static::factory()->post->create(
			array(
				'post_title' => $btitle,
				'post_type'  => 'page',
			)
		);
		$burl = sprintf( '?page_id=%s', $bpost );

		$ctitle = 'Test aa%c';
		$cpost  = static::factory()->post->create(
			array(
				'post_title' => $ctitle,
				'post_type'  => 'page',
			)
		);
		$curl = sprintf( '?page_id=%s', $cpost );

		$expected = sprintf(
			file_get_contents( 'tests/_data/populated-listing-sorting-test.txt' ),
			$atitle,
			$aurl,
			$btitle,
			$burl,
			$ctitle,
			$curl
		);
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}


	public function testPopulatedListingWithSymbolsDifferingBySymbol() {
		$ctitle = 'Test aa^a';
		$cpost  = static::factory()->post->create(
			array(
				'post_title' => $ctitle,
				'post_type'  => 'page',
			)
		);
		$curl = sprintf( '?page_id=%s', $cpost );

		$atitle = 'Test aa!a';
		$apost  = static::factory()->post->create(
			array(
				'post_title' => $atitle,
				'post_type'  => 'page',
			)
		);
		$aurl = sprintf( '?page_id=%s', $apost );

		$btitle = 'Test aa%a';
		$bpost  = static::factory()->post->create(
			array(
				'post_title' => $btitle,
				'post_type'  => 'page',
			)
		);
		$burl = sprintf( '?page_id=%s', $bpost );

		$expected = sprintf(
			file_get_contents( 'tests/_data/populated-listing-sorting-test.txt' ),
			$atitle,
			$aurl,
			$btitle,
			$burl,
			$ctitle,
			$curl
		);
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingWithPostsDifferingInLength() {
		$atitle = 'Test aaaa';
		$apost  = static::factory()->post->create(
			array(
				'post_title' => $atitle,
				'post_type'  => 'page',
			)
		);
		$aurl = sprintf( '?page_id=%s', $apost );

		$btitle = 'Test aaaaa';
		$bpost  = static::factory()->post->create(
			array(
				'post_title' => $btitle,
				'post_type'  => 'page',
			)
		);
		$burl = sprintf( '?page_id=%s', $bpost );

		$ctitle = 'Test aaaaaa';
		$cpost  = static::factory()->post->create(
			array(
				'post_title' => $ctitle,
				'post_type'  => 'page',
			)
		);
		$curl = sprintf( '?page_id=%s', $cpost );

		$expected = sprintf(
			file_get_contents( 'tests/_data/populated-listing-sorting-test.txt' ),
			$atitle,
			$aurl,
			$btitle,
			$burl,
			$ctitle,
			$curl
		);
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}
}
