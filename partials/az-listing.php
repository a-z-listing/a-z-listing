<?php
/**
 * Support functions for the A-Z Index page
 * @package  a-z-listing
 */

class A_Z_Listing {
	private $query;
	private $taxonomy;
	private $type = 'posts';

	private static $alphabet;
	private static $unknown_letters;

	private $index_indices;
	private $index_taxonomy;

	private $items;
	private $item_indices;
	private $current_item = null;
	private $current_letter_items = array();

	private $current_letter_index = 0;
	private $current_item_index = 0;

	function __construct( $query = null ) {
		self::get_alphabet();
		$this->index_indices = array_values( array_unique( array_values( self::$alphabet ) ) );

		if ( is_string( $query ) && ! empty( $query ) ) {
			do_action( 'log', 'A-Z Listing: Setting taxonomy mode', $query );
			$this->type = 'taxonomy';
			$this->taxonomy = $query;
			$this->items = get_terms( $query, array( 'hide_empty' => false ) );
			do_action( 'log', 'A-Z Listing: Terms', '!slug', $this->items );
		} else {
			do_action( 'log', 'A-Z Listing: Setting posts mode', $query );
			$index_taxonomy = apply_filters( 'az_additional_titles_taxonomy', '' );
			$this->index_taxonomy = apply_filters( 'a-z-listing-additional-titles-taxonomy', $index_taxonomy );

			$this->query = $query;

			$section = self::get_section();
			$this->construct_query( $section );

			$this->items = $this->query->get_posts();
		}
		$this->item_indices = $this->get_all_indices();
	}

	/**
	 * @see: http://php.net/manual/en/function.mb-split.php#80046
	 */
	public static function mb_string_to_array( $string ) {
		$strlen = mb_strlen( $string );
		while ( $strlen ) {
			$array[] = mb_substr( $string, 0, 1, 'UTF-8' );
			$string = mb_substr( $string, 1, $strlen, 'UTF-8' );
			$strlen = mb_strlen( $string );
		}
		return $array;
	}

	protected static function get_alphabet() {
		if ( ! empty( self::$alphabet ) ) {
			return;
		}

		/* translators: List the aphabet of your language in the order that your language prefers. list as groups of identical meanings but different characters together, e.g. in English we group A with a because they are both the same letter but different character-code. Each character group should be followed by a comma separating it from the next group. Any amount of characters per group are acceptible, and there is no requirement for all the groups to contain the same amount of characters as all the others. Be careful with the character you place first in each group as that will be used as the identifier for the group which gets displayed on the page, e.g. in English a group definition of "Aa" will indicate that we display all the posts in the group, i.e. whose titles begin with either "A" or "a", listed under a heading of "A" (the first character in the definition). */
		$alphabet = apply_filters( 'a-z-listing-alphabet', __( 'AÁÀÄÂaáàäâ,Bb,Cc,Dd,EÉÈËÊeéèëê,Ff,Gg,Hh,IÍÌÏÎiíìïî,Jj,Kk,Ll,Mm,Nn,OÓÒÖÔoóòöô,Pp,Qq,Rr,Ssß,Tt,UÚÙÜÛuúùüû,Vv,Ww,Xx,Yy,Zz' ) );
		/* translators: This should be a single character to denote "all entries that didn't fit under one of the alphabet character groups defined". This is used in English to categorise posts whose title begins with a numeric (0 through to 9), or some other character that is not a standard English alphabet letter. */
		$others = apply_filters( 'a-z-listing-non-alpha-char', __( '#', 'a-z-listing' ) );

		$alphabet_groups = mb_split( ',', $alphabet );
		$letters = array_reduce( $alphabet_groups, function( $return, $group ) {
			$group = A_Z_Listing::mb_string_to_array( $group );
			$group_index_character = $group[0];
			$group = array_reduce( $group, function( $group, $character ) use ( $group_index_character ) {
				$group[ $character ] = $group_index_character;
				return $group;
			} );
			if ( ! is_array( $return ) ) {
				return $group;
			}
			return array_merge( $return, $group );
		} );

		self::$alphabet = $letters;
		self::$unknown_letters = $others;
	}

