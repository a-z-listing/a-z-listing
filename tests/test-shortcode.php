<?php

// Load a-z-listing-specific test extension
require_once 'html-assertions.php';

class AZ_Shortcode_Tests extends WP_UnitTestCase {
	use HtmlAssertions;

	public function test_empty() {
		$expected = file_get_contents( 'tests/data/default-listing.txt' );
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_listing() {
		$title = 'Test Page';
		$p     = $this->factory->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing.txt' ), $title, $p );
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_lowercase_titles() {
		$p = $this->factory->post->create(
			array(
				'post_title' => 'test page',
				'post_type'  => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing-lowercase.txt' ), $p );
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_children() {
		$title  = 'Test Page';
		$parent = $this->factory->post->create(
			array(
				'post_title' => 'Parent post',
				'post_type'  => 'page',
			)
		);
		$p     = $this->factory->post->create(
			array(
				'post_title'  => $title,
				'post_type'   => 'page',
				'post_parent' => $parent,
			)
		);

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing.txt' ), $title, $p + 5 );
		$actual   = do_shortcode( "[a-z-listing parent-post='$parent']" );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_taxonomy_listing() {
		$title = 'test category';
		$t     = $this->factory->term->create(
			array(
				'name'     => $title,
				'taxonomy' => 'category',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/data/populated-taxonomy-listing.txt' ), $title, $t );
		$actual   = do_shortcode( '[a-z-listing display="terms" taxonomy="category"]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_multiple_taxonomy_listing() {
		$cat_title = 'Test Category';
		$cat       = $this->factory->term->create(
			array(
				'name'     => $cat_title,
				'taxonomy' => 'category',
			)
		);
		$tag_title = 'Test Tag';
		$tag       = $this->factory->term->create(
			array(
				'name'     => $tag_title,
				'taxonomy' => 'post_tag',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/data/populated-multiple-taxonomy-listing.txt' ), $cat_title, $cat, $tag_title, $tag );
		$actual   = do_shortcode( '[a-z-listing display="terms" taxonomy="category,post_tag"]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_filtered_pages_listing() {
		$this->factory->post->create(
			array(
				'post_title' => 'Must not be visible',
				'post_type'  => 'page',
			)
		);

		$post_title = 'Test Page';
		$p          = $this->factory->post->create(
			array(
				'post_title' => $post_title,
				'post_type'  => 'page',
			)
		);

		$term_title = 'test category';
		$t          = $this->factory->term->create(
			array(
				'name'     => $term_title,
				'taxonomy' => 'category',
				'slug'     => 'test-category',
			)
		);

		$this->factory->term->add_post_terms( $p, $t, 'category', true );

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing.txt' ), $post_title, $p );
		$actual   = do_shortcode( '[a-z-listing post-type="page" taxonomy="category" terms="test-category"]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_filtered_posts_listing() {
		$this->factory->post->create(
			array(
				'post_title' => 'Must not be visible',
				'post_type'  => 'post',
			)
		);

		$post_title = 'Test Post';
		$p          = $this->factory->post->create(
			array(
				'post_title' => $post_title,
				'post_type'  => 'post',
			)
		);

		$term_title = 'test category';
		$t          = $this->factory->term->create(
			array(
				'name'     => $term_title,
				'taxonomy' => 'category',
				'slug'     => 'test-category',
			)
		);

		$this->factory->term->add_post_terms( $p, $t, 'category', true );

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing.txt' ), $post_title, $p );
		$actual   = do_shortcode( '[a-z-listing post-type="post" taxonomy="category" terms="test-category"]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_multiple_post_type_listing() {
		$title1 = 'Test title 1';
		$post1  = $this->factory->post->create(
			array(
				'post_title' => $title1,
				'post_type'  => 'post',
			)
		);

		$title2 = 'Test title 2';
		$post2  = $this->factory->post->create(
			array(
				'post_title' => $title2,
				'post_type'  => 'page',
			)
			);

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing-multiple-post-types.txt' ), $title1, $post1, $title2, $post2 );
		$actual   = do_shortcode( '[a-z-listing post-type="post,page"]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_symbols_last_listing() {
		$title = '%Test Page';
		$p     = $this->factory->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing-symbols-last.txt' ), $title, $p );
		$actual   = do_shortcode( '[a-z-listing]' );

		$this->assertHTMLEquals( $expected, $actual );
	}

	public function test_populated_symbols_first_listing() {
		$title = '%Test Page';
		$p     = $this->factory->post->create(
			array(
				'post_title' => $title,
				'post_type'  => 'page',
			)
		);

		add_filter( 'a_z_listing_unknown_letters_first', '__return_true' );
		$expected = sprintf( file_get_contents( 'tests/data/populated-listing-symbols-first.txt' ), $title, $p );
		$actual   = do_shortcode( '[a-z-listing]' );
		remove_filter( 'a_z_listing_unknown_letters_first', '__return_true' );

		$this->assertHTMLEquals( $expected, $actual );
	}
}
