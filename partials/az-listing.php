<?php
/**
 * Support functions for the A-Z Index page
 * @package  a-z-listing
 */

class A_Z_Listing {
	private $query;

	private static $alphabet;
	private static $unknown_letters;

	private $index_indices;
	private $index_taxonomy;

	private $posts;
	private $post_indices;
	private $current_letter_posts = array();

	private $current_letter_index = 0;
	private $current_post_index = 0;

	function __construct( $query ) {
		self::get_alphabet();
		$this->index_indices = array_values( array_unique( array_values( self::$alphabet ) ) );
		$this->index_taxonomy = apply_filters( 'az_additional_titles_taxonomy', '' );
		$this->query = $query;

		$section = self::get_section();
		$this->construct_query( $section );

		$this->posts = $this->query->get_posts();
		$this->post_indices = $this->get_all_indices();
	}

	protected static function get_alphabet() {
		if ( ! empty( self::$alphabet ) ) {
			return;
		}

		/* translators: List the aphabet of your language in the order that your language prefers. list as groups of identical meanings but different characters together, e.g. in English we group A with a because they are both the same letter but different character-code. Each character group should be followed by a comma separating it from the next group. Any amount of characters per group are acceptible, and there is no requirement for all the groups to contain the same amount of characters as all the others. Be careful with the character you place first in each group as that will be used as the identifier for the group which gets displayed on the page, e.g. in English a group definition of "Aa" will indicate that we display all the posts in the group, i.e. whose titles begin with either "A" or "a", listed under a heading of "A" (the first character in the definition). */
		$alphabet = apply_filters( 'a-z-listing-alphabet', __( 'Aa,Bb,Cc,Dd,Ee,Ff,Gg,Hh,Ii,Jj,Kk,Ll,Mm,Nn,Oo,Pp,Qq,Rr,Ss,Tt,Uu,Vv,Ww,Xx,Yy,Zz' ) );
		/* translators: This should be a single character to denote "all entries that didn't fit under one of the alphabet character groups defined". This is used in English to categorise posts whose title begins with a numeric (0 through to 9), or some other character that is not a standard English alphabet letter. */
		$others = apply_filters( 'a-z-listing-non-alpha-char', __( '#', 'a-z-listing' ) );

		$alphabet_groups = explode( ',', $alphabet );
		$letters = array_reduce( $alphabet_groups, function( $return, $group ) {
			$group_index_character = $group[0];
			$group = str_split( $group );
			$group = array_reduce( $group, function( $group, $character ) use ( $group_index_character ) {
				$group[ $character ] = $group_index_character;
				return $group;
			} );
			if ( ! is_array( $return ) ) {
				return $group;
			}
			return array_merge_recursive( $return, $group );
		} );

		self::$alphabet = $letters;
		self::$unknown_letters = $others;
	}

	protected static function get_section() {
		$sections = apply_filters( 'az_sections', get_pages( array( 'parent' => 0 ) ) );
		$section = bh_current_section();
		if ( ! in_array( $section, $sections ) ) {
			$section = null;
		}
		do_action( 'log', 'A-Z Section', $section );
		return $section;
	}

	private function construct_query( $section = null ) {
		$q = apply_filters( 'a-z-listing-query', $this->query );

		$query = null;

		if ( ! $q instanceof WP_Query ) {
			if ( is_array( $q ) ) {
				$query = new WP_Query( $q );
			} else {
				$query = new WP_Query( array(
					'post_type' => 'page',
					'numberposts' => -1,
					'section' => $section,
					'nopaging' => true,
				) );
			}
		}

		if ( ! $query instanceof WP_Query ) {
			$query = $q;
		}

		$this->query = $query;
	}

	private function get_the_indices( $post ) {
		$terms = $indices = array();

		if ( ! empty( $this->index_taxonomy ) ) {
			$terms = array_filter( wp_get_object_terms( $post->ID, $index_taxonomy ) );
		}

		$indices[ substr( $post->post_title, 0, 1 ) ][] = array( 'title' =>  $post->post_title, 'post' => $post);
		$term_indices = array_reduce( $terms, function( $indices, $term ) {
			$indices[ substr( $term->name, 0, 1 ) ][] = array( 'title' => $term->name, 'post' => $post);
			return $indices;
		});
		if ( is_array( $term_indices ) ) {
			$indices = array_merge_recursive( $indices, $term_indices );
		}

		$indices = apply_filters( 'a-z-listing-post-indices', $indices, $post );

		return $indices;
	}

	function get_indexed_posts() {
		$letters = array();

		foreach ( $this->posts as $post ) {
			$indices = $this->get_the_indices( $post );

			foreach ( $indices as $indice => $index_entries ) {
				if ( count( $index_entries ) > 0 ) {
					if ( in_array( $indice, self::$alphabet ) ) {
						$indice = self::$alphabet[ $indice ];
					} else {
						$indice = '_';
					}

					if ( ! isset( $letters[ $indice ] ) || ! is_array( $letters[ $indice ] ) ) {
						$letters[ $indice ] = array();
					}

					$letters[ $indice ] = array_merge_recursive( $letters[ $indice ], $index_entries );
				}
			}
		}

		return $letters;
	}

	private function get_all_indices() {
		$short_names = array();

		$index = $this->get_indexed_posts();

		if ( ! empty( $index[ self::$unknown_letters ] ) ) {
			$this->index_indices[] = self::$unknown_letters;
		}

		foreach ( $this->index_indices as $indice ) {
			if ( ! empty( $index[ $indice ] ) ) {
				usort( $index[ $indice ], function( $a, $b ) {
					return strcmp( $a['title'], $b['title'] );
				});
			}
		}

		return $index;
	}

