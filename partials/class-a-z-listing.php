<?php
/**
 * The main A-Z Query class
 *
 * @since 0.1
 */
class A_Z_Listing {
	private $query;
	private $taxonomy;
	private $type = 'posts';

	/**
	 * All available characters in a single string for translation support.
	 */
	private static $alphabet;

	/**
	 * The index to use for posts which are not matched by any known letter, from the $alphabet, such as numerics.
	 */
	private static $unknown_letters;

	/**
	 * All available characters which may be used as an index.
	 */

	private $available_indices;
	/**
	 * A Taxonomy which contains terms to apply additional titles to posts.
	 */
	private $index_taxonomy;

	/**
	 * All items returned by the query.
	 */
	private $items;

	/**
	 * Indices for only the items returned by the query - filtered version of $available_indices.
	 */
	private $matched_item_indices;

	/**
	 * The current item for use in the a-z items loop. internal use only.
	 */
	private $current_item = null;

	/**
	 * The current item array-index in $items. internal use only.
	 */
	private $current_item_index = 0;

	/**
	 * The current letter for use in the a-z letter loop. internal use only.
	 */
	private $current_letter_items = array();

	/**
	 * The current letter array-index in $matched_item_indices. internal use only.
	 */
	private $current_letter_index = 0;

	/**
	 * A_Z_Listing constructor.
	 *
	 * @since 0.1
	 * @param null|WP_Query|array|string $query
	 */
	public function __construct( $query = null ) {
		global $post;
		self::get_alphabet();
		$this->available_indices = array_values( array_unique( array_values( self::$alphabet ) ) );

		if ( is_string( $query ) && ! empty( $query ) ) {
			if ( AZLISTINGLOG ) {
				do_action( 'log', 'A-Z Listing: Setting taxonomy mode', $query );
			}

			$this->type     = 'taxonomy';
			$this->taxonomy = $query;
			$this->items    = get_terms(
				$query, array(
					'hide_empty' => false,
				)
			);

			if ( AZLISTINGLOG ) {
				do_action( 'log', 'A-Z Listing: Terms', '!slug', $this->items );
			}
		} else {
			if ( AZLISTINGLOG ) {
				do_action( 'log', 'A-Z Listing: Setting posts mode', $query );
			}
			/**
			 * @deprecated Use a_z_listing_additional_titles_taxonomy
			 * @see a_z_listing_additional_titles_taxonomy
			 */
			$index_taxonomy = apply_filters_deprecated( 'az_additional_titles_taxonomy', array( '' ), '1.0.0', 'a_z_listing_additional_titles_taxonomy' );
			/**
			 * Taxonomy containing terms which are used as the title for associated posts
			 *
			 * @param string $taxonomy The taxonomy mapping alternative titles to posts
			 */
			$index_taxonomy = apply_filters( 'a_z_listing_additional_titles_taxonomy', $index_taxonomy );
			/**
			 * Taxonomy containing terms which are used as the title for associated posts
			 *
			 * @since 1.7.1
			 * @param string $taxonomy The taxonomy mapping alternative titles to posts
			 */
			$index_taxonomy       = apply_filters( 'a-z-listing-additional-titles-taxonomy', $index_taxonomy );
			$this->index_taxonomy = $index_taxonomy;

			$query = (array) $query;

			$section = self::get_section();

			if ( ( isset( $query['post_type'] ) && 'page' !== $query['post_type'] )
				|| ( isset( $post ) && 'page' !== $post->post_type ) ) {
				$section = null;
			}

			if ( $section ) {
				$query['child_of'] = $section->ID;
			}

			if ( isset( $query['child_of'] ) ) {
				$this->items = get_pages( $query );
			} else {
				$query       = $this->construct_query( $query );
				$this->items = $query->get_posts();
			}

			$this->query = $query;
		} // End if().

		$this->matched_item_indices = $this->get_all_indices();
	}

