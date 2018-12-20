## Full Changelog

### 1.9.1
Add CSS classes to letters indicating presence of posts or not:
  
* `has-posts` allows styling of letters that have posts visible in the listing
* `no-posts` allows styling of letters that do not have any posts visible in the listing
  
You can use these classes to hide letters that have no posts by including the following CSS rule:
`.az-letters ul.az-links li.no-posts {
    display: none;
}`

### 1.9.0
* Fix multi-column example template
* Update multi-column styles to include display:grid support
* Add back-to-top link
* Add server system requirements to readme
* Add PHP section to readme including link to API Reference

### 1.8.0
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

### 1.7.2
* Bugfix: Previous release broke the shortcode

### 1.7.1
* Add additional filters allowing for hyphens or underscores to be used when defining. The readme.txt incorrectly used then-unsupported names with hyphens in examples so now we support both.
* Add numbers="before" and numbers="after" in shortcode

### 1.7.0
* Add support for taxonomy term listings to the shortcode
* Add support for filtering by taxonomy terms to the shortcode

### 1.6.5
* Regression fix for widget accessing WP_Post object as array

### 1.6.4
* Bugfix for accessing array as object PHP Warning. Reported by @babraham76

### 1.6.2
* Bugfix for more complex templates - accessing post thumbnails failed.

### 1.6.1
* Regression fix: Notice was emitted by PHP about invalid variable. This was cosmetic only, and had no impact on functionality.

### 1.6.0
* Fix bug of case sensitity in listings order
* Better warning of deprecated functions when called by other plugins or themes

### 1.5.4
* Fix post links when using an alternative titles taxonomy (discovered by [bugnumber9](https://profiles.wordpress.org/bugnumber9))
* Ensure that we don't access rogue objects. Warnings and errors in 1.5.3 are squashed now.
* Verified that [tests](https://travis-ci.org/bowlhat/wp-a-z-listing) pass correctly before releasing this version.

### 1.5.3
* Regression in 1.5.2 causing empty listing is fixed

### 1.5.2
* Regression fix for styling loading - seems the widget code was still causing issues
* Add inline PHPdoc to all functions and custom filters

### 1.5.1
* Fix multiple post-types support for shortcode
* Update documentation to explain how to show multiple post-types with the shortcode

### 1.5.0
* Ensure styling is loaded correctly
* Ensure styling works correctly when using the multi-column template

### 1.4.1
* Fix warning introduced by 1.4.0 about implicit coercion between WP_Post and string

### 1.4.0
* Add support for passing a WP_Post object instead of an ID to the widget function
* Fix widget config not saving post-type parameter
* Fix warning of incorrect usage of `has_shortcode()` function
* Fix section-targeting to work as described

### 1.3.1
* Fix broken admin pages caused by 1.3.0

### 1.3.0
* Added targeted stylesheet loading to enqueue only on pages where the short-code is active
* Further improved default stylesheet loading

### 1.2.0
* Changed default to apply the in-built styles, unless overridden

### 1.1.0
* Minor refactoring to remove unused variables
* Fix some Code-Smell (phpcs)

### 1.0.1
* BUGFIX: lower-case titles missing

### 1.0.0
* BREAKING CHANGE: Refactored several function names. If you have written your own template/loop you will need to adapt your code. See the readme's Theming section for details.
* Added `post-type` attribute into the shortcode to display for post-types other than pages.
* Minor code cleanup.

### 0.8.0
* Standardised on naming convention of `*_a_z_*` in function names, e.g. `get_the_a_z_listing()`, rather than the former `*_az_*` names, e.g. `get_the_az_listing()`.
* Converted version numbering to semver style.
* Fixed the in-built styling.
* Added filter to determine whether to apply in-built styles in addition to hidden setting: `set_option( a-z-listing-add-styling', true );`.
* Added taxonomy terms list support.

### 0.7.1
* Fix potential XSS vector.

### 0.7
* rebuilt most of the logic in preparation for more functionality.
* added template/theming capability (BIG change!)
* Added option to choose to apply default styling of the widget.

### 0.6
* STYLING BREAKING change: the widget's CSS class is changed from `bh_az_widget` to `a-z-listing-widget`. Please update your CSS accordingly.
* Conformed to WordPress coding style guidelines.
* Updated widget class to call php5-style constructor.
* Applied internationalisation (i18n).
* Added testsuite.

### 0.5
* Added new shortcode to display the index page.

### 0.4
* fixed file locations causing failure to load.

### 0.3
* fixed failure to activate as reported by [ml413](https://profiles.wordpress.org/ml413) and verified by [creativejuiz](https://wordpress.org/support/users/creativejuiz/); [reference](https://wordpress.org/support/topic/fatal-error-when-trying-to-install-1).

### 0.2
* renamed the plugin file and packaged for release

### 0.1
* first release
