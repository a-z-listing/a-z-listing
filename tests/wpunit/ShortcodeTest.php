<?php

// Load a-z-listing-specific test extension

class AZ_Shortcode_Tests extends \Codeception\TestCase\WPTestCase {
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

	public function testEmptyListing() {
		$expected = file_get_contents( 'tests/_data/default-listing.txt' );
		$actual   = do_shortcode( '[a-z-listing]' );

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

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedLowercaseTitlesListing() {
		$p = static::factory()->post->create(
			array(
				'post_title' => 'test page',
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-lowercase.txt' ), $url );
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedChildrenListing() {
		$title  = 'Test Page';
		$parent = static::factory()->post->create(
			array(
				'post_title' => 'Parent post',
				'post_type'  => 'page',
			)
		);
		$p     = static::factory()->post->create(
			array(
				'post_title'  => $title,
				'post_type'   => 'page',
				'post_parent' => $parent,
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected  = trim( sprintf( file_get_contents( 'tests/_data/populated-listing.txt' ), $title, $url ) );
		$shortcode = sprintf( '[a-z-listing parent-post="%s"]', $parent );
		$actual    = do_shortcode( $shortcode );

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
		$cat_url = sprintf( '?cat=%s', $cat );
		$tag_title = 'Test Tag';
		$tag       = static::factory()->term->create(
			array(
				'name'     => $tag_title,
				'taxonomy' => 'post_tag',
			)
		);
		$tag_url = sprintf( '?tag=%s', 'test-tag' );
	
		$expected = sprintf( file_get_contents( 'tests/_data/populated-multiple-taxonomy-listing.txt' ), $cat_title, $cat_url, $tag_title, $tag_url );
		$actual   = do_shortcode( '[a-z-listing display="terms" taxonomy="category,post_tag"]' );
	
		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedPagesFilteredByTaxonomyTermsListing() {
		static::factory()->post->create(
			array(
				'post_title' => 'Must not be visible',
				'post_type'  => 'page',
			)
		);

		$post_title = 'Test Page';
		$p          = static::factory()->post->create(
			array(
				'post_title' => $post_title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$t = static::factory()->term->create(
			array(
				'name'     => 'test category',
				'taxonomy' => 'category',
				'slug'     => 'test-category',
			)
		);

		$this->assertNotWPError( static::factory()->term->add_post_terms( $p, $t, 'category', true ) );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing.txt' ), $post_title, $url );
		$actual   = do_shortcode( '[a-z-listing post-type="page" taxonomy="category" terms="test-category"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedPostsFilteredByTaxonomyTermsListing() {
		static::factory()->post->create(
			array(
				'post_title' => 'Must not be visible',
				'post_type'  => 'post',
			)
		);

		$post_title = 'Test Post';
		$p          = static::factory()->post->create(
			array(
				'post_title' => $post_title,
				'post_type'  => 'post',
			)
		);

		$url = sprintf( '?p=%s', $p );

		$t = static::factory()->term->create(
			array(
				'name'     => 'test category',
				'taxonomy' => 'category',
				'slug'     => 'test-category',
			)
		);

		$this->assertNotWPError( static::factory()->term->add_post_terms( $p, $t, 'category', true ) );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing.txt' ), $post_title, $url );
		$actual   = do_shortcode( '[a-z-listing post-type="post" taxonomy="category" terms="test-category"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testMultiplePostTypeListing() {
		$title1 = 'Test title 1';
		$post1  = static::factory()->post->create(
			array(
				'post_title' => $title1,
				'post_type'  => 'post',
			)
		);

		$url1 = sprintf( '?p=%s', $post1 );

		$title2 = 'Test title 2';
		$post2  = static::factory()->post->create(
			array(
				'post_title' => $title2,
				'post_type'  => 'page',
			)
		);

		$url2 = sprintf( '?page_id=%s', $post2 );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-multiple-post-types.txt' ), $title1, $url1, $title2, $url2 );
		$actual   = do_shortcode( '[a-z-listing post-type="post,page"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testEmptyListingAfterExcludePosts() {
		$title = 'Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);


		$expected  = file_get_contents( 'tests/_data/default-listing.txt' );
		$shortcode = sprintf( '[a-z-listing display="posts" post-type="page" exclude-posts="%s"]', $p );
		$actual    = do_shortcode( $shortcode );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedListingAfterExcludePosts() {
		$exclude_id = static::factory()->post->create(
			array(
				'post_title' => 'Must not be visible',
				'post_type'  => 'page',
			)
		);

		$post_title = 'Test Post';
		$p          = static::factory()->post->create(
			array(
				'post_title' => $post_title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing.txt' ), $post_title, $url );
		$shortcode = sprintf( '[a-z-listing display="posts" post-type="page" exclude-posts="%s"]', $exclude_id );
		$actual    = do_shortcode( $shortcode );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedSymbolsLastListing() {
		$title = '%Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-symbols-last.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedSymbolsLastOverrideListing() {
		$title = '%Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-symbols-last.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing symbols-first="no"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedSymbolsFirstOverrideListing() {
		$title = '%Test Page';
		$p     = static::factory()->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$url = sprintf( '?page_id=%s', $p );

		$expected = sprintf( file_get_contents( 'tests/_data/populated-listing-symbols-first.txt' ), $title, $url );
		$actual   = do_shortcode( '[a-z-listing symbols-first="yes"]' );

		$this->tester->seeHTMLEquals( $expected, $actual );
	}
}
