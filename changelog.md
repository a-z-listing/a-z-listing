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
