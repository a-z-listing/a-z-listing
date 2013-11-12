<?php
// borrowed from Marcus Downing: http://profiles.wordpress.org/marcusdowning/

if (!function_exists('bh_get_page_path')) {
	function bh_get_page_path(&$p = null, $current = false) {
	  if (empty($p) && $current) {
		global $post, $bh_hierarchy;
		if (!empty($bh_hierarchy)) {
		  $h = $bh_hierarchy;
		  $p = array_pop($h);
		  if (DEBUG_PATHS) do_action('log', 'get_page_path: Reverting to hierarchy top', $p);
		} else if (!is_search()) {
		  $p = $post;
		  if (DEBUG_PATHS) do_action('log', 'get_page_path: Reverting to global post', $post);
		}
	  }
	  if (is_object($p) && !empty($p->_page_path)) {
		if (DEBUG_PATHS) do_action('log', 'get_page_path: Stored page path', $p->_page_path);
		return $p->_page_path;
	  }
	  if (!empty($p) && $p->post_type == 'page') {
		$page_id = select_post_id($p);
		$path = get_page_uri($page_id);
		if (DEBUG_PATHS) do_action('log', 'get_page_path: Page URI', $path);
	  }
	  if (empty($path) || is_numeric($path)) {
		if (!empty($p) || !is_search()) {
		  $permalink = get_permalink($p);
		  $path = substr(get_permalink(select_post_id($p)), strlen(get_settings('home')));
		  $path = trim($path, "/");
		  if (DEBUG_PATHS) do_action('log', 'get_page_path: Permalink', $path);
		}
	  }
	  if ($current && empty($path)) {
		$path = $_SERVER['REQUEST_URI'];
		$path = preg_replace("!^https?://[^/]+/!i", "", $path);
		$path = preg_replace("!\?.*$!i", "", $path);
		$path = trim($path, '/');
		if (DEBUG_PATHS) do_action('log', 'get_page_path: Hard URI', $path);
	  }
	  if (is_object($p)) $p->_page_path = $path;
	  return $path;
	}
}
//  Current page's section

if (!function_exists('bh_current_section')) {
	function bh_current_section(&$p = null, $depth = 0, $current = true) {
	  if (is_object($p) && !empty($p->_current_section))
		return $p->_current_section;
	  $path = get_page_path($p, $current);
	  if (DEBUG_PATHS) do_action('log', 'current_section: Current path', $path);
	  $parts = explode("/", $path);
	  $parts = array_values(array_filter($parts));
	   
	  while (count($parts) <= $depth) $depth--;
	  if ($depth == -1)
		$section = "home";
	  else
		$section = $parts[$depth];
	
	  $section2 = apply_filters('bh_post_section', $section, $p);
	  if (DEBUG_PATHS) do_action('log', 'current_section: Page section', $path, $section, $section2);
	  if (is_object($p)) $p->_current_section = $section2;
	  return $section2;
	}
}