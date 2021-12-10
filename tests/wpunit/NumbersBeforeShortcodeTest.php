<?php

declare(strict_types=1);

// Load a-z-listing-specific test extension

class AZ_Shortcode_Numbers_Before_Tests extends \Codeception\TestCase\WPTestCase {
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

	public function testEmpty() {
		$expected = file_get_contents( 'tests/_data/default-listing-numbers-before.txt' );
		$actual   = do_shortcode( '[a-z-listing numbers="before"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testEmptyGrouped() {
		$expected = file_get_contents( 'tests/_data/default-listing-numbers-before-grouped.txt' );
		$actual   = do_shortcode( '[a-z-listing numbers="before" grouping="numbers"]' );

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

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-numbers-before.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing numbers="before"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingGrouped() {
		$title = 'Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-numbers-before-grouped.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing numbers="before" grouping="numbers"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLowercaseTitles() {
		$title = 'test page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-numbers-before.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing numbers="before"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyListing() {
		$title = 'test category';
		$t     = static::factory()->term->create(
			array(
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing-numbers-before.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing display="terms" taxonomy="category" numbers="before"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyListingGrouped() {
		$title = 'test category';
		$t     = static::factory()->term->create(
			array(
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing-numbers-before-grouped.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing display="terms" taxonomy="category" numbers="before" grouping="numbers"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedFilteredListing() {
		$cat = 'test category';
		$t   = static::factory()->term->create(
			array(
				'name'     => $cat,
				'taxonomy' => 'category',
			)
		);

		$title = 'Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		wp_set_post_terms( $p, $t, 'category' );

		$term = get_term( $t, 'category' );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-numbers-before.txt' ), $title, $url );
		$actual   = do_shortcode( sprintf( '[a-z-listing taxonomy="category" terms="%s" numbers="before"]', $term->slug ) );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedFilteredListingGrouped() {
		$cat = 'test category';
		$t   = static::factory()->term->create(
			array(
				'name'     => $cat,
				'taxonomy' => 'category',
			)
		);

		$title = 'Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		wp_set_post_terms( $p, $t, 'category' );

		$term = get_term( $t, 'category' );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-numbers-before-grouped.txt' ), $title, $url );
		$actual   = do_shortcode( sprintf( '[a-z-listing taxonomy="category" terms="%s" numbers="before" grouping="numbers"]', $term->slug ) );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}
}
