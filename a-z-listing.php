<?php
/**
 * Plugin Name:     A-Z Listing
 * Plugin URI:      https://a-z-listing.com/
 * Description:     Display an A to Z listing of posts
 * Author:          Dani Llewellyn
 * Author URI:      https://bowlhat.net/
 * Text Domain:     a-z-listing
 * Domain Path:     /languages
 * Version:         4.3.1
 *
 * @package         A_Z_Listing
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( '\get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
define( 'A_Z_LISTING_VERSION', \get_plugin_data( __FILE__, false, false )['Version'] );

if ( ! defined( 'A_Z_LISTING_LOG' ) ) {
	define( 'A_Z_LISTING_LOG', false );
}

define( 'A_Z_LISTING_PLUGIN_FILE', __FILE__ );
define( 'A_Z_LISTING_DEFAULT_TEMPLATE', __DIR__ . '/templates/a-z-listing.php' );

if ( file_exists( __DIR__ . '/build/vendor/scoper-autoload.php' ) ) {
	require_once __DIR__ . '/build/vendor/scoper-autoload.php';
} else {
	require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . '/functions/i18n.php';
require_once __DIR__ . '/functions/health-check.php';
require_once __DIR__ . '/functions/helpers.php';
require_once __DIR__ . '/functions/styles.php';
require_once __DIR__ . '/functions/scripts.php';
require_once __DIR__ . '/functions/enqueues.php';
require_once __DIR__ . '/widgets/class-a-z-listing-widget.php';

/**
 * Initialize the plugin.
 *
 * @return void
 */
function a_z_listing_init() {
	\A_Z_Listing\Indices::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\GutenBlock::instance()->activate( __FILE__ )->initialize();

	// Shortcode handler.
	\A_Z_Listing\Shortcode::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\PostsQuery::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\TermsQuery::instance()->activate( __FILE__ )->initialize();

	// Shortcode attribute handlers.
	\A_Z_Listing\Shortcode\QueryParts\Alphabet::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\Columns::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\ColumnGap::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\ColumnWidth::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\ExcludePosts::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\ExcludeTerms::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\HideEmpty_Deprecated::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\HideEmptyTerms::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\InstanceId::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\ParentPost::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\ParentTermId::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\ParentTermSlugOrId::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\PostsTerms::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\PostType::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\SymbolsFirst::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\Taxonomy::instance()->activate( __FILE__ )->initialize();
	\A_Z_Listing\Shortcode\QueryParts\TermsTerms::instance()->activate( __FILE__ )->initialize();
}
add_action( 'init', 'a_z_listing_init', 5 );
