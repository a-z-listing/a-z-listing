<?php

class A_Z_Listing {
	private $query;
	private $taxonomy;
	private $type = 'posts';

	// All available characters in a single string for translation support.
	private static $alphabet;
	// The index to use for posts which are not matched by any known letter, from the $alphabet, such as numerics.
	private static $unknown_letters;

	// All available characters which may be used as an index.
	private $available_indices;
	// A Taxonomy which contains terms to apply additional titles to posts.
	private $index_taxonomy;

	// All items returned by the query.
	private $items;
	// Indices for only the items returned by the query - filtered version of $available_indices.
	private $matched_item_indices;

	// The current item for use in the a-z items loop. internal use only.
	private $current_item = null;
	// The current item array-index in $items. internal use only.
	private $current_item_index = 0;

	// The current letter for use in the a-z letter loop. internal use only.
	private $current_letter_items = array();
	// The current letter array-index in $matched_item_indices. internal use only.
	private $current_letter_index = 0;

	/**
	 * A_Z_Listing constructor.
	 * @param null|WP_Query|string $query
	 */
	public function __construct( $query = null ) {
		self::get_alphabet();
		$this->available_indices = array_values( array_unique( array_values( self::$alphabet ) ) );

		if ( is_string( $query ) && ! empty( $query ) ) {
			if ( AZLISTINGLOG ) {
				do_action( 'log', 'A-Z Listing: Setting taxonomy mode', $query );
			}
			$this->type = 'taxonomy';
			$this->taxonomy = $query;
			$this->items = get_terms( $query, array( 'hide_empty' => false ) );
			if ( AZLISTINGLOG ) {
				do_action( 'log', 'A-Z Listing: Terms', '!slug', $this->items );
			}
		} else {
			if ( AZLISTINGLOG ) {
				do_action( 'log', 'A-Z Listing: Setting posts mode', $query );
			}
			$index_taxonomy = apply_filters( 'az_additional_titles_taxonomy', '' );
			$this->index_taxonomy = apply_filters( 'a_z_listing_additional_titles_taxonomy', $index_taxonomy );

			$this->query = $query;

			$section = self::get_section();
			$this->construct_query( $section );

			$this->items = $this->query->get_posts();
		}
		$this->matched_item_indices = $this->get_all_indices();
	}

	/**
	 * @see: http://php.net/manual/en/function.mb-split.php#80046
	 * @param string $string multi-byte string
	 * @return array individual multi-byte characters from the string
	 */
	public static function mb_string_to_array( $string ) {
		$array = array();
		$length = mb_strlen( $string );
		while ( $length ) {
			$array[] = mb_substr( $string, 0, 1, 'UTF-8' );
			$string = mb_substr( $string, 1, $length, 'UTF-8' );
			$length = mb_strlen( $string );
		}
		return $array;
	}

	protected static function get_alphabet() {
		if ( ! empty( self::$alphabet ) ) {
			return;
		}

		/* translators: List the aphabet of your language in the order that your language prefers. list as groups of identical meanings but different characters together, e.g. in English we group A with a because they are both the same letter but different character-code. Each character group should be followed by a comma separating it from the next group. Any amount of characters per group are acceptible, and there is no requirement for all the groups to contain the same amount of characters as all the others. Be careful with the character you place first in each group as that will be used as the identifier for the group which gets displayed on the page, e.g. in English a group definition of "Aa" will indicate that we display all the posts in the group, i.e. whose titles begin with either "A" or "a", listed under a heading of "A" (the first character in the definition). */
		$alphabet = apply_filters( 'a_z_listing_alphabet', __( 'AÁÀÄÂaáàäâ,Bb,Cc,Dd,EÉÈËÊeéèëê,Ff,Gg,Hh,IÍÌÏÎiíìïî,Jj,Kk,Ll,Mm,Nn,OÓÒÖÔoóòöô,Pp,Qq,Rr,Ssß,Tt,UÚÙÜÛuúùüû,Vv,Ww,Xx,Yy,Zz' ) );
		/* translators: This should be a single character to denote "all entries that didn't fit under one of the alphabet character groups defined". This is used in English to categorise posts whose title begins with a numeric (0 through to 9), or some other character that is not a standard English alphabet letter. */
		$others = apply_filters( 'a_z_listing_non_alpha_char', __( '#', 'a-z-listing' ) );

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
		$sections = apply_filters( 'a_z_listing_sections', $sections );
		$section = bh_current_section();
		if ( ! in_array( $section, $sections, true ) ) {
			$section = null;
		}
		if ( AZLISTINGLOG ) {
			do_action( 'log', 'A-Z Section', $section );
		}
		return $section;
	}

