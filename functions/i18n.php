<?php
function a_z_listing_init_translations() {
	load_plugin_textdomain( 'a-z-listing' );
}
add_action( 'init', 'a_z_listing_init_translations' );
