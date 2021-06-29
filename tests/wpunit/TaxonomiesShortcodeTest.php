<?php

declare(strict_types=1);

// Load a-z-listing-specific test extension

class AZ_Shortcode_Taxonomies_Tests extends \Codeception\TestCase\WPTestCase {
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

	public function testPopulatedTaxonomyListing() {
		$title = 'test category';
		$t     = static::factory()->term->create(
			array(
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing display="terms" taxonomy="category"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedMultipleTaxonomyListing() {
		$cat_title = 'Test Category';
		$cat       = static::factory()->term->create(
			array(
				'name'     => $cat_title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $cat );

		$tag_title = 'Test Tag';
		$tag       = static::factory()->term->create(
			array(
				'name'     => $tag_title,
				'taxonomy' => 'post_tag',
			)
		);
		
		$expected = sprintf( file_get_contents( 'tests/_data/populated-multiple-taxonomy-listing.txt' ), $cat_title, $url, $tag_title, '?tag=test-tag' );
		$actual   = do_shortcode( '[a-z-listing display="terms" taxonomy="category,post_tag"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyChildTermsByIdInIdAttributeListing() {
		$cat_title = 'Parent-Category';
		$cat_slug  = 'parent-category';
		$cat       = static::factory()->term->create(
			array(
				'slug'     => $cat_slug,
				'name'     => $cat_title,
				'taxonomy' => 'category',
			)
		);
		$title = 'test category';
		$t     = static::factory()->term->create(
			array(
				'parent'   => $cat,
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing-parent-term.txt' ), $title, $url );
		$actual   = do_shortcode( sprintf( '[a-z-listing display="terms" taxonomy="category,post_tag" parent-term-id="%s"]', $cat ) );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyChildTermsByIdInSlugAttributeListing() {
		$cat_title = 'Parent-Category';
		$cat_slug  = 'parent-category';
		$cat       = static::factory()->term->create(
			array(
				'slug'     => $cat_slug,
				'name'     => $cat_title,
				'taxonomy' => 'category',
			)
		);
		$title = 'test category';
		$t     = static::factory()->term->create(
			array(
				'parent'   => $cat,
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing-parent-term.txt' ), $title, $url );
		$actual   = do_shortcode( sprintf( '[a-z-listing display="terms" taxonomy="category,post_tag" parent-term="%s"]', $cat ) );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedTaxonomyChildTermsBySlugInSlugAttributeListing() {
		$cat_title = 'Parent-Category';
		$cat_slug  = 'parent-category';
		$cat       = static::factory()->term->create(
			array(
				'slug'     => $cat_slug,
				'name'     => $cat_title,
				'taxonomy' => 'category',
			)
		);
		$title = 'test category';
		$t     = static::factory()->term->create(
			array(
				'parent'   => $cat,
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$url = sprintf( '?cat=%s', $t );
		
		$expected = sprintf( file_get_contents( 'tests/_data/populated-taxonomy-listing-parent-term.txt' ), $title, $url );
		$actual   = do_shortcode( sprintf( '[a-z-listing display="terms" taxonomy="category,post_tag" parent-term="%s"]', $cat_slug ) );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}
}
