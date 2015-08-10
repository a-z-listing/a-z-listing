<?php
class az_widget extends WP_Widget {
	function az_widget() {
		$this->WP_Widget('bh_az_widget', 'A-Z Site Map', array(
			'classname' => 'bh_az_widget',
			'description' => 'Alphabetised links to the A-Z site map',
		));
	}
	
	function form($instance) {
		$title = esc_attr($instance['title']);
		$titleID = $this->get_field_id('title');
		$titleName = $this->get_field_name('title');
		
		$post = isset($instance['post']) ? (int) $instance['post'] : (isset($instance['page']) ? (int) $instance['page'] : 0);
		$postID =      $this->get_field_id('post');
		$postName =    $this->get_field_name('post');
		
		// write the form
		echo "<div><label for='$postID'>Site map A-Z page</label></div>";
		wp_dropdown_pages(array(
			'id' => $postID,
			'name' => $postName,
			'selected' => $post,
		));
		
		echo "<div><label for='$titleID'>Title</label></div>";
		echo "<input class='widefat' id='$titleID' name='$titleName' type='text' placeholder='Title' value='$title' />";
		echo "<p style='color: #333;'>Leave blank to use the page's title</p>";
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post'] = (int) $new_instance['post'];
		return $instance;
	}
	
	function widget($args, $instance) {
		the_section_az_widget($args, $instance);
	}
}
	
function the_section_az_widget($args, $instance) {
	extract($args);
	
	$instance = wp_parse_args($instance, array(
		'title' => '',
		'post' => 0
	));
	
	if ($instance['post']) {
		$id = (int) $instance['post'];
		$target = get_post($id);
	} else {
		// somehow deduce the right target page...
	}
	$targeturl = get_permalink($target->ID);
	
	$title = $instance['title'];
	if (empty($title)) {
		$title = $target->post_title;
	}
	
	// letters from short names
	$caps = range('A', 'Z');
	$letters = bh__az_query($query);
	
	//  write the widget
	echo $before_widget.$before_title.esc_html($title).$after_title."<ul class='az-links'>";
	foreach ($caps as $letter) {
		$extra_pre = $extra_post = '';
		if (!empty($letters[$letter])) {
			$affix = $letter == '#' ? '_' : $letter;
			$extra_pre = "<a href='$targeturl#letter-$affix'>";
			$extra_post = "</a>";
		}
		echo "<li>$extra_pre<span>$letter</span>$extra_post</li>";
	}
	echo "</ul><div class='clear empty'></div>".$after_widget;
}
