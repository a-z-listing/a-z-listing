<?php
if ( ! function_exists( 'bh_select_post_id' ) ) {
	function bh_select_post_id( $p = null ) {
		if ( is_null( $p ) ) {
			global $post;
			$p = $post;
		}

		if ( is_string( $p ) ) {
			$p = get_page_by_path( $p );
		}

		if ( is_array( $p ) ) {
			$p = (object) $p;
		}

		if ( is_object( $p ) ) {
			if ( 'nav_menu_item' === $p->post_type ) {
				$p = $p->object_id;
			} else {
				$p = $p->ID;
			}
		}

		return (int) $p;
	}
}

if ( ! function_exists( 'bh_get_page_path' ) ) {
	function bh_get_page_path( &$p = null, $current = false ) {
		if ( empty( $p ) && $current ) {
			global $post, $bh_hierarchy;
			if ( ! empty( $bh_hierarchy ) ) {
				$h = $bh_hierarchy;
				$p = array_pop( $h );
				if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
					do_action( 'log', 'get_page_path: Reverting to hierarchy top', $p );
				}
			} elseif ( ! is_search() ) {
				$p = $post;
				if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
					do_action( 'log', 'get_page_path: Reverting to global post', $post );
				}
			}
		}
		if ( is_object( $p ) && ! empty( $p->_page_path ) ) {
			if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
				do_action( 'log', 'get_page_path: Stored page path', $p->_page_path );
			}
			return $p->_page_path;
		}
		if ( ! empty( $p ) && 'page' === $p->post_type ) {
			$page_id = bh_select_post_id( $p );
			$path    = get_page_uri( $page_id );
			if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
				do_action( 'log', 'get_page_path: Page URI', $path );
			}
		}
		if ( empty( $path ) || is_numeric( $path ) ) {
			if ( ! empty( $p ) || ! is_search() ) {
				$permalink = get_permalink( $p );
				$path      = substr( get_permalink( bh_select_post_id( $p ) ), strlen( get_option( 'home' ) ) );
				$path      = trim( $path, '/' );
				if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
					do_action( 'log', 'get_page_path: Permalink', $path );
				}
			}
		}
		if ( $current && empty( $path ) ) {
			$path = $_SERVER['REQUEST_URI'];
			$path = preg_replace( '!^https?://[^/]+/!i', '', $path );
			$path = preg_replace( '!\?.*$!i', '', $path );
			$path = trim( $path, '/' );
			if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
				do_action( 'log', 'get_page_path: Hard URI', $path );
			}
		}
		if ( is_object( $p ) ) {
			$p->_page_path = $path;
		}
		return $path;
	}
} // End if().

if ( ! function_exists( 'bh_current_section' ) ) {
	function bh_current_section( &$p = null, $depth = 0, $current = true ) {
		if ( is_object( $p ) && ! empty( $p->_current_section ) ) {
			return $p->_current_section;
		}
		$path = bh_get_page_path( $p, $current );
		if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
			do_action( 'log', 'current_section: Current path', $path );
		}
		$parts = explode( '/', $path );
		$parts = array_values( array_filter( $parts ) );

		if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
			do_action( 'log', 'current_section: path parts', $parts );
		}

		if ( count( $parts ) <= $depth ) {
			$depth = count( $parts );
		}
		if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
			do_action( 'log', 'current_section: depth', $depth );
		}
		if ( -1 >= $depth || ! isset( $parts[ $depth ] ) ) {
			$section = 'home';
		} else {
			$section = $parts[ $depth ];
		}

		$section2 = apply_filters( 'bh_post_section', $section, $p );
		if ( defined( 'DEBUG_PATHS' ) && DEBUG_PATHS ) {
			do_action( 'log', 'current_section: Page section', $path, $section, $section2 );
		}
		if ( is_object( $p ) ) {
			$p->_current_section = $section2;
		}
		return $section2;
	}
} // End if().