	protected static function get_section() {
		$sections = apply_filters( 'az_sections', get_pages( array( 'parent' => 0 ) ) );
		$sections = apply_filters( 'a-z-listing-sections', $sections );
		$section = bh_current_section();
		if ( ! in_array( $section, $sections, true ) ) {
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

	protected function get_the_indices( $item ) {
		$terms = $indices = array();

		if ( $item instanceof WP_Term ) {
			$indices[ substr( $item->name, 0, 1 ) ][] = array( 'title' => $item->name, 'item' => $item );
			$indices = apply_filters( 'a-z-listing-term-indices', $indices, $item );
			$indices = apply_filters( 'a-z-listing-item-indices', $indices, $item, $this->type );
			return $indices;
		}

		if ( ! empty( $this->index_taxonomy ) ) {
			$terms = array_filter( wp_get_object_terms( $item->ID, $index_taxonomy ) );
		}

		$indices[ mb_substr( $item->post_title, 0, 1, 'UTF-8' ) ][] = array( 'title' => $item->post_title, 'item' => $item );
		$term_indices = array_reduce( $terms, function( $indices, $term ) {
			$indices[ mb_substr( $term->name, 0, 1, 'UTF-8' ) ][] = array( 'title' => $term->name, 'item' => $item );
			return $indices;
		});
		if ( is_array( $term_indices ) ) {
			$indices = array_merge( $indices, $term_indices );
		}

		$indices = apply_filters( 'a-z-listing-post-indices', $indices, $item );
		$indices = apply_filters( 'a-z-listing-item-indices', $indices, $item, $this->type );
		return $indices;
	}

	private function get_indexed_items() {
		$letters = array();

		foreach ( $this->items as $item ) {
			$indices = $this->get_the_indices( $item );

			foreach ( $indices as $indice => $index_entries ) {
				if ( count( $index_entries ) > 0 ) {
					if ( in_array( $indice, self::$alphabet, true ) ) {
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

		$index = $this->get_indexed_items();

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
			if ( ! empty( $this->item_indices[ $letter ] ) ) {
				$ret .= '<a href="' . esc_attr( esc_url( $target ) . '#letter-' . $id ) . '">';
			}
			$ret .= '<span>' . esc_html( $letter ) . '</span>';
			if ( ! empty( $this->item_indices[ $letter ] ) ) {
				$ret .= '</a>';
			}
			$ret .= '</li>';
		}
		$ret .= '</ul>';
		return $ret;
	}

	public function get_the_listing() {
		global $post;
		$original_post = $post;

		if ( 'taxonomy' === $this->type ) {
			$section = $this->taxonomy;
		} else {
			$section = self::get_section();
		}

		ob_start();
		if ( locate_template( array( 'a-z-listing-' . $section . '.php', 'a-z-listing.php' ) ) ) {
			get_template_part( 'a-z-listing', $section );
		} else {
			require( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'a-z-listing.php' );
		}
		$r = ob_get_clean();

		$post = $original_post; // WPCS: override OK.
		wp_reset_postdata();

		return $r;
	}

	public function have_a_z_letters() {
		return ( count( $this->index_indices ) > $this->current_letter_index );
	}
	/**
	 * @deprecated in favour of A_Z_Listing::have_a_z_items()
	 */
	public function have_a_z_posts() {
		return $this->have_a_z_items();
	}
	public function have_a_z_items() {
		return ( is_array( $this->current_letter_items ) && count( $this->current_letter_items ) > $this->current_item_index );
	}

	public function the_a_z_letter() {
		$this->current_item_index = 0;
		$this->current_letter_items = array();
		if ( isset( $this->item_indices[ $this->index_indices[ $this->current_letter_index ] ] ) ) {
			$this->current_letter_items = $this->item_indices[ $this->index_indices[ $this->current_letter_index ] ];
		}
		$this->current_letter_index++;
	}
	/**
	 * @deprecated in favour of A_Z_Listing::the_a_z_item()
	 */
	public function the_a_z_post() {
		$this->the_a_z_item();
	}
	public function the_a_z_item() {
		global $post;

		$this->current_item = $this->current_letter_items[ $this->current_item_index ];
		$item_object = $this->current_item['item'];
		if ( $item_object instanceof WP_Post ) {
			$post = $item_object; // WPCS: override OK.
			setup_postdata( $post );
		}

		$this->current_item_index++;
	}

	public function num_a_z_letters() {
		return count( $this->index_indices );
	}
	/**
	 * @deprecated in favour of get_the_letter_count()
	 */
	public function num_a_z_posts() {
		return $this->num_a_z_items();
	}
	/**
	 * @deprecated in favour of get_the_letter_count()
	 */
	public function num_a_z_items() {
		return count( $this->current_letter_items );
	}
	public function the_letter_count() {
		echo count( $this->current_letter_items ); // WPCS: XSS OK
	}
	public function get_the_letter_count() {
		return count( $this->current_letter_items );
	}

	public function the_letter_id() {
		echo esc_attr( $this->get_the_letter_id() );
	}
	public function get_the_letter_id() {
		return 'letter-' . self::$alphabet[ $this->index_indices[ $this->current_letter_index - 1 ] ];
	}
	public function the_letter_title() {
		echo esc_html( $this->get_the_letter_title() );
	}
	public function get_the_letter_title() {
		return self::$alphabet[ $this->index_indices[ $this->current_letter_index - 1 ] ];
	}
	public function the_item_title() {
		echo esc_html( $this->get_the_item_title() );
	}
	public function get_the_item_title() {
		$title = $this->current_item['title'];
		$item = $this->current_item['item'];
		if ( $item instanceof WP_Post ) {
			$title = apply_filters( 'the_title', $title, $item->ID );
		} elseif ( $item instanceof WP_Term ) {
			$title = apply_filters( 'term_name', $title, $item );
		}
		return $title;
	}
	public function the_item_permalink() {
		echo esc_html( $this->get_the_item_permalink() );
	}
	public function get_the_item_permalink() {
		$item = $this->current_item['item'];
		if ( $item instanceof WP_Term ) {
			return get_term_link( $this->current_item['item'] );
		}
		return get_permalink( $item );
	}
}

function have_a_z_letters() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->have_a_z_letters();
}
/**
 * @deprecated in favour of have_a_z_items()
 */
function have_a_z_posts() {
	return have_a_z_items();
}
function have_a_z_items() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->have_a_z_items();
}

function the_a_z_letter() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_a_z_letter();
}
/**
 * @deprecated in favour of the_a_z_item()
 */
function the_a_z_post() {
	the_a_z_item();
}
function the_a_z_item() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_a_z_item();
}

function num_a_z_letters() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->num_a_z_letters();
}
/**
 * @deprecated in favour of get_the_a_z_letter_count() and the_a_z_letter_count()
 */
