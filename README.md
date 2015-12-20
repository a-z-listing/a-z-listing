# A to Z Index #
- **Contributors:** diddledan
- **Tags:** a to z, a-z, archive, listing, widget, index
- **Requires at least:** 3.5
- **Tested up to:** 4.4
- **Stable tag:** 0.7
- **License:** GPLv2 or later
- **License URI:** http://www.gnu.org/licenses/gpl-2.0.html

Provides an A to Z index page and widget. The widget links to the index page at the appropriate letter.

## Description ##

[![Build Status](https://travis-ci.org/diddledan/wp-a-z-listing.svg?branch=master)](https://travis-ci.org/diddledan/wp-a-z-listing)

This plugin provides a widget which aggregates all pages into an A to Z listing. The widget includes just
the letters as links to the A-Z Index page. Also provided is an implementation for the A-Z Index page.
If a letter doesn't have any pages then the widget will display the letter unlinked; likewise the index page
will omit the display for that letter entirely.

Both the Widget and Index page can be section-targeted or global-targeted. i.e. they can limit their output
to displaying only the pages below a single top-level parent page or they can display the entirety of the
site's pages.

*By default* the widget and index page become section-targeted by including them on pages below a top-level page:
e.g. if your site layout has:

    home
        section1
        section1a
        section1b
        a-z
    section2
        section2a
        section2b
        a-z

then placing the widget onto either section1, section1a or section1b will target the widget to displaying only children of section1.
Placing the a-z index on a child of section1 will likewise limit the index page to display only children of section1.

Likewise for section2, section2a and section2b.

### NOTE ###
Styling (CSS) is left entirely up to the developer or site owner.

## Installation ##

This section describes how to install the plugin and get it working.

1. Upload the `a-z-listing` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php the_az_listing(); ?>` in your templates for the index page output or use the `a-z-listing` shortcode.
1. Add the A-Z Site Map widget to a sidebar or use `<?php the_az_widget(null, array('post' => get_page($id))); ?>` in your templates (the 'post' variable indicates where the A-Z Index page is located).

## Shortcode ##

New for 0.5 is a shortcode for the full A-Z listing allowing use without modifying your theme's templates.

The usage is as follows:

    [a-z-listing column-count=1 minimum-per-column=10 heading-level=2]

The arguments are all optional with their defaults shown above, which will be used when omitted.

- `column-count` defines how many columns of titles you want displayed for each alphabet letter.
- `minimum-per-column` is used to indicate the breakpoint number of posts before using an additional column. This prevents situations such as specifying 3 columns but having only three posts for a particular letter causing one post to be shown in each of the three columns. When using this feature we will keep all three posts in the first column even though we have also specified to use three columns because three is less-than the ten we set as the minimum breakpoint. Once the breakpoint is reached the columns will grow together.
- `heading-level` tells the code what HTML "heading number" to use, e.g. h1, h2, h3 etc. This is primarily to allow you to set things for correct accessibility as this plugin cannot anticipate how different themes will set-up their heading structure.

## Theming ##

New for 0.7 is themeability! This allows the site owner or theme developer to provide custom templates for the A-Z Listing output.

To add a template to your theme, you need a file similar to the `templates/a-z-listing.php` file in the plugin folder. Your copy needs to be placed within your theme at the theme root directory and called `a-z-listing.php` or `a-z-listing-section.php` (where `-section` is an optional top-level page slug for the section-targeting feature).

The theme system this plugin implements is very similar to the standard WordPress loop, with a few added bits.

Important functions to use in your template are as follows:

- `the_az_letters()` outputs the full alphabet, and links the letters that have posts to their section within the index page.
- `have_a_z_letters()` returns true or false depending on whether there are any letters left to loop-through. This is part of the Letter Loop.
- `have_a_z_posts()` this behaves very similarly to Core's `have_posts()` function. It is part of the Post Loop.
- `the_a_z_letter()` similar to Core's `the_post`, this will set-up the next iteration of the A-Z Listing's Letter Loop. This needs to wrap-around the Post Loop.
- `the_a_z_post()` similar to Core's `the_post`, this will set-up the next iteration of the A-Z Listing's _Post_ Loop. This needs to be _within_ the Letter Loop.

When you are within the Post Loop you can utilise all in-built WordPress Core post-related functions such as `the_title()`, `the_permalink`, `the_content`, etc.

## Frequently Asked Questions ##

### How do I remove section targetting or limit which sections are available? ###

In your theme's functions.php add the following code:

    <?php
    add_filter('az_sections', 'remove_az_section_targeting');
    function remove_az_section_targeting($sections) {
        return array();
    }
    ?>

This filter can also be used, by removing entries which are standard $post variables, to limit which top-level pages are used as section identifiers.

## Changelog ##

### 0.7 ###
- rebuilt most of the logic in preparation for more functionality.
- added template/theming capability (BIG change!)

### 0.6 ###
- STYLING BREAKING change: the widget's CSS class is changed from bh_az_widget to a-z-listing-widget. Please update your CSS accordingly.
- Conformed to WordPress coding style guidelines.
- Updated widget class to call php5-style constructor.
- Applied internationalisation (i18n).
- Added testsuite.

### 0.5 ###
- Added new shortcode to display the index page.

### 0.4 ###
- fixed file locations causing failure to load.

### 0.3 ###
- fixed failure to activate as reported by ml413 and verified by geoffrey (both WordPress.org usernames); see: https://wordpress.org/support/topic/fatal-error-when-trying-to-install-1

### 0.2 ###
- renamed the plugin file and packaged for release

### 0.1 ###
- first release
