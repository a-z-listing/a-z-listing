<?php

declare(strict_types=1);

// Load a-z-listing-specific test extension

class AZ_Listing_Tests extends \Codeception\TestCase\WPTestCase {
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

	public function testEmptyLetters() {
		$expected = file_get_contents( 'tests/_data/default-letters.txt' );
		$actual   = get_the_a_z_letters( null, '', '', false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}
	public function testEmptyListing() {
		$expected = file_get_contents( 'tests/_data/default-listing.txt' );
		$actual   = get_the_a_z_listing( null, false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLetters() {
		$p = static::factory()->post->create(
			array(
				'post_title' => 'Test Page',
				'post_type'  => 'page',
			)
		);

		$q = new WP_Query(
			array(
				'post_type' => 'page',
			)
		);

		$expected = file_get_contents( 'tests/_data/populated-letters.txt' );
		$actual   = get_the_a_z_letters( $q, '', '', false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLettersLinked() {
		$p = static::factory()->post->create(
			array(
				'post_title' => 'Test Page',
				'post_type'  => 'page',
			)
		);
		$q = new WP_Query(
			array(
				'post_type' => 'page',
			)
		);

		$expected = file_get_contents( 'tests/_data/populated-letters-linked.txt' );
		$actual   = get_the_a_z_letters( $q, '/test-path', '', false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListing() {
		$title = 'Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$q     = new WP_Query(
			array(
				'post_type' => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing.txt' ), $title, $url );
		$actual   = get_the_a_z_listing( $q, false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingWithUnknownLetters() {
		$title = '*Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$q     = new WP_Query(
			array(
				'post_type' => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-unknown-letters.txt' ), $title, $url );
		$actual   = get_the_a_z_listing( $q, false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyListingStringQuery() {
		$title = 'Test Category';
		$t     = static::factory()->term->create(
			array(
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing.txt' ), $title, $url );
		$actual   = get_the_a_z_listing( 'category', false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyListingArrayQuery() {
		$title = 'Test Category';
		$t     = static::factory()->term->create(
			array(
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected    = trim( sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing.txt' ), $title, $url ) );
		$a_z_listing = new \A_Z_Listing\Query(
			array(
				'taxonomy' => 'category',
			),
			'terms'
		);
		$actual      = $a_z_listing->get_the_listing();

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyListingWithHideEmptyArrayQuery() {
		static::factory()->term->create(
			array(
				'name'     => 'Test Category',
				'taxonomy' => 'category',
			)
		);

		static::factory()->post->create(
			array(
				'title'      => 'Test Post',
				'post_type'  => 'post',
			)
		);

		$expected    = trim( file_get_contents( 'tests/_data/populated-taxonomy-listing-hide-empty.txt' ) );
		$a_z_listing = new \A_Z_Listing\Query(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => true,
			),
			'terms'
		);
		$actual      = $a_z_listing->get_the_listing();

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedMultipleTaxonomyListingArrayQuery() {
		$cat_title = 'Test Category';
		$cat       = static::factory()->term->create(
			array(
				'name'     => $cat_title,
				'taxonomy' => 'category',
			)
		);
		$cat_url = sprintf( '?cat=%s', $cat );
		$tag_title = 'Test Tag';
		$tag       = static::factory()->term->create(
			array(
				'name'     => $tag_title,
				'taxonomy' => 'post_tag',
			)
		);
		$tag_url = sprintf( '?tag=%s', 'test-tag' );

		$expected    = trim( sprintf( file_get_contents( 'tests/_data/populated-multiple-taxonomy-listing.txt' ), $cat_title, $cat_url, $tag_title, $tag_url ) );
		$a_z_listing = new \A_Z_Listing\Query(
			array(
				'taxonomy' => array(
					'category',
					'post_tag',
				),
			),
			'terms'
		);
		$actual      = $a_z_listing->get_the_listing();

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLowercaseLetters() {
		$p = static::factory()->post->create(
			array(
				'post_title' => 'test page',
				'post_type'  => 'page',
			)
		);
		$q = new WP_Query(
			array(
				'post_type' => 'page',
			)
		);

		$expected = file_get_contents( 'tests/_data/populated-letters.txt' );
		$actual   = get_the_a_z_letters( $q, '' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLowercaseLettersLinked() {
		$p = static::factory()->post->create(
			array(
				'post_title' => 'test page',
				'post_type'  => 'page',
			)
		);
		$q = new WP_Query(
			array(
				'post_type' => 'page',
			)
		);

		$expected = file_get_contents( 'tests/_data/populated-letters-linked.txt' );
		$actual   = get_the_a_z_letters( $q, '/test-path', '', false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLowercaseListing() {
		$title = 'test page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$q     = new WP_Query(
			array(
				'post_type' => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing.txt' ), $title, $url );
		$actual   = get_the_a_z_listing( $q, false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLowercaseTaxonomyListing() {
		$title = 'test category';
		$t     = static::factory()->term->create(
			array(
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing.txt' ), $title, $url );
		$actual   = get_the_a_z_listing( 'category', false );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}
}