	/**
	 * Split a multibyte string into an array. (see http://php.net/manual/en/function.mb-split.php#80046)
	 *
	 * @since 1.0.0
	 * @param string $string multi-byte string
	 * @return array individual multi-byte characters from the string
	 */
	public static function mb_string_to_array( $string ) {
		$array  = array();
		$length = mb_strlen( $string );
		while ( $length ) {
			$array[] = mb_substr( $string, 0, 1, 'UTF-8' );
			$string  = mb_substr( $string, 1, $length, 'UTF-8' );
			$length  = mb_strlen( $string );
		}
		return $array;
	}

	/**
	 * Build a translated alphabet
	 *
	 * @since 0.1
	 */
	protected static function get_alphabet() {
		if ( ! empty( self::$alphabet ) ) {
			return;
		}

		/* translators: List the aphabet of your language in the order that your language prefers. list as groups of identical meanings but different characters together, e.g. in English we group A with a because they are both the same letter but different character-code. Each character group should be followed by a comma separating it from the next group. Any amount of characters per group are acceptible, and there is no requirement for all the groups to contain the same amount of characters as all the others. Be careful with the character you place first in each group as that will be used as the identifier for the group which gets displayed on the page, e.g. in English a group definition of "Aa" will indicate that we display all the posts in the group, i.e. whose titles begin with either "A" or "a", listed under a heading of "A" (the first character in the definition). */
		$alphabet = __( 'AÁÀÄÂaáàäâ,Bb,Cc,Dd,EÉÈËÊeéèëê,Ff,Gg,Hh,IÍÌÏÎiíìïî,Jj,Kk,Ll,Mm,Nn,OÓÒÖÔoóòöô,Pp,Qq,Rr,Ssß,Tt,UÚÙÜÛuúùüû,Vv,Ww,Xx,Yy,Zz' );
		/* translators: This should be a single character to denote "all entries that didn't fit under one of the alphabet character groups defined". This is used in English to categorise posts whose title begins with a numeric (0 through to 9), or some other character that is not a standard English alphabet letter. */
		$others = __( '#', 'a-z-listing' );

		/**
		 * Filters the alphabet. The string should contain groups of similar or identical characters separated by commas. The first character in each group is the one used for the group title.
		 *
		 * @param string $alphabet The $alphabet
		 */
		$alphabet = apply_filters( 'a_z_listing_alphabet', $alphabet );
		/**
		 * Filters the alphabet. The string should contain groups of similar or identical characters separated by commas. The first character in each group is the one used for the group title.
		 *
		 * @since 1.7.1
		 * @param string $alphabet The $alphabet
		 */
		$alphabet = apply_filters( 'a-z-listing-alphabet', $alphabet );

		/**
		 * Specifies the character used for all non-alphabetic titles, such as numeric titles in the default setup for English. Defaults to '#' unless overidden by a language pack.
		 *
		 * @param string $non_alpha_char The character for non-alphabetic post titles
		 */
		$others = apply_filters( 'a_z_listing_non_alpha_char', $others );
		/**
		 * Specifies the character used for all non-alphabetic titles, such as numeric titles in the default setup for English. Defaults to '#' unless overidden by a language pack.
		 *
		 * @since 1.7.1
		 * @param string $non_alpha_char The character for non-alphabetic post titles
		 */
		$others = apply_filters( 'a-z-listing-non-alpha-char', $others );

		$alphabet_groups = mb_split( ',', $alphabet );
		$letters         = array_reduce(
			$alphabet_groups, function( $return, $group ) {
				$group                 = A_Z_Listing::mb_string_to_array( $group );
				$group_index_character = $group[0];
				$group                 = array_reduce(
					$group, function( $group, $character ) use ( $group_index_character ) {
						$group[ $character ] = $group_index_character;
						return $group;
					}
				);
				if ( ! is_array( $return ) ) {
					return $group;
				}
				return array_merge( $return, $group );
			}
		);

		self::$alphabet        = $letters;
		self::$unknown_letters = $others;
	}

