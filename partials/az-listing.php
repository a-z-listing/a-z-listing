<?php
/**
 * Support functions for the A-Z Index page
 * @package  a-z-listing
 */

/**
 * Print the A-Z Index page content.
 * @param  WP_Query $query      Option WP_Query of posts to index.
 * @param  integer  $colcount   Optional number of columns to separate the list of posts into.
 * @param  integer  $minpercol  Optional minimum number of posts in each column before starting a new column.
 * @param  integer  $h          Optional Heading-Level number for the section headings. May be 1 thru 7.
 */
function the_az_listing( $query = null, $colcount = 1, $minpercol = 10, $h = 2 ) {
	echo get_the_az_listing( $query, $colcount, $minpercol, $h ); // WPCS: XSS OK.
}

/**
 * Return the index of posts ordered and segmented alphabetically.
 * @param  WP_Query $query      Option WP_Query of posts to index.
 * @param  integer  $colcount   Optional number of columns to separate the list of posts into.
 * @param  integer  $minpercol  Optional minimum number of posts in each column before starting a new column.
 * @param  integer  $h          Optional Heading-Level number for the section headings. May be 1 thru 7.
 * @return string               The listing html content ready for echoing to the page.
 */
function get_the_az_listing( $query = null, $colcount = 1, $minpercol = 10, $h = 2 ) {
	$heading_level = (int) $h;
	$heading_level = ( 1 <= $heading_level && 7 >= $heading_level ) ? $heading_level : 2;
	$caps = range( 'A', 'Z' );
	$letters = bh__az_query( $query );

	$ret = '<div id="letters">' . get_the_az_letters( $query ) .'</div>';
	$ret .= '<div id="az-slider"><div id="inner-slider">';

	foreach ( $caps as $letter ) {
		if ( ! empty( $letters[ $letter ] ) ) {
			$id = $letter;
			if ( '#' == $id ) {
				$id = '_';
			}
			$ret .= '<div class="letter-section" id="letter-' . $id . '"><a name="letter-' . $id . '"></a>';
			$ret .= '<h' . $heading_level . '><span>' . esc_html( $letter ) . '</span></h' . $heading_level . '>';

			$numpercol = ceil( count( $letters[ $letter ] ) / $colcount );

			$i = $j = 0;
			foreach ( $letters[ $letter ] as $name => $post ) {
				if ( 0 == $i ) {
					$ret .= '<div><ul>';
				}
				$i++;
				$j++;
				$ret .= '<li><a href="' . get_permalink( $post->ID ) . '">' . esc_html( $name ) .'</a></li>';
				if ( ( $minpercol - $i <= 0 && $numpercol - $i <= 0 ) || $j >= count( $letters[ $letter ] ) ) {
					$ret .= '</ul></div>';
					$i = 0;
				}
			}
			$ret .= '<div class="clear empty"></div></div><!-- /letter-section -->';
		}
	}

	$ret .= '</div></div>';
	return $ret;
}

/**
 * Prints the A-Z Letter list.
 * @param  WP_Query $query Optional WP_Query object defining the posts to index.
 */
function the_az_letters( $query = null ) {
	echo get_the_az_letters( $query ); // WPCS: XSS OK.
}

/**
 * Returns the A-Z Letter list.
 * @param  WP_Query $query Optional WP_Query object defining the posts to index.
 * @return String          HTML ready for echoing containing the list of A-Z letters with anchor links to the A-Z Index page.
 */
function get_the_az_letters( $query = null ) {
	$caps = range( 'A', 'Z' );
	$letters = bh__az_query( $query );

	$ret = '<div class="az-letters"><ul>';
	$count = 0;
	foreach ( $caps as $letter ) {
		$count++;
		$id = $letter;
		if ( '#' == $id ) {
			$id = '_';
		}

		$extra_pre = $extra_post = '';
		$classes = ( ( 1 == $count ) ? 'first ' : ( ( count( $caps ) == $count ) ? 'last ' : '' ) );
		$classes .= ( ( 0 == $count % 2 ) ? 'even' : 'odd' );
		if ( ! empty( $letters[ $letter ] ) ) {
			$extra_pre = '<a href="' . esc_attr( '#letter-' . $id ) . '">';
			$extra_post = '</a>';
		}
		$ret .= '<li class="' . esc_attr( $classes ) . '">';
		$ret .= $extra_pre . '<span>' . esc_html( $letter ) . '</span>' . $extra_post;
		$ret .= '</li>';
	}
	$ret .= '</ul><div class="clear empty"></div></div>';
	return $ret;
}

/**
 * Queries the database for posts and organises the IDs into an Array with each post assigned to a letter for use in the index page or widget.
 * @param  WP_Query $query Query arguments defining the posts to use for the A-Z Listing.
 * @return array           The list of post IDs assigned into a slice for each appropriate letter.
 */
function bh__az_query( $query ) {
	$sections = apply_filters( 'az_sections', get_pages( array( 'parent' => 0 ) ) );
	$section = bh_current_section();
	if ( ! in_array( $section, $sections ) ) {
		$section = null;
	}

	do_action( 'log', 'A-Z Section', $section );
	if ( ! $query instanceof WP_Query ) {
		$query = new WP_Query(array(
			'post_type' => 'page',
			'numberposts' => -1,
			'section' => $section,
			'nopaging' => true,
		));
	}

	$pages = $query->get_posts();

	$letters = array();
	$caps = range( 'A', 'Z' );

	$short_names = array();
	foreach ( $pages as $page ) {
		$index_tax = apply_filters( 'az_additional_titles_taxonomy', '' );
		$terms = array();
		if ( ! empty( $index_tax ) ) {
			$terms = array_filter( wp_get_object_terms( $page->ID, $index_tax ) );
		}
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$A = strtoupper( substr( $term->name, 0, 1 ) );
				if ( ! in_array( $A, $caps ) ) {
					$A = '#';
				}
				$letters[ $A ][ $term->name ] = $page;
			}
		} else {
			$A = strtoupper( substr( get_the_title( $page->ID ), 0, 1 ) );
			if ( ! in_array( $A, $caps ) ) {
				$A = '#';
			}
			$letters[ $A ][ get_the_title( $page->ID ) ] = $page;
		}
	}

	$letters = array_filter( $letters );

	if ( ! empty( $letters['#'] ) ) {
		$caps[] = '#';
	}

	foreach ( $caps as $letter ) {
		if ( ! empty( $letters[ $letter ] ) ) {
			ksort( $letters[ $letter ], SORT_STRING );
		}
	}

	return $letters;
}
