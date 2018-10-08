<?php
class AZ_Shortcode_Tests extends AZ_UnitTestCase {
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
}