	/**
	 * Find a post's parent post. Will return the original post if the post-type is not hierarchical or the post does not have a parent.
	 *
	 * @since 1.4.0
	 * @param WP_Post|int $page The post whose parent we want to find
	 * @return WP_Post|bool The parent post or the original post if no parents were found. Will be false if the function is called with incorrect arguments.
	 */
	public static function find_post_parent( $page ) {
		if ( ! $page ) {
			return false;
		}
		if ( ! $page instanceof WP_Post ) {
			$page = get_post( $page );
		}
		if ( ! $page->post_parent ) {
			return $page;
		}
		return self::find_post_parent( $page->post_parent );
	}

	/**
	 * Calculate the top-level section of the requested page
	 *
	 * @since 0.1
	 * @param WP_Post|int $page Optional: The post object, or post-ID, of the page whose section we want to find.
	 * @return WP_Post|null The post object of the current section's top-level page.
	 */
	protected static function get_section( $page = 0 ) {
		global $post;

		$pages    = get_pages(
			array(
				'parent' => 0,
			)
		);
		$sections = array_map(
			function( $item ) {
					return $item->post_name;
			}, $pages
		);
		/**
		 * @deprecated Use a_z_listing_sections
		 * @see a_z_listing_sections
		 */
		$sections = apply_filters_deprecated( 'az_sections', array( $sections ), '1.0.0', 'a_z_listing_sections' );
		/**
		 * Override the detected top-level sections for the site. Defaults to contain each page with no post-parent.
		 *
		 * @param array $sections The sections for the site
		 */
		$sections = apply_filters( 'a_z_listing_sections', $sections );
		/**
		 * Override the detected top-level sections for the site. Defaults to contain each page with no post-parent.
		 *
		 * @since 1.7.1
		 * @param array $sections The sections for the site
		 */
		$sections = apply_filters( 'a-z-listing-sections', $sections );

		if ( ! $page ) {
			$page = $post;
		}
		if ( is_int( $page ) ) {
			$page = get_post( $page );
		}

		$section_object = self::find_post_parent( $page );
		$section_name   = null;
		if ( $section_object === $page ) {
			$section_object = null;
		}

		if ( null !== $section_object ) {
			if ( isset( $section_object->post_name ) ) {
				$section_name = $section_object->post_name;
			} else {
				$section_name   = null;
				$section_object = null;
			}
		}

		if ( AZLISTINGLOG ) {
			do_action( 'log', 'A-Z Section selection', $section_name, $sections );
		}

		if ( null !== $section_name && ! in_array( $section_name, $sections, true ) ) {
			$section_name   = null;
			$section_object = null;
		}

		if ( AZLISTINGLOG ) {
			do_action( 'log', 'A-Z Section', $section_name );
		}
		return $section_object;
	}

	/**
	 * Build a WP_Query object to actually fetch the posts
	 *
	 * @since 1.0.0
	 * @param Array|Object|WP_Query Query params as an array/object or WP_Query object
	 * @return WP_Query the query
	 */
	protected function construct_query( $q ) {
		/**
		 * Modify or replace the query
		 *
		 * @since 1.0.0
		 * @param Array|Object|WP_Query $query The query object
		 */
		$q = apply_filters( 'a_z_listing_query', $q );
		/**
		 * Modify or replace the query
		 *
		 * @since 1.7.1
		 * @param Array|Object|WP_Query $query The query object
		 */
		$q = apply_filters( 'a-z-listing-query', $q );

		if ( $q instanceof WP_Query ) {
			return $q;
		}

		$q = wp_parse_args(
			(array) $q, array(
				'post_type'   => 'page',
				'numberposts' => -1,
				'nopaging'    => true,
			)
		);
		return new WP_Query( $q );
	}

	/**
	 * Fetch the query we are currently using
	 *
	 * @since 1.0.0
	 * @return WP_Query The query object
	 */
	public function get_the_query() {
		return $this->query;
	}

