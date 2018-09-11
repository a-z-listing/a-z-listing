<?php
/**
 * Definition for the a-z-listing's main widget
 *
 * @package  a-z-listing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Definition for the AZ_Widget which displays alphabetically-ordered list of latin letters linking to the A-Z Listing page
 *
 * @since 0.1
 */
class A_Z_Listing_Widget extends WP_Widget {
	/**
	 * Register the widget's meta information
	 *
	 * @since 0.1
	 */
	public function __construct() {
		parent::__construct(
			'bh_az_widget',
			__( 'A-Z Site Map', 'a-z-listing' ),
			array(
				'classname'   => 'a-z-listing-widget',
				'description' => __( 'Alphabetised links to the A-Z site map', 'a-z-listing' ),
			)
		);

		if ( is_active_widget( false, false, $this->id_base, true ) ) {
			a_z_listing_do_enqueue();
		}
	}

	/**
	 * Deprecated constructor
	 *
	 * @since 0.1
	 */
	public function A_Z_Widget() {
		$this->__construct();
	}

	/**
	 * Print-out the configuration form for the widget
	 *
	 * @since 0.1
	 * @param  array $instance Widget instance as provided by WordPress core.
	 * @return void
	 */
	public function form( $instance ) {
		$args = array(
			'public' => true,
		);

		$public_post_types = get_post_types( $args, 'objects' );
		$public_taxonomies = get_taxonomies( $args, 'objects' );

		$widget_title      = $instance['title'];
		$widget_title_id   = $this->get_field_id( 'title' );
		$widget_title_name = $this->get_field_name( 'title' );

		$display_type      = $instance['type'];
		$display_type_id   = $this->get_field_id( 'type' );
		$display_type_name = $this->get_field_name( 'type' );

		$target_post            = isset( $instance['post'] ) ? $instance['post'] : ( isset( $instance['page'] ) ? $instance['page'] : 0 );
		$target_post_id         = $this->get_field_id( 'post' );
		$target_post_name       = $this->get_field_name( 'post' );
		$target_post_title      = ( $target_post > 0 ) ? get_the_title( $target_post ) : '';
		$target_post_title_id   = $this->get_field_id( 'target_post_title' );
		$target_post_title_name = $this->get_field_name( 'target_post_title' );

		$listing_post_type            = isset( $instance['post_type'] ) ? $instance['post_type'] : 'page';
		$listing_post_type_id         = $this->get_field_id( 'post_type' );
		$listing_post_type_name       = $this->get_field_name( 'post_type' );
		$listing_post_type_wrapper_id = $this->get_field_id( 'post_type_wrapper' );

		$listing_taxonomy            = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : 'page';
		$listing_taxonomy_id         = $this->get_field_id( 'taxonomy' );
		$listing_taxonomy_name       = $this->get_field_name( 'taxonomy' );
		$listing_taxonomy_wrapper_id = $this->get_field_id( 'taxonomy_wrapper' );

		$listing_parent_term            = isset( $instance['parent_term'] ) ? $instance['parent_term'] : '';
		$listing_parent_term_id         = $this->get_field_id( 'parent_term' );
		$listing_parent_term_name       = $this->get_field_name( 'parent_term' );
		$listing_parent_term_wrapper_id = $this->get_field_id( 'parent_term_wrapper' );

		$listing_terms_include      = isset( $instance['terms'] ) ? $instance['terms'] : '';
		$listing_terms_include_id   = $this->get_field_id( 'terms' );
		$listing_terms_include_name = $this->get_field_name( 'terms' );

		$listing_terms_exclude            = isset( $instance['terms_exclude'] ) ? $instance['terms_exclude'] : '';
		$listing_terms_exclude_id         = $this->get_field_id( 'terms_exclude' );
		$listing_terms_exclude_name       = $this->get_field_name( 'terms_exclude' );
		$listing_terms_exclude_wrapper_id = $this->get_field_id( 'terms_exclude_wrapper' );

		$listing_hide_empty_terms            = isset( $instance['hide_empty_terms'] ) ? $instance['hide_empty_terms'] : '';
		$listing_hide_empty_terms_id         = $this->get_field_id( 'hide_empty_terms' );
		$listing_hide_empty_terms_name       = $this->get_field_name( 'hide_empty_terms' );
		$listing_hide_empty_terms_wrapper_id = $this->get_field_id( 'hide_empty_terms_wrapper' );
		?>

		<div>
			<p>
				<label for="<?php echo esc_attr( $widget_title_id ); ?>">
					<?php esc_html_e( 'Widget Title', 'a-z-listing' ); ?>
				</label>
				<input class="widefat" type="text"
					id="<?php echo esc_attr( $widget_title_id ); ?>"
					name="<?php echo esc_attr( $widget_title_name ); ?>"
					placeholder="<?php esc_attr_e( 'Widget Title', 'a-z-listing' ); ?>"
					value="<?php echo esc_attr( $widget_title ); ?>" />
			</p>
			<p style="color: #333;">
				<?php esc_html_e( 'Leave the title field blank, above, to use the title from the page set in the next field', 'a-z-listing' ); ?>
			</p>
		</div>

		<div>
			<p>
				<label for="<?php echo esc_attr( $target_post_id ); ?>">
					<?php esc_html_e( 'Site map A-Z page', 'a-z-listing' ); ?>
				</label>
				<input class="widefat" type="text"
					id="<?php echo esc_attr( $target_post_title_id ); ?>"
					name="<?php echo esc_attr( $target_post_title_name ); ?>"
					value="<?php echo esc_attr( $target_post_title ); ?>" />
				<input type="hidden"
					id="<?php echo esc_attr( $target_post_id ); ?>"
					name="<?php echo esc_attr( $target_post_name ); ?>"
					value="<?php echo esc_attr( $target_post ); ?>" />
			</p>
		</div>

		<div>
			<p>
				<label for="<?php echo esc_attr( $display_type_id ); ?>">
					<?php esc_html_e( 'Display posts or terms?', 'a-z-listing' ); ?>
				</label>
				<select class="widefat"
					id="<?php echo esc_attr( $display_type_id ); ?>"
					name="<?php echo esc_attr( $display_type_name ); ?>">
					<option value="posts"
						<?php echo ( 'terms' !== $display_type ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Posts', 'a-z-listing' ); ?>
					</option>
					<option value="terms"
						<?php echo ( 'terms' === $display_type ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Taxonomy terms', 'a-z-listing' ); ?>
					</option>
				</select>
			</p>
		</div>

		<div <?php echo ( 'terms' !== $display_type ) ? '' : 'style="display: none;"'; ?>
			id="<?php echo esc_attr( $listing_post_type_wrapper_id ); ?>">
			<p>
				<label for="<?php echo esc_attr( $listing_post_type_id ); ?>">
					<?php esc_html_e( 'Post-type to display', 'a-z-listing' ); ?>
				</label>
				<select class="widefat"
					id="<?php echo esc_attr( $listing_post_type_id ); ?>"
					name="<?php echo esc_attr( $listing_post_type_name ); ?>"
					<?php echo ( 'terms' !== $display_type ) ? '' : 'disabled'; ?>>
					<?php foreach ( $public_post_types as $k => $t ) : ?>
						<option value="<?php echo esc_attr( $k ); ?>"
							<?php echo ( $k === $listing_post_type ) ? 'selected' : ''; ?>>
							<?php echo esc_html( $t->labels->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>

		<div <?php echo ( 'terms' === $display_type ) ? '' : 'style="display: none;"'; ?>
			id="<?php echo esc_attr( $listing_taxonomy_wrapper_id ); ?>">
			<p>
				<label for="<?php echo esc_attr( $listing_taxonomy_id ); ?>">
					<?php esc_html_e( 'Taxonomy to display', 'a-z-listing' ); ?>
				</label>
				<select class="widefat"
					id="<?php echo esc_attr( $listing_taxonomy_id ); ?>"
					name="<?php echo esc_attr( $listing_taxonomy_name ); ?>"
					<?php echo ( 'terms' === $display_type ) ? '' : 'disabled'; ?>>
					<?php foreach ( $public_taxonomies as $k => $t ) : ?>
						<option value="<?php echo esc_attr( $k ); ?>"
							<?php echo ( $k === $listing_taxonomy ) ? 'selected' : ''; ?>>
							<?php echo esc_html( $t->labels->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>

		<div <?php echo ( 'terms' === $display_type ) ? '' : 'style="display: none;"'; ?>
			id="<?php echo esc_attr( $listing_parent_term_wrapper_id ); ?>">
			<p>
				<label for="<?php echo esc_attr( $listing_parent_term_id ); ?>">
					<?php esc_html_e( 'Parent term to display children of', 'a-z-listing' ); ?>
				</label>
				<input class="widefat" type="text"
					id="<?php echo esc_attr( $listing_parent_term_id ); ?>"
					name="<?php echo esc_attr( $listing_parent_term_name ); ?>"
					value="<?php echo esc_attr( $listing_parent_term ); ?>" />
			</p>
		</div>

		<div>
			<p>
				<label for="<?php echo esc_attr( $listing_terms_include_id ); ?>">
					<?php esc_html_e( 'Terms to include (IDs)', 'a-z-listing' ); ?>
				</label>
				<input class="widefat" type="text"
					id="<?php echo esc_attr( $listing_terms_include_id ); ?>"
					name="<?php echo esc_attr( $listing_terms_include_name ); ?>"
					value="<?php echo esc_attr( $listing_terms_include ); ?>" />
			</p>
		</div>

		<div <?php echo ( 'terms' === $display_type ) ? '' : 'style="display: none;"'; ?>
			id="<?php echo esc_attr( $listing_terms_exclude_wrapper_id ); ?>">
			<p>
				<label for="<?php echo esc_attr( $listing_terms_exclude_id ); ?>">
					<?php esc_html_e( 'Terms to exclude (IDs)', 'a-z-listing' ); ?>
				</label>
				<input class="widefat" type="text"
					id="<?php echo esc_attr( $listing_terms_exclude_id ); ?>"
					name="<?php echo esc_attr( $listing_terms_exclude_name ); ?>"
					value="<?php echo esc_attr( $listing_terms_exclude ); ?>" />
			</p>
		</div>

		<div <?php echo ( 'terms' === $display_type ) ? '' : 'style="display: none;"'; ?>
			id="<?php echo esc_attr( $listing_hide_empty_terms_wrapper_id ); ?>">
			<p>
				<label for="<?php echo esc_attr( $listing_hide_empty_terms_id ); ?>">
					<?php esc_html_e( 'Hide empty terms', 'a-z-listing' ); ?>
				</label>
				<input type="checkbox"
					id="<?php echo esc_attr( $listing_hide_empty_terms_id ); ?>"
					name="<?php echo esc_attr( $listing_hide_empty_terms_name ); ?>"
					<?php echo ( isset( $listing_hide_empty_terms ) && true === $listing_hide_empty_terms ) ? 'checked' : ''; ?> />
			</p>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				const target_post                      = document.getElementById( '<?php echo esc_html( $target_post_id ); ?>' );
				const target_post_title                = document.getElementById( '<?php echo esc_html( $target_post_title_id ); ?>' );
				const display_type                     = document.getElementById( '<?php echo esc_html( $display_type_id ); ?>' );
				const listing_post_type                = document.getElementById( '<?php echo esc_html( $listing_post_type_id ); ?>' );
				const listing_post_type_wrapper        = document.getElementById( '<?php echo esc_html( $listing_post_type_wrapper_id ); ?>' );
				const listing_taxonomy                 = document.getElementById( '<?php echo esc_html( $listing_taxonomy_id ); ?>' );
				const listing_taxonomy_wrapper         = document.getElementById( '<?php echo esc_html( $listing_taxonomy_wrapper_id ); ?>' );
				const listing_parent_term              = document.getElementById( '<?php echo esc_html( $listing_parent_term_id ); ?>' );
				const listing_parent_term_wrapper      = document.getElementById( '<?php echo esc_html( $listing_parent_term_wrapper_id ); ?>' );
				const listing_hide_empty_terms         = document.getElementById( '<?php echo esc_html( $listing_hide_empty_terms ); ?>' );
				const listing_hide_empty_terms_wrapper = document.getElementById( '<?php echo esc_html( $listing_hide_empty_terms_wrapper_id ); ?>' );

				function switch_taxonomy_or_posts() {
					if ( 'terms' === display_type.value ) {
						listing_post_type.setAttribute( 'disabled', 'disabled' );
						listing_post_type_wrapper.style.display = 'none';
						listing_taxonomy.removeAttribute( 'disabled' );
						listing_taxonomy_wrapper.style.display = 'unset';
						listing_parent_term.removeAttribute( 'disabled' );
						listing_parent_term_wrapper.style.display = 'unset';
						listing_hide_empty_terms.removeAttribute( 'disabled' );
						listing_hide_empty_terms.style.display = 'unset';
					} else {
						listing_post_type.removeAttribute( 'disabled' );
						listing_post_type_wrapper.style.display = 'unset';
						listing_taxonomy.setAttribute( 'disabled', 'disabled' );
						listing_taxonomy_wrapper.style.display = 'none';
						listing_parent_term.setAttribute( 'disabled', 'disabled' );
						listing_parent_term_wrapper.style.display = 'none';
						listing_hide_empty_terms.setAttribute( 'disabled', 'disabled' );
						listing_hide_empty_terms_wrapper.style.display = 'none';
					}
				}
				switch_taxonomy_or_posts();
				display_type.addEventListener( 'change', switch_taxonomy_or_posts );

				$( target_post_title ).autocomplete( {
					source: function( post_title, response ) {
						$.ajax( {
							url:      '/wp-admin/admin-ajax.php',
							type:     'POST',
							dataType: 'json',
							data: {
								action:     'get_a_z_listing_autocomplete_post_titles',
								post_title,
							},
							success: function( data ) {
								response( data );
							},
							error: function() {
								response();
							},
						} );
					},
					select: function( event, ui ) {
						event.preventDefault();
						target_post.value       = ui.item.value;
						target_post_title.value = ui.item.label;
					},
				} );
			} );
		</script>
		<?php
	}

	/**
	 * Called by WordPress core. Sanitises changes to the Widget's configuration
	 *
	 * @since 0.1
	 * @param  array $new_instance the new configuration values.
	 * @param  array $old_instance the previous configuration values.
	 * @return array               sanitised version of the new configuration values to be saved
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']             = strip_tags( $new_instance['title'] );
		$instance['type']              = strip_tags( $new_instance['type'] );
		$instance['post']              = (int) $new_instance['post'];
		$instance['target_post_title'] = strip_tags( $new_instance['target_post_title'] );
		$instance['post_type']         = strip_tags( $new_instance['post_type'] );
		$instance['parent']            = (int) $new_instance['parent'];
		$instance['taxonomy']          = strip_tags( $new_instance['taxonomy'] );
		$instance['parent_term']       = strip_tags( $new_instance['parent_term'] );
		$instance['terms']             = strip_tags( $new_instance['terms'] );
		$instance['exclude_terms']     = strip_tags( $new_instance['exclude_terms'] );
		$instance['hide_empty_terms']  = 'checked' === $new_instance['hide_empty_terms'] ? 'true' : 'false';

		if ( empty( $instance['target_post_title'] ) ) {
			$instance['post'] = 0;
		}

		return $instance;
	}

	/**
	 * Print the user-visible widget to the page
	 *
	 * @since 0.1
	 * @param  array $args     General widget configuration. Often shared between all widgets on the site.
	 * @param  array $instance Configuration of this Widget. Unique to this invocation.
	 */
	public function widget( $args, $instance ) {
		the_section_a_z_widget( $args, $instance );
	}
}

/**
 * Print the user-visible widget to the page implentation
 *
 * @since 0.1
 * @see A_Z_Widget::the_section_a_z_widget()
 * @deprecated use the_section_a_z_widget()
 * @param  array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  array $instance Configuration of this Widget. Unique to this invocation.
 */
function the_section_az_widget( $args, $instance ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'the_section_a_z_widget' );
	the_section_a_z_widget( $args, $instance );
}

/**
 * Print the user-visible widget to the page implentation
 *
 * @since 0.8.0
 * @param  array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  array $instance Configuration of this Widget. Unique to this invocation.
 */
function the_section_a_z_widget( $args, $instance ) {
	echo get_the_section_a_z_widget( $args, $instance ); // WPCS: XSS OK.
}

/**
 * Deprecated print the user-visible widget to the page implementation.
 *
 * @since 0.1
 * @see A_Z_Widget::get_the_section_a_z_widget()
 * @deprecated use get_the_section_a_z_widget()
 *
 * @param  array $args General widget configuration. Often shared between all widgets on the site.
 * @param  array $instance Configuration of this Widget. Unique to this invocation.
 *
 * @return string
 */
function get_the_section_az_widget( $args, $instance ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'get_the_section_a_z_widget' );
	return get_the_section_a_z_widget( $args, $instance );
}