function num_a_z_posts() {
	return num_a_z_items();
}
/**
 * @deprecated in favour of get_the_a_z_letter_count() and the_a_z_letter_count()
 */
function num_a_z_items() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->get_the_letter_count();
}
function the_a_z_letter_count() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->the_letter_count();
}
function get_the_a_z_letter_count() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->get_the_letter_count();
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
function the_a_z_item_title() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_item_title();
}
function get_the_a_z_item_title() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->get_the_item_title();
}
function the_a_z_item_permalink() {
	global $_a_z_listing_object;
	$_a_z_listing_object->the_item_permalink();
}
function get_the_a_z_item_permalink() {
	global $_a_z_listing_object;
	return $_a_z_listing_object->get_the_item_permalink();
}

/**
 * @deprecated in favour of the_a_z_listing()
 */
function the_az_listing( $query = null, $colcount = 1, $minpercol = 10, $h = 2 ) {
	the_a_z_listing( $query, $colcount, $minpercol, $h );
}
/**
 * Print the A-Z Index page content.
 * @param  WP_Query $query      Option WP_Query of posts to index.
 * @param  integer  $colcount   Optional number of columns to separate the list of posts into.
 * @param  integer  $minpercol  Optional minimum number of posts in each column before starting a new column.
 * @param  integer  $h          Optional Heading-Level number for the section headings. May be 1 thru 7.
 */
function the_a_z_listing( $query = null, $colcount = 1, $minpercol = 10, $h = 2 ) {
	echo get_the_a_z_listing( $query, $colcount, $minpercol, $h ); // WPCS: XSS OK.
}

/**
 * @deprecated in favour of get_the_a_z_listing()
 */
function get_the_az_listing( $query = null, $colcount = 1, $minpercol = 10, $h = 2 ) {
	return get_the_a_z_listing( $query, $colcount, $minpercol, $h );
}
/**
 * Return the index of posts ordered and segmented alphabetically.
 * @param  WP_Query $query      Option WP_Query of posts to index.
 * @param  integer  $colcount   Optional number of columns to separate the list of posts into.
 * @param  integer  $minpercol  Optional minimum number of posts in each column before starting a new column.
 * @param  integer  $h          Optional Heading-Level number for the section headings. May be 1 thru 7.
 * @return string               The listing html content ready for echoing to the page.
 */
function get_the_a_z_listing( $query = null, $colcount = 1, $minpercol = 10, $h = 2 ) {
	global $_a_z_listing_object, $_a_z_listing_colcount, $_a_z_listing_minpercol;
	$_a_z_listing_colcount = $colcount;
	$_a_z_listing_minpercol = $minpercol;

	$_a_z_listing_object = new A_Z_Listing( $query );
	return $_a_z_listing_object->get_the_listing();
}

/**
 * @deprecated in favour of the_a_z_letters()
 */
function the_az_letters( $query = null, $target = false, $styling = false ) {
	the_a_z_letters( $query, $target, $styling );
}
/**
 * Prints the A-Z Letter list.
 * @param  WP_Query $query Optional WP_Query object defining the posts to index.
 * @param  string $target  URL of the page to send the browser when a letter is clicked.
 */
function the_a_z_letters( $query = null, $target = false, $styling = false ) {
	echo get_the_a_z_letters( $query, $target, $styling ); // WPCS: XSS OK.
}

/**
 * @deprecated in favour of get_the_a_z_letters()
 */
function get_the_az_letters( $query = null, $target = false, $styling = false ) {
	return get_the_a_z_letters( $query, $target, $styling );
}
/**
 * Returns the A-Z Letter list.
 * @param  WP_Query $query Optional WP_Query object defining the posts to index.
 * @param  string $target  URL of the page to send the browser when a letter is clicked.
 * @return String          HTML ready for echoing containing the list of A-Z letters with anchor links to the A-Z Index page.
 */
function get_the_a_z_letters( $query = null, $target = false, $styling = false ) {
	global $_a_z_listing_object;
	if ( ! $_a_z_listing_object instanceof A_Z_Listing || null !== $query ) {
		$_a_z_listing_object = new A_Z_Listing( $query );
	}
	return $_a_z_listing_object->get_letter_display( $target, $styling );
}