	/**
	 * Reducer used by get_the_item_indices() to filter the indices for each post to unique array_values (see: https://secure.php.net/array_reduce)
	 *
	 * @param array $carry Holds the return value of the previous iteration
	 * @param array $value  Holds the value of the current iteration
	 * @return array The previous iteration return value with the current iteration added after running through array_unique()
	 */
	public function index_reduce( $carry, $value ) {
		$v = array_unique( $value );
		if ( ! empty( $v ) ) {
			$carry[] = $v;
		}
		return $carry;
	}

	/**
	 * Find and return the index letter for a post
	 *
	 * @since 1.0.0
	 * @param WP_Post|WP_Term $item The item whose index letters we want to find
	 * @return Array The post's index letters (usually matching the first character of the post title)
	 */
	protected function get_the_item_indices( $item ) {
		$terms        = array();
		$indices      = array();
		$term_indices = array();
		$index        = '';

		if ( $item instanceof WP_Term ) {
			$index               = mb_substr( $item->name, 0, 1, 'UTF-8' );
			$indices[ $index ][] = array(
				'title' => $item->name,
				'item'  => $item,
			);
			/**
			 * @deprecated Use a_z_listing_item_indices
			 * @see a_z_listing_item_indices
			 */
			$indices = apply_filters_deprecated( 'a_z_listing_term_indices', array( $indices, $item ), '1.0.0', 'a_z_listing_item_indices' );
		} else {
			$index               = mb_substr( $item->post_title, 0, 1, 'UTF-8' );
			$indices[ $index ][] = array(
				'title' => $item->post_title,
				'item'  => $item,
			);

			if ( ! empty( $this->index_taxonomy ) ) {
				$terms        = array_filter( wp_get_object_terms( $item->ID, $this->index_taxonomy ) );
				$term_indices = array_reduce(
					$terms, function( $indices, $term ) use ( $item ) {
						$indices[ mb_substr( $term->name, 0, 1, 'UTF-8' ) ][] = array(
							'title' => $term->name,
							'item'  => $item,
						);
						return $indices;
					}
				);

				if ( ! empty( $term_indices ) ) {
					$indices = array_merge( $indices, $term_indices );
				}
			}

			/**
			 * @deprecated Use a_z_listing_item_indices
			 * @see a_z_listing_item_indices
			 */
			$indices = apply_filters_deprecated( 'a_z_listing_post_indices', array( $indices, $item ), '1.5.0', 'a_z_listing_item_indices' );
		} // End if().

		//$indices = array_reduce( $indices, array( $this, 'index_reduce' ) );

		/**
		 * Modify the indice(s) to group this post under
		 *
		 * @param array           $indices The current indices
		 * @param WP_Term|WP_Post $item The item
		 * @param string          $item_type The type of the item
		 */
		$indices = apply_filters( 'a_z_listing_item_indices', $indices, $item, $this->type );
		/**
		 * Modify the indice(s) to group this post under
		 *
		 * @since 1.7.1
		 * @param array           $indices The current indices
		 * @param WP_Term|WP_Post $item The item
		 * @param string          $item_type The type of the item
		 */
		$indices = apply_filters( 'a-z-listing-item-indices', $indices, $item, $this->type );
		if ( AZLISTINGLOG ) {
			do_action( 'log', 'Item indices', $indices );
		}
		return $indices;
	}

	/**
	 * Sort the posts into an Array based on their index letters
	 *
	 * @since 0.8.0
	 * @return Array The posts array keyed by index letter
	 */
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

	/**
	 * Sort the letters to be used as indices and return as an Array
	 *
	 * @since 0.1
	 * @return Array The index letters
	 */
	protected function get_all_indices() {
		$indexed_items = $this->get_indexed_items();

		if ( ! empty( $index[ self::$unknown_letters ] ) ) {
			$this->available_indices[] = self::$unknown_letters;
		}

		foreach ( $this->available_indices as $index ) {
			if ( ! empty( $indexed_items[ $index ] ) ) {
				usort(
					$indexed_items[ $index ], function( $a, $b ) {
						return strcasecmp( $a['title'], $b['title'] );
					}
				);
			}
		}

		return $indexed_items;
	}

