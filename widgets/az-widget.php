<?php
/**
 * Definition for the a-z-listing's main widget.
 * @package  a-z-listing
 */

/**
 * Definition for the AZ_Widget which displays alphabetically-ordered list of latin letters linking to the A-Z Listing page.
 */
class AZ_Widget extends WP_Widget {
	/**
	 * Register the widget's meta information.
	 */
	function AZ_Widget() {
		$this->WP_Widget('bh_az_widget', 'A-Z Site Map', array(
			'classname' => 'bh_az_widget',
			'description' => 'Alphabetised links to the A-Z site map',
		));
	}

	/**
	 * Print-out the configuration form for the widget.
	 * @param  Array $instance Widget instance as provided by WordPress core.
	 */
	function form( $instance ) {
		$title = $instance['title'];
		$titleID = $this->get_field_id( 'title' );
		$titleName = $this->get_field_name( 'title' );

		$post = isset( $instance['post'] ) ? $instance['post'] : ( isset( $instance['page'] ) ? $instance['page'] : 0 );
		$postID = $this->get_field_id( 'post' );
		$postName = $this->get_field_name( 'post' );

		echo '<div><label for="'.esc_attr( $postID ).'">Site map A-Z page</label></div>';
		wp_dropdown_pages(array(
			'id' => intval( $postID ),
			'name' => esc_html( $postName ),
			'selected' => intval( $post ),
		));

		echo '<div><label for="'.esc_attr( $titleID ).'">Title</label></div>';
		echo '<input class="widefat" id="'.esc_attr( $titleID ).'" name="'.esc_attr( $titleName ).'" type="text" placeholder="Title" value="'.esc_attr( $title ).'" />';
		echo '<p style="color: #333;">'.esc_html( __( 'Leave blank to use the title specified by the page', 'a-z-listing' ) ).'</p>';
	}

	/**
	 * Called by WordPress core. Sanitises changes to the Widget's configuration.
	 * @param  Array $new_instance the new configuration values.
	 * @param  Array $old_instance the previous configuration values.
	 * @return Array               sanitised version of the new configuration values to be saved
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post'] = (int) $new_instance['post'];
		return $instance;
	}

	/**
	 * Print the user-visible widget to the page.
	 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
	 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
	 */
	function widget( $args, $instance ) {
		the_section_az_widget( $args, $instance );
	}
}

/**
 * Print the user-visible widget to the page implentation
 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
 */
function the_section_az_widget( $args, $instance ) {
	extract( $args );

	$instance = wp_parse_args( $instance, array(
		'title' => '',
		'post' => 0,
	) );

	if ( $instance['post'] ) {
		$target = get_post( (int) $instance['post'] );
	} else {
		return;
	}

	$targeturl = get_permalink( $target->ID );

	$title = $instance['title'];
	if ( empty( $title ) ) {
		$title = $target->post_title;
	}

	$caps = range( 'A', 'Z' );
	$letters = bh__az_query( $query );

	echo $before_widget; // WPCS: XSS OK.
	echo $before_title.esc_html( $title ).$after_title; // WPCS: XSS OK.
	echo '<ul class="az-links">';
	foreach ( $caps as $letter ) {
		$extra_pre = $extra_post = '';
		if ( ! empty( $letters[ $letter ] ) ) {
			$affix = '#' == $letter ? '_' : $letter;
			$extra_pre = '<a href="'.esc_url( $targeturl.'#letter-'.$affix ).'">';
			$extra_post = '</a>';
		}
		echo '<li>';
		echo $extra_pre; // WPCS: XSS OK.
		echo '<span>'.$letter.'</span>'; // WPCS: XSS OK.
		echo $extra_post; // WPCS: XSS OK.
		echo '</li>';
	}
	echo '</ul><div class="clear empty"></div>';
	echo $after_widget; // WPCS: XSS OK.
}
