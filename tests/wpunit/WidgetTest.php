<?php

declare(strict_types=1);

// Load a-z-listing-specific test extension

class AZ_Widget_Tests extends \Codeception\TestCase\WPTestCase {
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
	
	public function testWidget() {
		$p = static::factory()->post->create(
			array(
				'post_title' => 'Index Page',
				'post_type'  => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/_data/default-widget.txt' ), $p );

		$actual = get_the_section_a_z_widget(
			array(
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>',
			),
			array(
				'title' => 'Test Widget',
				'post'  => $p,
			)
		);

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedWidget() {
		$p  = static::factory()->post->create(
			array(
				'post_title' => 'Index Page',
				'post_type'  => 'page',
			)
		);
		$p2 = static::factory()->post->create(
			array(
				'post_title' => 'Test Post',
				'post_type'  => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/_data/populated-widget.txt' ), $p );

		$actual = get_the_section_a_z_widget(
			array(
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>',
			),
			array(
				'title' => 'Test Widget',
				'post'  => $p,
			)
		);

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedWidgetObsoleteConfiguration() {
		$p  = static::factory()->post->create(
			array(
				'post_title' => 'Index Page',
				'post_type'  => 'page',
			)
		);
		$p2 = static::factory()->post->create(
			array(
				'post_title' => 'Test Post',
				'post_type'  => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/_data/populated-widget.txt' ), $p );

		$actual = get_the_section_a_z_widget(
			array(
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>',
			),
			array(
				'title' => 'Test Widget',
				'page'  => $p,
			)
		);

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testPopulatedWidgetLowercaseTitles() {
		$p  = static::factory()->post->create(
			array(
				'post_title' => 'Index Page',
				'post_type'  => 'page',
			)
		);
		$p2 = static::factory()->post->create(
			array(
				'post_title' => 'test post',
				'post_type'  => 'page',
			)
		);

		$expected = sprintf( file_get_contents( 'tests/_data/populated-widget.txt' ), $p );

		$actual = get_the_section_a_z_widget(
			array(
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>',
			),
			array(
				'title' => 'Test Widget',
				'post'  => $p,
			)
		);

		$this->tester->seeHTMLEquals( $expected, $actual );
	}

	public function testGetPostsByTitle() {
		$p  = static::factory()->post->create(
			array(
				'post_title' => 'Test Page',
				'post_type'  => 'page',
			)
		);
		$p2 = static::factory()->post->create(
			array(
				'post_title' => 'Another Page',
				'post_type'  => 'page',
			)
		);

		$posts = a_z_listing_get_posts_by_title( 'Page', 'page' );
		$this->assertEquals( count( $posts ), 2 );
		$this->assertContains( $posts[0]->ID, array( $p, $p2 ) );
		$this->assertContains( $posts[1]->ID, array( $p, $p2 ) );

		$posts = a_z_listing_get_posts_by_title( 'Test', 'page' );
		$this->assertEquals( count( $posts ), 1 );
		$this->assertEquals( $posts[0]->ID, $p );
	}
}