	/**
	 * Print the letter links HTML
	 *
	 * @since 1.0.0
	 */
	public function the_letters( $target = '', $style = null ) {
		echo $this->get_the_letters( $target, $style ); // WPCS: XSS OK.
	}

	/**
	 * @since 0.1
	 * @see A_Z_Listing::get_the_letters()
	 * @deprecated use A_Z_Listing::get_the_letters().
	 */
	public function get_letter_display( $target = '', $style = null ) {
		_deprecated_function( __METHOD__, '1.0.0', 'A_Z_Listing::get_the_letters' );
		return $this->get_the_letters( $target, $style );
	}

	/**
	 * Return the letter links HTML
	 *
	 * @since 1.0.0
	 * @param string $target The page to point links toward
	 * @param string $style Optional CSS classes to apply to the output
	 * @return string The letter links HTML
	 */
	public function get_the_letters( $target = '', $style = null ) {
		$classes = array( 'az-links' );
		if ( null !== $style ) {
			if ( is_array( $style ) ) {
				$classes = array_merge( $classes, $style );
			} elseif ( is_string( $style ) ) {
				$c       = explode( ' ', $style );
				$classes = array_merge( $classes, $c );
			}
		}
		$classes = array_unique( $classes );

		$ret   = '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';
		$count = count( $this->available_indices );
		$i     = 0;
		foreach ( $this->available_indices as $letter ) {
			$i++;
			$id = $letter;
			if ( self::$unknown_letters === $id ) {
				$id = '_';
			}

			$classes = array();
			if ( 1 === $i ) {
				array_push( $classes, 'first' );
			} elseif ( $count === $i ) {
				array_push( $classes, 'last' );
			}

			if ( 0 === $i % 2 ) {
				array_push( $classes, 'even' );
			} else {
				array_push( $classes, 'odd' );
			}

			if ( ! empty( $this->matched_item_indices[ $letter ] ) ) {
				array_push( $classes, 'has-posts' );
			} else {
				array_push( $classes, 'no-posts' );
			}

			$ret .= '<li class="' . esc_attr( implode( ' ', $classes ) ) . '">';
			if ( ! empty( $this->matched_item_indices[ $letter ] ) ) {
				$ret .= '<a href="' . esc_url( $target . '#letter-' . $id ) . '">';
			}
			$ret .= '<span>' . esc_html( $this->get_the_letter_title( $letter ) ) . '</span>';
			if ( ! empty( $this->matched_item_indices[ $letter ] ) ) {
				$ret .= '</a>';
			}
			$ret .= '</li>';
		}
		$ret .= '</ul>';
		return $ret;
	}

	/**
	 * Load and execute a theme template
	 *
	 * @since 1.0.0
	 * @param string $template_file The path of the template to execute
	 */
	protected function do_template( $template_file ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$a_z_query = $this;
		/** @noinspection PhpIncludeInspection */
		include $template_file;
	}