	protected function construct_query( $section = null ) {
		$q = apply_filters( 'a_z_listing_query', $this->query );

		$query = null;

		if ( ! $q instanceof WP_Query ) {
			$q = wp_parse_args($q, array(
				'post_type' => 'page',
				'numberposts' => -1,
				'section' => $section,
				'nopaging' => true,
			) );
			$query = new WP_Query( $q );
		}

		if ( ! $query instanceof WP_Query ) {
			$query = $q;
		}

		$this->query = $query;
	}

	public function get_the_query() {
		return $this->query;
	}

	protected function get_the_item_indices( $item ) {
		$terms = $indices = array();

		if ( $item instanceof WP_Term ) {
			$indices[ mb_substr( $item->name, 0, 1, 'UTF-8' ) ][] = array( 'title' => $item->name, 'item' => $item );
			$indices = apply_filters( 'a_z_listing_term_indices', $indices, $item );
			$indices = apply_filters( 'a_z_listing_item_indices', $indices, $item, $this->type );
			return $indices;
		}

		if ( ! empty( $this->index_taxonomy ) ) {
			$terms = array_filter( wp_get_object_terms( $item->ID, $this->index_taxonomy ) );
		}

		$indices[ mb_substr( $item->post_title, 0, 1, 'UTF-8' ) ][] = array( 'title' => $item->post_title, 'item' => $item );
		$term_indices = array_reduce( $terms, function( $indices, $term ) {
			$indices[ mb_substr( $term->name, 0, 1, 'UTF-8' ) ][] = array( 'title' => $term->name, 'item' => $term );
			return $indices;
		});
		if ( is_array( $term_indices ) ) {
			$indices = array_merge( $indices, $term_indices );
		}

		$indices = apply_filters( 'a_z_listing_post_indices', $indices, $item );
		$indices = apply_filters( 'a_z_listing_item_indices', $indices, $item, $this->type );
		if ( AZLISTINGLOG ) {
			do_action( 'log', 'Item indices', $indices );
		}
		return $indices;
	}

	protected function get_indexed_items() {
		$letters = array();

		foreach ( $this->items as $item ) {
			$indices = $this->get_the_item_indices( $item );

			foreach ( $indices as $index => $index_entries ) {
				if ( count( $index_entries ) > 0 ) {
					if ( in_array( $index, array_keys( self::$alphabet ), true ) ) {
						$index = self::$alphabet[ $index ];
					} else {
						$index = '_';
					}

					if ( ! isset( $letters[ $index ] ) || ! is_array( $letters[ $index ] ) ) {
						$letters[ $index ] = array();
					}

					$letters[ $index ] = array_merge_recursive( $letters[ $index ], $index_entries );
				}
			}
		}

		return $letters;
	}

	protected function get_all_indices() {
		$indexed_items = $this->get_indexed_items();

		if ( ! empty( $index[ self::$unknown_letters ] ) ) {
			$this->available_indices[] = self::$unknown_letters;
		}

		foreach ( $this->available_indices as $index ) {
			if ( ! empty( $indexed_items[ $index ] ) ) {
				usort( $indexed_items[ $index ], function( $a, $b ) {
					return strcmp( $a['title'], $b['title'] );
				});
			}
		}

		return $indexed_items;
	}

	public function the_letters() {
		echo $this->get_the_letters(); // WPCS: XSS OK.
	}

	/**
	 * @deprecated use A_Z_Listing::get_the_letters().
	 * @param string $target
	 * @param null $style
	 * @return string
	 */
	public function get_letter_display( $target = '', $style = null  ) {
		return $this->get_the_letters( $target, $style );
	}

	public function get_the_letters( $target = '', $style = null ) {
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
		$count = count( $this->available_indices );
		$i = 0;
		foreach ( $this->available_indices as $letter ) {
			$i += 1;
			$id = $letter;
			if ( self::$unknown_letters === $id ) {
				$id = '_';
			}

			$classes = ( ( 1 === $i ) ? 'first ' : ( ( $count === $i ) ? 'last ' : '' ) );
			$classes .= ( ( 0 === $i % 2 ) ? 'even' : 'odd' );

			$ret .= '<li class="' . esc_attr( $classes ) . '">';
			if ( ! empty( $this->matched_item_indices[ $letter ] ) ) {
				$ret .= '<a href="' . esc_url( $target . '#letter-' . $id ) . '">';
			}
			$ret .= '<span>' . esc_html( $letter ) . '</span>';
			if ( ! empty( $this->matched_item_indices[ $letter ] ) ) {
				$ret .= '</a>';
			}
			$ret .= '</li>';
		}
		$ret .= '</ul>';
		return $ret;
	}

	protected function do_template( $template_file ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$a_z_query = $this;
		/** @noinspection PhpIncludeInspection */
		include( $template_file );
	}

