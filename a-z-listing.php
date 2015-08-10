<?php
/*
Plugin Name: A-Z Listing
Plugin URI: http://bowlhat.net/
Description: Display an A to Z listing of posts
Version: 0.5
Author: Daniel Llewellyn
Author URI: http://bowlhat.net
License: GPLv2
*/

function bh_az_listing_activate() {
	//  activation scripts
	$dir = dirname(__FILE__)."/";
	foreach (glob($dir."activate/*.php") as $filename) {
		require_once($filename);
	}
	bh_az_listing_init();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'bh_az_listing_activate');

add_action('plugins_loaded', 'bh_az_listing_init');
function bh_az_listing_init() {
	$dir = dirname(__FILE__)."/";

	// common functions
	foreach (glob($dir."functions/common/*.php") as $filename) {
	  require_once($filename);
	}

	// functions: always present
	foreach (glob($dir."functions/*.php") as $filename) {
		require_once($filename);
	}

	// partials: only visible outside of admin
	if (!is_admin() && trim($_SERVER['SCRIPT_NAME'], "/") != "wp-login.php") {
		foreach (glob($dir."partials/*.php") as $filename) {
			require_once($filename);
		}
	}

	// locale
	$locale = get_locale();
	$lang = substr($locale, 0, 2);
	$country = substr($locale, 3, 2);

	if (is_readable($dir."languages/$lang-$country.php"))
		require_once($dir."languages/$lang-$country.php");
	else if (is_readable($dir."languages/$lang.php"))
		require_once($dir."languages/$lang.php");

	// javascripts: autoload
	if (is_admin()) {
		$glob = glob($dir."scripts/admin/*.js");
		$admin = "admin/";
	} else {
		$glob = glob($dir."scripts/*.js");
		$admin = '';
	}

	foreach ($glob as $filename) {
		$matches = array();
		preg_match("!([^/]+).js$!", $filename, $matches);
		$code = "bh-".$matches[1];
		$url = plugins_url("scripts/".$admin.$matches[1].".js", __FILE__);
		wp_enqueue_script($code, $url, array('jquery'), NULL, true);
	}

	// css: autoload
	$glob = glob($dir."*.css");

	foreach($glob as $filename) {
		$matches = array();
		preg_match("!([^/]+).css!", $filename, $matches);
		$code = "functionality-css-".$matches[1];
		$url = plugins_url($matches[1].".css", __FILE__);

		if ($matches[1] != 'admin' || is_admin()) {
			wp_enqueue_style($code, $url);
		}
	}
}

add_action('widgets_init', 'bh_az_listing_widgets');
function bh_az_listing_widgets() {
	$dir = dirname(__FILE__)."/";

	// widgets: auto register
	foreach (glob($dir."widgets/*.php") as $filename) {
		require_once($filename);

		$filename = substr($filename, 0, strlen($filename) - strlen(".php"));
		$filename = substr($filename, strrpos($filename, "/")+1);
		register_widget($filename);
	}
}