	/**
	 * Return the index list HTML created by a theme template
	 *
	 * @since 0.7
	 * @return string The index list HTML
	 */
	public function get_the_listing() {
		if ( 'taxonomy' === $this->type ) {
			$section = $this->taxonomy;
		} else {
			$section = self::get_section();
			if ( $section instanceof WP_Post ) {
				$section = $section->post_name;
			}
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

	/**
	 * @since 0.7
	 * @see A_Z_Listing::have_letters()
	 * @deprecated use A_Z_Listing::have_letters()
	 */
	public function have_a_z_letters() {
		_deprecated_function( __METHOD__, '1.0.0', 'A_Z_Listing::have_letters' );
		return $this->have_letters();
	}

	/**
	 * Used by theme templates. Returns true when we still have letters to iterate.
	 *
	 * @since 1.0.0
	 * @return bool True if we have more letters to iterate, otherwise false
	 */
	public function have_letters() {
		return ( count( $this->available_indices ) > $this->current_letter_index );
	}

	/**
	 * @since 0.7
	 * @see A_Z_Listing::have_items()
	 * @deprecated use A_Z_Listing::have_items()
	 */
	public function have_a_z_posts() {
		_deprecated_function( __METHOD__, '1.0.0', 'have_items' );
		return $this->have_items();
	}

	/**
	 * Used by theme templates. Returns true when we still have items/posts within the current letter.
	 *
	 * To advance the letter use A_Z_Listing::the_letter()
	 * To advance the item/post use A_Z_Listing::the_item()
	 *
	 * @since 1.0.0
	 * @return bool True if there are posts left to iterate within the current letter, otherwise false.
	 */
	public function have_items() {
		return ( is_array( $this->current_letter_items ) && count( $this->current_letter_items ) > $this->current_item_index );
	}

	/**
	 * Advance the Letter Loop onto the next letter
	 *
	 * @since 1.0.0
	 */
	public function the_letter() {
		$this->current_item_index   = 0;
		$this->current_letter_items = array();
		if ( isset( $this->matched_item_indices[ $this->available_indices[ $this->current_letter_index ] ] ) ) {
			$this->current_letter_items = $this->matched_item_indices[ $this->available_indices[ $this->current_letter_index ] ];
		}
		$this->current_letter_index += 1;
	}

	/**
	 * @since 0.7
	 * @see A_Z_Listing::the_item()
	 * @deprecated use A_Z_Listing::the_item()
	 */
	public function the_a_z_post() {
		_deprecated_function( __METHOD__, '1.0.0', 'A_Z_Listing::the_item' );
		$this->the_item();
	}

	/**
	 * Advance the Post loop within the Letter Loop onto the next post
	 *
	 * @since 1.0.0
	 */
	public function the_item() {
		global $post;
		$this->current_item = $this->current_letter_items[ $this->current_item_index ];
		$item_object        = $this->current_item['item'];
		if ( $item_object instanceof WP_Post ) {
			$post = $item_object; // WPCS: Override OK.
			setup_postdata( $post );
		}

		$this->current_item_index += 1;
	}

	/**
	 * Returns the number of letters in the loaded alphabet
	 *
	 * @since 1.0.0
	 * @return int The number of letters
	 */
	public function num_letters() {
		return count( $this->available_indices );
	}

	/**
	 * @since 0.7
	 * @see A_Z_Listing::get_the_letter_count()
	 * @deprecated use A_Z_Listing::get_the_letter_count()
	 */
	public function num_a_z_posts() {
		_deprecated_function( __METHOD__, '1.0.0', 'A_Z_Listing::get_the_letter_count' );
		return $this->get_the_letter_count();
	}

	/**
	 * @since 0.7
	 * @see A_Z_Listing::get_the_letter_count()
	 * @deprecated use A_Z_Listing::get_the_letter_count()
	 */
	public function num_a_z_items() {
		_deprecated_function( __METHOD__, '1.0.0', 'A_Z_Listing::get_the_letter_count' );
		return $this->get_the_letter_count();
	}

	/**
	 * Print the number of posts within the current letter
	 *
	 * @since 1.0.0
	 */
	public function the_letter_count() {
		echo esc_html( $this->get_the_letter_count() );
	}

	/**
	 * Return the number of posts within the current letter
	 *
	 * @since 1.0.0
	 * @return int The number of posts
	 */
	public function get_the_letter_count() {
		return count( $this->current_letter_items );
	}

	/**
	 * Print the escaped ID of the current letter.
	 *
	 * @since 0.7
	 */
	public function the_letter_id() {
		echo esc_attr( $this->get_the_letter_id() );
	}

	/**
	 * Return the ID of the current letter. This is not escaped!
	 *
	 * @since 0.7
	 * @return string The letter ID
	 */
	public function get_the_letter_id() {
		return 'letter-' . self::$alphabet[ $this->available_indices[ $this->current_letter_index - 1 ] ];
	}

	/**
	 * Print the escaped title of the current letter. For example, upper-case A or B or C etc.
	 *
	 * @since 0.7
	 */
	public function the_letter_title( $index = '' ) {
		echo esc_html( $this->get_the_letter_title( $index ) );
	}

	/**
	 * Return the title of the current letter. For example, upper-case A or B or C etc. This is not escaped!
	 *
	 * @since 0.7
	 * @since 1.8.0 Add filters to modify the title of the letter.
	 * @return string The letter title
	 */
	public function get_the_letter_title( $index = '' ) {
		if ( '' !== $index ) {
			$letter = self::$alphabet[ $index ];
		} else {
			$letter = self::$alphabet[ $this->available_indices[ $this->current_letter_index - 1 ] ];
		}

		/**
		 * Modify the letter title or heading
		 *
		 * @since 1.8.0
		 * @param string $letter The title of the letter
		 */
		$letter = apply_filters( 'the_a_z_letter_title', $letter );
		/**
		 * Modify the letter title or heading
		 *
		 * @since 1.8.0
		 * @param string $letter The title of the letter
		 */
		$letter = apply_filters( 'the-a-z-letter-title', $letter );

		return $letter;
	}

	/**
	 * Print the escaped title of the current post
	 *
	 * @since 1.0.0
	 */
	public function the_title() {
		echo esc_html( $this->get_the_title() );
	}

	/**
	 * Return the title of the current post. This is not escaped!
	 *
	 * @since 1.0.0
	 * @return string The post title
	 */
	public function get_the_title() {
		$title = $this->current_item['title'];
		$item  = $this->current_item['item'];
		if ( $item instanceof WP_Post ) {
			$title = apply_filters( 'the_title', $title, $item->ID );
		} elseif ( $item instanceof WP_Term ) {
			$title = apply_filters( 'term_name', $title, $item );
		}
		return $title;
	}

	/**
	 * Print the escaped permalink of the current post.
	 *
	 * @since 1.0.0
	 */
	public function the_permalink() {
		echo esc_url( $this->get_the_permalink() );
	}

	/**
	 * Return the permalink of the current post. This is not escaped!
	 *
	 * @since 1.0.0
	 * @return string The permalink
	 */
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
 *
 * @param  array|string|WP_Query|A_Z_Listing  $query      a valid WordPress query or an A_Z_Listing instance
 * @param  bool                               $use_cache  use the plugin's in-built query cache
 * @return A_Z_Listing                                    a new or previously-saved instance of A_Z_Listing using the provided construct_query
 */
function a_z_listing_cache( $query = null, $use_cache = true ) {
	static $cache = array();

	if ( $query instanceof A_Z_Listing ) {
		// we received a valid A_Z_Listing instance so we get the query from it for the cache lookup/save key.
		if ( true === $use_cache ) {
			$key = wp_json_encode( $query->get_the_query() );
			if ( array_key_exists( $key, $cache ) ) {
				return $cache[ $key ];
			}

			$cache[ $key ] = $query;
		}
		return $query;
	}

	// check the cache and return any pre-existing A_Z_Listing instance we have.
	$key = wp_json_encode( $query );
	if ( null !== $query && true === $use_cache && array_key_exists( $key, $cache ) ) {
		return $cache[ $key ];
	}

	// if $query is $obj then we did not get an A_Z_Listing instance as our argument, so we will make a new one.
	$obj = new A_Z_Listing( $query );

	if ( true === $use_cache ) {
		// save the new A_Z_Listing instance into the cache.
		$cache[ $key ] = $obj;
	}

	// return the new A_Z_Listing instance.
	return $obj;
}