	public function get_the_listing() {
		if ( 'taxonomy' === $this->type ) {
			$section = $this->taxonomy;
		} else {
			$section = self::get_section();
		}

		ob_start();
		$template = locate_template( array( 'a-z-listing-' . $section . '.php', 'a-z-listing.php' ) );
		if ( $template ) {
			$this->do_template( $template );
		} else {
			$this->do_template( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'a-z-listing.php' );
		}
		$r = ob_get_clean();

		wp_reset_postdata();

		return $r;
	}

	public function have_letters() {
		return ( count( $this->available_indices ) > $this->current_letter_index );
	}
	/**
	 * @deprecated use A_Z_Listing::have_items()
	 */
	public function have_a_z_posts() {
		return $this->have_items();
	}
	public function have_items() {
		return ( is_array( $this->current_letter_items ) && count( $this->current_letter_items ) > $this->current_item_index );
	}

	public function the_letter() {
		$this->current_item_index = 0;
		$this->current_letter_items = array();
		if ( isset( $this->matched_item_indices[ $this->available_indices[ $this->current_letter_index ] ] ) ) {
			$this->current_letter_items = $this->matched_item_indices[ $this->available_indices[ $this->current_letter_index ] ];
		}
		$this->current_letter_index += 1;
	}
	/**
	 * @deprecated use A_Z_Listing::the_item()
	 */
	public function the_a_z_post() {
		$this->the_item();
	}
	public function the_item() {
		$this->current_item = $this->current_letter_items[ $this->current_item_index ];
		$item_object = $this->current_item['item'];
		if ( $item_object instanceof WP_Post ) {
			setup_postdata( $item_object );
		}

		$this->current_item_index += 1;
	}

	public function num_letters() {
		return count( $this->available_indices );
	}
	/**
	 * @deprecated use A_Z_Listing::get_the_letter_count()
	 */
	public function num_a_z_posts() {
		/** @noinspection PhpDeprecationInspection */
		return $this->num_a_z_items();
	}
	/**
	 * @deprecated use A_Z_Listing::get_the_letter_count()
	 */
	public function num_a_z_items() {
		return count( $this->current_letter_items );
	}
	public function the_letter_count() {
		echo esc_html( count( $this->current_letter_items ) );
	}
	public function get_the_letter_count() {
		return count( $this->current_letter_items );
	}

	public function the_letter_id() {
		echo esc_attr( $this->get_the_letter_id() );
	}
	public function get_the_letter_id() {
		return 'letter-' . self::$alphabet[ $this->available_indices[ $this->current_letter_index - 1 ] ];
	}
	public function the_letter_title() {
		echo esc_html( $this->get_the_letter_title() );
	}
	public function get_the_letter_title() {
		return self::$alphabet[ $this->available_indices[ $this->current_letter_index - 1 ] ];
	}
	public function the_title() {
		echo esc_html( $this->get_the_title() );
	}
	public function get_the_title() {
		$title = $this->current_item['title'];
		$item = $this->current_item['item'];
		if ( $item instanceof WP_Post ) {
			$title = apply_filters( 'the_title', $title, $item->ID );
		} elseif ( $item instanceof WP_Term ) {
			$title = apply_filters( 'term_name', $title, $item );
		}
		return $title;
	}
	public function the_permalink() {
		echo esc_url( $this->get_the_permalink() );
	}
	public function get_the_permalink() {
		$item = $this->current_item['item'];
		if ( $item instanceof WP_Term ) {
			return get_term_link( $this->current_item['item'] );
		}
		return get_permalink( $item );
	}
}

/**
 * Get a saved copy of the A_Z_Listing instance if we have one, or make a new one and save it for later
 * @param  array|string|WP_Query|A_Z_Listing  $query  a valid WordPress query or an A_Z_Listing instance
 * @return A_Z_Listing                                a new or previously-saved instance of A_Z_Listing using the provided construct_query
 */
function a_z_listing_cache( $query = null ) {
	static $cache = array();

	// copy $query into $obj in case it already is a valid A_Z_Listing instance.
	$obj = $query;
	if ( $obj instanceof A_Z_Listing ) {
		// we received a valid A_Z_Listing instance so we get the query from it for the cache lookup/save key.
		$query = $obj->get_the_query();
	}

	// check the cache and return any pre-existing A_Z_Listing instance we have.
	$key = serialize( $query );
	if ( array_key_exists( $key, $cache ) ) {
		return $cache[ $key ];
	}

	// if $query is $obj then we did not get an A_Z_Listing instance as our argument, so we will make a new one.
	if ( $query === $obj ) {
		$obj = new A_Z_Listing( $query );
	}
	// save the new A_Z_Listing instance into the cache.
	$cache[ $key ] = $obj;

	// return the new A_Z_Listing instance.
	return $cache[ $key ];
}
