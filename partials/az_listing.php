<?php
function the_az_listing($query = null, $colcount = 1, $minpercol = 10, $h = 2) {
	echo get_the_az_listing($query, $colcount, $minpercol, $h);
}
function get_the_az_listing($query=null, $colcount = 1, $minpercol = 10, $h = 2) {
	$heading_level = (int) $h;
	$heading_level = ($heading_level >= 1 && $heading_level <= 7) ? $heading_level : 2;
	$caps = range('A', 'Z');
	$letters = bh__az_query($query);
	
	$ret = '<div id="letters">' . get_the_az_letters($query) .'</div>';
	$ret .= '<div id="az-slider"><div id="inner-slider">';
	
	foreach ($caps as $letter) {
		if (!empty($letters[$letter])) {
			$id = $letter;
			if ($id == '#') $id = '_';
			$ret .= '<div class="letter-section" id="letter-' . $id . '"><a name="letter-' . $id . '"></a>';
			$ret .= '<h' . $heading_level . '><span>' . $letter . '</span></h' . $heading_level . '>';
			
			$numpercol = ceil(count($letters[$letter]) / $colcount);
			
			$i = $j = 0;
			foreach ($letters[$letter] as $name => $post) {
				if ($i == 0) {
					$ret .= '<div><ul>';
				}
				$i++;$j++;
				$ret .= '<li><a href="' . get_permalink($post->ID) . '">' . $name .'</a></li>';
				if (($minpercol - $i <= 0 && $numpercol - $i <= 0) || $j >= count($letters[$letter])) {
					$ret .= '</ul></div>';
					$i = 0;
				}
			}
			$ret .= "<div class='clear empty'></div></div><!-- /letter-section -->";
		}
	}
	
	$ret .= '</div></div>';
	return $ret;
}

function the_az_letters($query = null) {
	echo get_the_az_letters($query);
}

function get_the_az_letters($query = null) {
	$caps = range('A', 'Z');
	$letters = bh__az_query($query);
	
	$ret = '<div class="az-letters"><ul>';
	$count = 0;
	foreach ($caps as $letter) {
		$count++;
		$id = $letter;
		if ($id == '#') $id = '_';
	  
		$extra_pre = $extra_post = '';
		$classes = (($count == 1) ? 'first ' : (($count == count($caps)) ? 'last ' : ''));
		$classes .= (($count % 2 == 0) ? 'even' : 'odd');
		if (!empty($letters[$letter])) {
			$extra_pre = "<a href='#letter-$id'>";
			$extra_post = "</a>";
		}
		$ret .= "<li class='$classes'>$extra_pre<span>$letter</span>$extra_post</li>";
	}
	$ret .= '</ul><div class="clear empty"></div></div>';
	return $ret;
}

function bh__az_query($query) {
	$sections = apply_filters('az_sections', get_pages(array('parent' => 0)));
	$section = bh_current_section();
	if (!in_array($section, $sections)) {
		$section = null;
	}
	
	do_action('log', 'A-Z Section', $section);
	if (!$query instanceof WP_Query) {
		$query = new WP_Query(array(
			'post_type' => 'page',
			'numberposts' => -1,
			'section' => $section,
			'nopaging' => true,
		));
	}
	
	$pages = $query->get_posts();
	
	// letters from short names
	$letters = array();
	$caps = range('A', 'Z');
	
	$short_names = array();
	foreach ($pages as $page) {
		$index_tax = apply_filters('az_additional_titles_taxonomy', '');
		$terms = array();
		if (!empty($index_tax)) {
			$terms = array_filter(wp_get_object_terms($page->ID, $index_tax));
		}
		if (!empty($terms)) {
			foreach ($terms as $term) {
				$A = strtoupper(substr($term->name, 0, 1));
				if (!in_array($A, $caps)) $A = '#';
				$letters[$A][$term->name] = $page;
			}
		} else {
			$A = strtoupper(substr(get_the_title($page->ID), 0, 1));
			if (!in_array($A, $caps)) $A = '#';
			$letters[$A][get_the_title($page->ID)] = $page;
		}
	}
	
	$letters = array_filter($letters);
	
	if (!empty($letters['#'])) $caps[] = '#';
	
	//  sort each letter by name
	foreach ($caps as $letter) {
		if (!empty($letters[$letter])) {
			ksort($letters[$letter], SORT_STRING);
		}
	}
	
	return $letters;
}