	public function get_letter_display( $target = '', $style = null ) {
		$classes = array( 'az-links' );
		if ( null !== $style ) {
			if ( is_array( $style ) ) {
				$classes = array_merge( $classes, $style );
			} elseif ( is_string( $style ) ) {
				$c = explode( ' ', $style );
				$classes = array_merge( $classes, $c );
			}
		}
		$classes = array_unique( $classes );

		$ret = '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';
		$count = count( $this->index_indices );
		$i = 0;
		foreach ( $this->index_indices as $letter ) {
			$i++;
			$id = $letter;
			if ( self::$unknown_letters === $id ) {
				$id = '_';
			}

			$classes = ( ( 1 === $i ) ? 'first ' : ( ( $count === $i ) ? 'last ' : '' ) );
			$classes .= ( ( 0 === $i % 2 ) ? 'even' : 'odd' );

			$ret .= '<li class="' . esc_attr( $classes ) . '">';
			if ( ! empty( $this->post_indices[ $letter ] ) ) {
				$ret .= '<a href="' . esc_attr( esc_url( $target ) . '#letter-' . $id ) . '">';
			}
			$ret .= '<span>' . esc_html( $letter ) . '</span>';
			if ( ! empty( $this->post_indices[ $letter ] ) ) {
				$ret .= '</a>';
			}
			$ret .= '</li>';
		}
		$ret .= '</ul>';
		return $ret;
	}

	public function get_the_listing() {
		$section = self::get_section();

		global $post;
		$original_post = $post;
		ob_start();

		if ( locate_template( array( 'a-z-listing-' . $section . '.php', 'a-z-listing.php' ) ) ) {
			get_template_part( 'a-z-listing', $section );
		} else {
			require( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'a-z-listing.php' );
		}

		$post = $original_post;
		wp_reset_postdata();

		$r = ob_get_clean();

		return $r;
	}

	public function have_a_z_letters() {
		return ( count( $this->index_indices ) > $this->current_letter_index );
	}
	public function have_a_z_posts() {
		return ( is_array( $this->current_letter_posts ) && count( $this->current_letter_posts ) > $this->current_post_index );
	}

	public function the_a_z_letter() {
		$this->current_post_index = 0;
		$this->current_letter_posts = array();
		if ( isset( $this->post_indices[ $this->index_indices[ $this->current_letter_index ] ] ) ) {
			$this->current_letter_posts = $this->post_indices[ $this->index_indices[ $this->current_letter_index ] ];
		}
		$this->current_letter_index++;
	}
	public function the_a_z_post() {
		global $post;
		$post = $this->current_letter_posts[ $this->current_post_index ]['post'];
		setup_postdata( $post );
		$this->current_post_index++;
	}

	public function num_a_z_letters() {
		return count( $this->index_indices );
	}
	public function num_a_z_posts() {
		return count( $this->current_letter_posts );
	}

	public function the_letter_id() {
		echo $this->get_the_letter_id();
	}
	public function get_the_letter_id() {
		return esc_attr( 'letter-' . self::$alphabet[ $this->index_indices[ $this->current_letter_index - 1 ] ] );
	}
	public function the_letter_title() {
		echo $this->get_the_letter_title();
	}
	public function get_the_letter_title() {
		return esc_html( self::$alphabet[ $this->index_indices[ $this->current_letter_index - 1 ] ] );
	}
}

function have_a_z_letters() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->have_a_z_letters();
}
function have_a_z_posts() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->have_a_z_posts();
}

function the_a_z_letter() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_a_z_letter();
}
function the_a_z_post() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_a_z_post();
}

function num_a_z_letters() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->num_a_z_letters();
}
function num_a_z_posts() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->num_a_z_posts();
}

function the_a_z_letter_id() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_letter_id();
}
function get_the_a_z_letter_id() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->get_the_letter_id();
}
function the_a_z_letter_title() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_letter_title();
}
function get_the_a_z_letter_title() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->get_the_letter_title();
}

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
	global $_a_z_listing_object, $_a_z_listing_colcount, $_a_z_listing_minpercol;
	$_a_z_listing_colcount = $colcount;
	$_a_z_listing_minpercol = $minpercol;

	$_a_z_listing_object = new A_Z_Listing( $query );
	return $_a_z_listing_object->get_the_listing();
}

/**
 * Prints the A-Z Letter list.
 * @param  WP_Query $query Optional WP_Query object defining the posts to index.
 * @param  string $target  URL of the page to send the browser when a letter is clicked.
 */
function the_az_letters( $query = null, $target = false, $styling = false ) {
	echo get_the_az_letters( $query, $target, $styling ); // WPCS: XSS OK.
}

/**
 * Returns the A-Z Letter list.
 * @param  WP_Query $query Optional WP_Query object defining the posts to index.
 * @param  string $target  URL of the page to send the browser when a letter is clicked.
 * @return String          HTML ready for echoing containing the list of A-Z letters with anchor links to the A-Z Index page.
 */
function get_the_az_letters( $query = null, $target = false, $styling = false ) {
	global $_a_z_listing_object;
	if ( ! $_a_z_listing_object instanceof A_Z_Listing || $query !== null ) {
		$_a_z_listing_object = new A_Z_Listing( $query );
	}
	return $_a_z_listing_object->get_letter_display( $target, $styling );
}
