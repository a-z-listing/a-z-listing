<?php
class AZ_Widget_Tests extends WP_UnitTestCase {
	function test_widget() {
		$p = $this->factory->post->create( array(
			'post_title' => 'Index Page',
			'post_type' => 'page',
		) );

		$expected = sprintf( file_get_contents( 'tests/default-widget.txt' ), $p );

		ob_start();
		the_section_a_z_widget(
			array(
				'before_widget' => '<div>',
				'after_widget' => '</div>',
				'before_title' => '<h2>',
				'after_title' => '</h2>',
			),
			array(
				'title' => 'Test Widget',
				'post' => $p,
			)
		);
		$actual = ob_get_clean();

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}
	function test_populated_widget() {
		$p = $this->factory->post->create( array(
			'post_title' => 'Index Page',
			'post_type' => 'page',
		) );
		$p2 = $this->factory->post->create( array(
			'post_title' => 'Test Post',
			'post_type' => 'page',
		) );

		$expected = sprintf( file_get_contents( 'tests/populated-widget.txt' ), $p );

		ob_start();
		the_section_a_z_widget(
			array(
				'before_widget' => '<div>',
				'after_widget' => '</div>',
				'before_title' => '<h2>',
				'after_title' => '</h2>',
			),
			array(
				'title' => 'Test Widget',
				'post' => $p,
			)
		);
		$actual = ob_get_clean();

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated_widget_lowercase_titles() {
		$p = $this->factory->post->create( array(
			'post_title' => 'Index Page',
			'post_type' => 'page',
		) );
		$p2 = $this->factory->post->create( array(
			'post_title' => 'test post',
			'post_type' => 'page',
		) );

		$expected = sprintf( file_get_contents( 'tests/populated-widget.txt' ), $p );

		ob_start();
		the_section_a_z_widget(
			array(
				'before_widget' => '<div>',
				'after_widget' => '</div>',
				'before_title' => '<h2>',
				'after_title' => '</h2>',
			),
			array(
				'title' => 'Test Widget',
				'post' => $p,
			)
		);
		$actual = ob_get_clean();

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}
}
