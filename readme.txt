=== A to Z Index ===
Contributors: diddledan
Tags: a to z, a-z, archive, listing, widget
Requires at least: 3.5
Tested up to: 3.9.1
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides an A to Z index page and widget. The widget links to the index page at the appropriate letter.

== Description ==

This plugin provides a widget which aggregates all pages into an A to Z listing. The widget includes just
the letters as links to the A-Z Index page. Also provided is an implementation for the A-Z Index page.
If a letter doesn't have any pages then the widget will display the letter unlinked; likewise the index page
will omit the display for that letter entirely.

Both the Widget and Index page can be section-targeted or global-targeted. i.e. they can limit their output
to displaying only the pages below a single top-level parent page or they can display the entirety of the
site's pages.

*By default* the widget and index page become section-targeted by including them on pages below a top-level page:
e.g. if your site layout has:
`	home
	section1
		section1a
		section1b
		a-z
	section2
		section2a
		section2b
		a-z`
then placing the widget onto either section1, section1a or section1b will target the widget to displaying only children of section1.
placing the a-z index on a child of section1 will likewise limit the index page to display only children of section1.

Likewise for section2, section2a and section2b.

= NOTE =
This plugin requires modification of the theme to be able to provide the A to Z Index page. Styling (CSS) is left entirely up to the
developer or site owner.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `bh_az_listing` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php the_az_listing(); ?>` in your templates for the index page output
1. Add the A-Z Site Map widget to a sidebar or use `<?php the_az_widget(null, array('post' => get_page($id))); ?>` in your templates (the 'post' variable indicates where the A-Z Index page is located).

== Frequently Asked Questions ==

= How do I remove section targetting or limit which sections are available? =

in your theme's functions.php add the following code:

`<?php
add_filter('az_sections', 'remove_az_section_targeting');
function remove_az_section_targeting($sections) {
	return array();
}
?>`

This filter can also be used, by removing entries which are standard $post variables, to limit which top-level pages are used as section identifiers.

== Changelog ==

= 0.3 =
fixed failure to activate as reported by ml413 and verified by geoffrey (both WordPress.org usernames)
	see: https://wordpress.org/support/topic/fatal-error-when-trying-to-install-1

= 0.2 =
renamed the plugin file and packaged for release

= 0.1 =
first release
