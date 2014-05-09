<?php

function the_az_listing($query=null, $colcount = 1, $minpercol = 10, $heading_level = 2) {
	$caps = range('A', 'Z');
	$letters = bh__az_query($query);
	?>
	<div id='letters'><?php the_az_letters($query); ?></div>
	<div id='az-slider'>
		<div id='inner-slider'><?php
		foreach ($caps as $A) {
			if (!empty($letters[$A])) {
				//$colcount = $backupcolcount;
				$id = $A;
				if ($id == '#') $id = '_';
				echo "<div class='letter-section' id='letter-$id'><a name='letter-$id'></a><h$heading_level><span>$A</span></h$heading_level>";
				
				// $numpercol = 0;
			  
				// do {
				//   $numpercol = ceil(count($letters[$A]) / $colcount);
				//   do_action('log', 'number of items per column in-loop', $A, $numpercol, $colcount, $minpercol);
				//   if ($numpercol >= $minpercol) break;
				//   $colcount--;
				// } while ($numpercol < $minpercol && $colcount > 1);
			  
				// do_action('log', 'number of items per column', $numpercol, $colcount);
				
				$numpercol = ceil(count($letters[$A]) / $colcount);
				
				$i = $j = 0;
				foreach ($letters[$A] as $name => $post) {
					if ($i == 0) {
						echo '<div><ul>';
					}
					$i++;$j++;
					?>
						<li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $name; ?></a></li>
					<?php
					if (($minpercol - $i <= 0 && $numpercol - $i <= 0) || $j >= count($letters[$A])) {
						echo '</ul></div>';
						$i = 0;
					}
				}
				echo "<div class='clear empty'></div></div><!-- /letter-section -->";
			}
		}
		?></div>
	</div>
	<?php
}

function the_az_letters($query = null) {
	$caps = range('A', 'Z');
	$letters = bh__az_query($query);
	
	echo '<div class="az-letters"><ul>';
	$count = 0;
	foreach ($caps as $A) {
		$count++;
		$id = $A;
		if ($id == '#') $id = '_';
	  
		$extra_pre = $extra_post = '';
		$classes = (($count == 1) ? 'first ' : (($count == count($caps)) ? 'last ' : ''));
		$classes .= (($count % 2 == 0) ? 'even' : 'odd');
		if (!empty($letters[$A])) {
			$extra_pre = "<a href='#letter-$id'>";
			$extra_post = "</a>";
		}
		echo "<li class='$classes'>$extra_pre<span>$A</span>$extra_post</li>";
	}
	echo '</ul><div class="clear empty"></div></div>';
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
	foreach ($caps as $A)
		if (!empty($letters[$A]))
			ksort($letters[$A], SORT_STRING);
	
	return $letters;
}