/**
 * Get the user-visible widget html
 *
 * @since 0.8.0
 * @param  array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  array $instance Configuration of this Widget. Unique to this invocation.
 * @return  string The complete A-Z Widget HTML ready for echoing to the page.
 */
function get_the_section_a_z_widget( $args, $instance ) {
	do_action( 'log', 'A-Z Listing: Running widget' );

	$instance = wp_parse_args(
		$instance,
		array(
			'title'            => '',
			'target'           => -1,
			'type'             => 'posts',
			'taxonomy'         => '',
			'post_type'        => 'page',
			'terms'            => '',
			'exclude_terms'    => '',
			'parent_term'      => '',
			'hide_empty_terms' => false,
		)
	);

	$title  = esc_html( $instance['title'] );
	$target = '';
	if ( empty( $title ) ) {
		if ( $instance['target'] > 0 ) {
			$title  = get_the_title( $instance['target'] );
			$target = get_the_permalink( $instance['target'] );
		} else {
			$title = esc_html__( 'A-Z Listing', 'a-z-listing' );
		}
	}

	$hide_empty = true === $instance['hide_empty_terms'] ? 'true' : 'false';

	$ret  = '';
	$ret .= $args['before_widget'];

	$ret .= $args['before_title'];
	$ret .= $title;
	$ret .= $args['after_title'];

	$ret .= do_shortcode(
		"[a-z-listing
			return='letters'
			target='{$target}'
			display='{$instance['type']}'
			taxonomy='{$instance['taxonomy']}'
			alphabet=''
			group-numbers=''
			grouping=''
			numbers='hide'
			post-type='{$instance['post_type']}'
			terms='{$instance['terms']}'
			exclude-terms='{$instance['exclude_terms']}'
			parent-term='{$instance['parent_term']}'
			hide-empty-terms='{$hide_empty}'
		]"
	);

	$ret .= $args['after_widget'];

	return $ret;
}

