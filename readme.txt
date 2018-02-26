=== A-Z Listing ===
Contributors: diddledan
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N7QFVVD4PZVFE
Tags: a to z, a-z, archive, listing, widget, index
Requires at least: 3.5
Tested up to: 4.9
Stable tag: 1.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides an A to Z index page and widget. The widget links to the index page at the appropriate letter.

== Description ==

[![Build Status](https://travis-ci.org/bowlhat/wp-a-z-listing.svg?branch=master)](https://travis-ci.org/bowlhat/wp-a-z-listing)

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
placing the a-z index on a child of section1 will likewise limit the index page to display only children of section1.

Likewise for section2, section2a and section2b.

= Your server will need =

1. PHP 5.3 is the minimum version supported. Preferably use the most-recent version of PHP your host offers; PHP 7.0 is ideal.
1. The plugin requires `mbstring` turned-on in your PHP installation. Without this feature WordPress will issue a WSOD (White Screen of Death).

== Installation ==

This section describes how to install the plugin and get it working.

= Your server will need =

1. PHP 5.3 is the minimum version supported. Preferably use the most-recent version of PHP your host offers; PHP 7.0 is ideal.
1. The plugin requires `mbstring` turned-on in your PHP installation. Without this feature WordPress will issue a WSOD (White Screen of Death).

= Instructions =
1. Upload the `a-z-listing` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php the_a_z_listing(); ?>` in your templates for the index page output (see the **php** section of this document for details) or use the `a-z-listing` shortcode (see the **shortcode** section of this document for details).
1. Add the A-Z Site Map widget to a sidebar or use `<?php the_a_z_widget( null, array( 'post' => get_page( $id ) ) ); ?>` in your templates (see the **php** section of this document for details).

== Shortcode ==

The plugin supplies a shortcode for the full A-Z listing allowing use without modifying your theme's templates.

Basic usage is as follows:

    [a-z-listing]

To specify a post-type to display instead of `page` then use, e.g. `Posts`:

    [a-z-listing post-type="post"]

To filter the posts by a term from a taxonomy:

    [a-z-listing taxonomy="category" terms="my-term-slug"]

To show terms from a taxonomy instead of posts and pages, e.g. Terms from the `Categories` taxonomy:

    [a-z-listing taxonomy="category" display="terms"]

To override the alphabet used by the plugin:

    [a-z-listing alphabet="Aa,Bb,Cc,Dd,Ee,Ff,Gg,Hh,Ii,Jj,Kk,Ll,Mm,Nn,Oo,Pp,Qq,Rr,Ss,Tt,Uu,Vv,Ww,Xx,Yy,Zz"]

To add numbers to the listing:

    [a-z-listing numbers="after"]

The numbers can also be shown before the alphabet:

    [a-z-listing numbers="before"]

You can group the numbers into a single collection for all posts beginning with a numeral:

    [a-z-listing numbers="after" grouping="numbers"]

To group the alphabet letters into a range:

    [a-z-listing grouping="3"]

**The arguments are all optional.**

* `post-type`: sets the listing to show a specific post-type.
  - Default value: `page`
  - You may specify multiple post-types by separating with commas (`,`) e.g. `post-type="page,post"`
* `taxonomy`: does nothing by itself, see the combinations below
  - Default value: unset
  - Uses the `slug` of the taxonomy
  - When combined with:
    - `terms`, will filter your posts by the terms you set there, which appear in the taxonomy set here
    - `display="terms"`, will switch from displaying post titles to displaying the names of terms from the taxonomy specified
* `terms`: sets the taxonomy terms for filtering posts
  - Default value: unset
  - The taxonomy must also be specified in `taxonomy`
  - Uses the `slug` of the term(s)
  - Multiple terms can be specified by separating with commas: `,`
* `display`: specifies whether to display posts or terms from a taxonomy
  - Default value: unset
  - Can be set to either `posts` or `terms`.
  - Any value other than unset, `posts` or `terms` will default to displaying posts
* `numbers`: appends or prepends numerals to the alphabet
  - Default value: unset
  - Can be set to either `before` or `after`.
  - Any value other than unset, `before` or `after` will default to **appending** numerals to the alphabet
* `grouping`: tells the plugin if and how to group the alphabet
  - Default value: unset
  - Can be set to any positive number higher than `1` or the value `numbers`
  - Any value other than a positive number or the value `numbers` will default to disabling all grouping functionality
  - When set to a number higher than `1` the listing will group letters together into ranges
    - For example, if you chose `3` then a latin alphabet will group together `A`, `B`, and `C` into `A-C`. Likewise for `D-F`, `G-I` and so-on
    - When using this setting, if numbers are also shown via the `numbers="before"` or `numbers="after"` attribute then they will be shown as a single separate group `0-9`
  - When set to the value `numbers` it will group numerals into a single group `0-9`
    - This requires the numbers to be displayed via the `numbers="before"` or `numbers="after"` attributes
* `alphabet`: allows you to override the alphabet that the plugin uses
  - Default value: unset.
  - When this attribute is unset, the plugin will either use the untranslated default, or if [glotpress](https://translate.wordpress.org/projects/wp-plugins/a-z-listing) includes a translation for your site's language as set in `Admin -> Settings -> Site Language` it will use that translation.
  - The current untranslated default is: `AÁÀÄÂaáàäâ,Bb,Cc,Dd,EÉÈËÊeéèëê,Ff,Gg,Hh,IÍÌÏÎiíìïî,Jj,Kk,Ll,Mm,Nn,OÓÒÖÔoóòöô,Pp,Qq,Rr,Ssß,Tt,UÚÙÜÛuúùüû,Vv,Ww,Xx,Yy,Zz`
  - Accepts a single line of letters/symbols, which need to be separated via the comma character `,`
  - Including more than one letter/symbol in each group will display posts starting with any of those under the same section
  - The first letter/symbol in each group is used as the group's heading when displayed on your site

== PHP ==

= Synopsis =

`the_a_z_listing( $query );` or `get_the_a_z_listing( $query );`

`$query` is any valid [`WP_Query`](https://codex.wordpress.org/Class_Reference/WP_Query) array definition, a `WP_Query` object formed from `new WP_Query();`, or a single string containing a taxonomy which will switch the listing to display terms from that taxonomy instead of posts.

= Reference =

Full API documentation is available at [A-Z-Listing Reference](https://a-z-listing.com/reference/)

== Multi Column Output ==

If you want the multi-column output support, you need to copy the file `a-z-listing-multi-column.example.php` from the plugin inside the `templates` directory to your theme. The file needs to also be renamed to `a-z-listing.php` when copied to your theme. The **Templates and Theming** section of this Document details the functions used within templates and The Loop process this plugin follows.

== Templates and Theming ==

The plugin allows the site owner, developer, or themer to provide custom templates for the A-Z Listing output.

*NOTE: These functions have changed name and method of access in 1.0.0. We have dropped the `_a_z_` moniker in the function name and within the template file they are accessed via the `$a_z_listing` object.* The former function names are still accessible, but are largely deprecated.

To add a template to your theme, you need a file similar to the `templates/a-z-listing.php` file in the plugin folder. Your copy needs to be placed within your theme at the theme root directory and called `a-z-listing.php` or `a-z-listing-section.php` (where `-section` is an optional top-level page slug for the section-targeting feature).

= The Loop =

The theme system this plugin implements is *very* similar to [the standard WordPress loop](https://codex.wordpress.org/The_Loop), with a few added bits.

Important functions to use in your template are as follows:

* `$a_z_query->the_letters()` prints the full alphabet, and links the letters that have posts to their section within the index page.
* `$a_z_query->have_letters()` returns true or false depending on whether there are any letters left to loop-through. This is part of the Letter Loop.
* `$a_z_query->have_items()` this behaves very similarly to Core's `have_posts()` function. It is part of the Item Loop.
* `$a_z_query->the_letter()` similar to Core's `the_post()`, this will set-up the next iteration of the A-Z Listing's Letter Loop. This needs to wrap-around the Item Loop.
* `$a_z_query->the_item()` similar to Core's `the_post()`, this will set-up the next iteration of the A-Z Listing's Item Loop, the same way the normal WordPress Loop works. This needs to be _within_ the Letter Loop.

When you are within the Item Loop you can utilise all in-built WordPress Core post-related functions such as `the_content()`. Note that titles and permalinks have helper functions to cope with the A-Z Listing showing taxonomy terms (see the next section).

I advise that you start with a copy of the default template or the multi-column template when customizing your own version. The supplied templates show the usage of most of the functions this plugin provides.

= Helper functions =

The plugin supports displaying taxonomy terms as though each term were a post. This means that the WordPress functions related to posts such as `the_title()` and `the_permalink()` are unreliable. We have therefore added helper functions which will return or print the correct output for the item.

*NOTE: These functions have changed name and method of access in 1.0.0. We have dropped the _a_z_ moniker in the function name and within the template file they are accessed via the `$a_z_listing` object.* The previous function names are still accessible, but are largely deprecated.

These helper functions cope with the dual usage of the plugin supporting both `WP_Query`-based (returning `WP_Post` objects) and Taxonomy Terms (returning `WP_Term` objects) listings. These are:

* `$a_z_query->the_title()` - prints the current item's Title
* `$a_z_query->get_the_title()` returns the current item's Title but does not print it directly
* `$a_z_query->the_permalink()` prints the current item's Permalink
* `$a_z_query->get_the_permalink()` returns the current item's Permalink but does not print it directly

== Frequently Asked Questions ==

= How do I show posts of a different post-type (not pages) or multiple post-types (e.g. posts AND pages) =

This can be achieved using the shortcode or PHP.

**Shortcode method**

    [a-z-listing post-type="your-post-type-slug"]

*Multiple types*

For multiple post-types just separate them with a comma.

    [a-z-listing post-type="type1-slug,type2-slug"]

**PHP method**

PHP code needs to be added to your theme files, and cannot be used as post or page content in the way that a shortcode can.

    <?php
    the_a_z_listing( array(
        'post_type' => 'your-post-type-slug'
    ) );
    ?>

*Multiple post-types*

    <?php
    the_a_z_listing( array(
        'post_type' => array( 'type1-slug', 'type2-slug' )
    ) );
    ?>

*NOTE*

The argument to `the_a_z_listing()` is an [array](http://php.net/manual/en/language.types.array.php) and takes the same parameters as [WP_Query](https://codex.wordpress.org/Class_Reference/WP_Query)

*The code above needs to be within a php block which is denoted by the `<?php` and `?>` pair. Depending on your theme, you might not need the opening and closing php tags shown in the above snippet; if that is the case, you are free to omit them in your code.*

= How do I show posts from a specific category only =

This can be achieved using the shortcode or PHP.

**Shortcode method**

    [a-z-listing taxonomy="taxonomy-slug" terms="term-slug"]

*Multiple terms*

For multiple terms just separate them with a comma.

    [a-z-listing taxonomy="taxonomy-slug" terms="term1-slug,term2-slug"]

**PHP method**

PHP code needs to be added to your theme files, and cannot be used as post or page content in the way that a shortcode can.

    <?php
    the_a_z_listing( array(
        'tax_query' => array(
            'taxonomy' => 'your-taxonomy-slug',
            'field' => 'slug',
            'terms' => array( 'term1-slug', 'term2-slug' )
        )
    ) );
    ?>

Any number of terms can be added to the `terms` [array](http://php.net/manual/en/language.types.array.php), including one or none.

The argument to `the_a_z_listing()` is an [array](http://php.net/manual/en/language.types.array.php) and takes the same parameters as [WP_Query](https://codex.wordpress.org/Class_Reference/WP_Query)

*The code above needs to be within a php block which is denoted by the `<?php` and `?>` pair. Depending on your theme, you might not need the opening and closing php tags shown in the above snippet; if that is the case, you are free to omit them in your code.*

= How do I show terms from a taxonomy instead of posts =

This can be achieved using the shortcode or PHP.

**Shortcode method**

    [a-z-listing taxonomy="taxonomy-slug" display="terms"]

The taxonomy parameter takes a single taxonomy's slug, e.g. `category` or `post_tag`.

The `display="terms"` attribute is required to display taxonomy terms instead of posts.

**PHP method**

PHP code needs to be added to your theme files, and cannot be used as post or page content in the way that a shortcode can.

    <?php
    the_a_z_listing( 'taxonomy-slug' );
    ?>

The argument to `the_a_z_listing()` is a [string](http://php.net/manual/en/language.types.string.php) and contains the slug of a single taxonomy, e.g. `category` or `post_tag`.

*The code above needs to be within a php block which is denoted by the `<?php` and `?>` pair. Depending on your theme, you might not need the opening and closing php tags shown in the above snippet; if that is the case, you are free to omit them in your code.*

= How do I remove section targeting or limit which sections are available? =

In your theme's functions.php add the following code:

    <?php
    add_filter( 'a-z-listing-sections', '__return_empty_array' );
    ?>

This filter can also be used, by removing entries which are standard $post variables, to limit which top-level pages are used as section identifiers.

*If there is code already in your functions.php then add just the lines between `<?php` and `?>` on the line directly after the very first instance of `<?php`.*

= I am not using the short-code so the styles are not working, can I still use the in-built styles without the short-code? =

Yes you can. This needs the following code added to your theme's functions.php. We purposely only display the stylesheet on pages where the short-code is active.

    <?php
    add_action( 'wp', 'a_z_listing_force_enable_styles', 99 );
    ?>

*If there is code already in your functions.php then add just the lines between `<?php` and `?>` on the line directly after the very first instance of `<?php`.*

The sidebar widget styling also works in a similar manner, and will also respond to the same code above to forcibly enable it.

You can add code which detects the page which the user is browsing and only enable the override on that page so that network requests are kept to a minimum (this is the same reason we detect usage of the short-code).

    <?php
    add_action( 'wp', 'your_override_wrapper_function', 99 );
    function your_override_wrapper_function() {
        if ( ! is_page( 'your-a-z-listing-page-slug-or-ID' ) ) { // ID is numeric, slug is a string.
            return; // we don't want to run for anything except the page we're interested in.
        }
        a_z_listing_force_enable_styles(); // this is the page we want, so run the function to enqueue the styles.
    }
    ?>

*If there is code already in your functions.php then add just the lines between `<?php` and `?>` on the line directly after the very first instance of `<?php`.*

= How do I disable the in-built styling? =

In your theme's functions.php add the following code:

    <?php
    add_filter( 'a-z-listing-add-styling', '__return_false' );
    ?>

*If there is code already in your functions.php then add just the lines between `<?php` and `?>` on the line directly after the very first instance of `<?php`.*

== Screenshots ==

1. An example of the index listing page.
2. The Widget is shown here.

== Changelog ==

= 1.9.1 =
Feature Request:
Add CSS classes to letters indicating presence of posts or not:
  
* `has-posts` allows styling of letters that have posts visible in the listing
* `no-posts` allows styling of letters that do not have any posts visible in the listing
  
You can use these classes to hide letters that have no posts by including the following CSS rule:
`.az-letters ul.az-links li.no-posts {
    display: none;
}`

= 1.9.0 =
* Fix multi-column example template
* Update multi-column styles to include display:grid support
* Add back-to-top link
* Add server system requirements to readme
* Add PHP section to readme including link to API Reference

= 1.8.0 =
* Add extra shortcode attributes:
  * `numbers`: appends or prepends numerals to the alphabet
    - Default value: unset
    - Can be set to either `before` or `after`.
    - Any value other than unset, `before` or `after` will default to **appending** numerals to the alphabet
  * `grouping`: tells the plugin if and how to group the alphabet
    - Default value: unset
    - Can be set to any positive number higher than `1` or the value `numbers`
    - Any value other than a positive number or the value `numbers` will default to disabling all grouping functionality
    - When set to a number higher than `1` the listing will group letters together into ranges
      - For example, if you chose `3` then a latin alphabet will group together `A`, `B`, and `C` into `A-C`. Likewise for `D-F`, `G-I` and so-on
      - When using this setting, if numbers are also shown via the `numbers="before"` or `numbers="after"` attribute then they will be shown as a single separate group `0-9`
    - When set to the value `numbers` it will group numerals into a single group `0-9`
      - This requires the numbers to be displayed via the `numbers="before"` or `numbers="after"` attributes
  * `alphabet`: allows you to override the alphabet that the plugin uses
    - Default value: unset.
    - When this attribute is unset, the plugin will either use the untranslated default, or if [glotpress](https://translate.wordpress.org/projects/wp-plugins/a-z-listing) includes a translation for your site's language as set in `Admin -> Settings -> Site Language` it will use that translation.
    - The current untranslated default is: `AÁÀÄÂaáàäâ,Bb,Cc,Dd,EÉÈËÊeéèëê,Ff,Gg,Hh,IÍÌÏÎiíìïî,Jj,Kk,Ll,Mm,Nn,OÓÒÖÔoóòöô,Pp,Qq,Rr,Ssß,Tt,UÚÙÜÛuúùüû,Vv,Ww,Xx,Yy,Zz`
    - Accepts a single line of letters/symbols, which need to be separated via the comma character `,`
    - Including more than one letter/symbol in each group will display posts starting with any of those under the same section
    - The first letter/symbol in each group is used as the group's heading when displayed on your site
* Bugfix: Shortcode to display taxonomy terms wouldn't also display numbers groups. Hat-tip to @sotos for the report.

= 1.7.2 =
* Add additional filters allowing for hyphens or underscores to be used when defining. The readme.txt incorrectly used then-unsupported names with hyphens in examples so now we support both.
* Add numbers="before" and numbers="after" in shortcode
* Add support for taxonomy term listings to the shortcode
* Add support for filtering by taxonomy terms to the shortcode

= Previous =
See the file called `changelog.md` for the full release history.
