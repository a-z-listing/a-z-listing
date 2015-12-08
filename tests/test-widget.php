<?php
class AZ_Widget_Tests extends WP_UnitTestCase {
	function test_widget() {
		$p = $this->factory->post->create( array( 'post_title' => 'Index Page', 'post_type' => 'page' ) );
		$this->expectOutputString( sprintf( file_get_contents( 'tests/default-widget.txt' ), $p ) );
		the_section_az_widget(
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
	}
	function test_populated_widget() {
		$p = $this->factory->post->create( array( 'post_title' => 'Index Page', 'post_type' => 'page' ) );
		$p2 = $this->factory->post->create( array( 'post_title' => 'Test Post', 'post_type' => 'page' ) );
		$this->expectOutputString( sprintf( file_get_contents( 'tests/populated-widget.txt' ), $p ) );
		the_section_az_widget(
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
	}
}