/**
 * Ajax responder for A_Z_Listing_Widget configuration
 */
function a_z_listing_autocomplete_post_titles() {
	global $wpdb;

	$post_title = '%' . $wpdb->esc_like( stripslashes( $_POST['post_title']['term'] ) ) . '%';

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT `ID`, `post_title` FROM `$wpdb->posts`
			WHERE `post_title` LIKE %s AND `post_status` = 'publish'",
			$post_title
		)
	);

	$titles = array();
	foreach ( $results as $result ) {
		$titles[] = array(
			'value' => intval( $result->ID ),
			'label' => addslashes( $result->post_title ),
		);
	}

	echo json_encode( $titles );

	exit();
}
add_action( 'wp_ajax_nopriv_get_a_z_listing_autocomplete_post_titles', 'a_z_listing_autocomplete_post_titles' );
add_action( 'wp_ajax_get_a_z_listing_autocomplete_post_titles', 'a_z_listing_autocomplete_post_titles' );

/**
 * Register the A_Z_Widget widget
 *
 * @since 2.0.0
 */
function a_z_listing_widget() {
	register_widget( 'A_Z_Listing_Widget' );
}
add_action( 'widgets_init', 'a_z_listing_widget' );

/**
 * Enqueue the jquery-ui autocomplete script
 */
function a_z_listing_autocomplete_script() {
	wp_enqueue_script( 'jquery-ui-autocomplete' );
}
add_action( 'admin_enqueue_scripts', 'a_z_listing_autocomplete_script' );
