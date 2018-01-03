<?php
/**
 * Definition for the a-z-listing's main widget.
 * @package  a-z-listing
 */

/**
 * Definition for the AZ_Widget which displays alphabetically-ordered list of latin letters linking to the A-Z Listing page.
 *
 * @since 0.1
 */
class A_Z_Widget extends WP_Widget {
	/**
	 * Register the widget's meta information.
	 *
	 * @since 0.1
	 */
	public function __construct() {
		parent::__construct(
			'bh_az_widget', __( 'A-Z Site Map', 'a-z-listing' ), array(
				'classname'   => 'a-z-listing-widget',
				'description' => __( 'Alphabetised links to the A-Z site map', 'a-z-listing' ),
			)
		);

		if ( is_active_widget( false, false, $this->id_base, true ) ) {
			a_z_listing_add_styling();
		}
	}

	/**
	 * Deprecated constructor.
	 *
	 * @since 0.1
	 */
	public function A_Z_Widget() {
		$this->__construct();
	}

	/**
	 * Print-out the configuration form for the widget.
	 *
	 * @since 0.1
	 * @param  Array $instance Widget instance as provided by WordPress core.
	 * @return void
	 */
	public function form( $instance ) {
		$title      = $instance['title'];
		$title_id   = $this->get_field_id( 'title' );
		$title_name = $this->get_field_name( 'title' );

		$post      = isset( $instance['post'] ) ? $instance['post'] : ( isset( $instance['page'] ) ? $instance['page'] : 0 );
		$post_id   = $this->get_field_id( 'post' );
		$post_name = $this->get_field_name( 'post' );

		$post_type      = isset( $instance['post_type'] ) ? $instance['post_type'] : 'page';
		$post_type_id   = $this->get_field_id( 'post_type' );
		$post_type_name = $this->get_field_name( 'post_type' );
		?>
		<div><label for="<?php echo esc_attr( $title_id ); ?>">
				<?php esc_html_e( 'Widget Title', 'a-z-listing' ); ?>
			</label></div>
		<input class="widefat" type="text"
			id="<?php echo esc_attr( $title_id ); ?>"
			name="<?php echo esc_attr( $title_name ); ?>"
			placeholder="<?php esc_attr_e( 'Widget Title', 'a-z-listing' ); ?>"
			value="<?php echo esc_attr( $title ); ?>" />

		<p style="color: #333;">
			<?php esc_html_e( 'Leave the title field blank, above, to use the title from the page set in the next field', 'a-z-listing' ); ?>
		</p>

		<div><label for="<?php echo esc_attr( $post_id ); ?>">
				<?php esc_html_e( 'Site map A-Z page', 'a-z-listing' ); ?>
			</label></div>
		<?php
		wp_dropdown_pages(
			array(
				'id'       => intval( $post_id ),
				'name'     => esc_html( $post_name ),
				'selected' => intval( $post ),
			)
		);
		?>

		<div><label for="<?php echo esc_attr( $post_type_id ); ?>">
				<?php esc_html_e( 'Post-type to display', 'a-z-listing' ); ?>
			</label></div>
		<?php
		$post_types = get_post_types();
		sort( $post_types );
		?>
		<select id="<?php echo esc_attr( $post_type_id ); ?>" name="<?php echo esc_attr( $post_type_name ); ?>">
			<?php foreach ( $post_types as $t ) : ?>
				<option value="<?php echo esc_attr( $t ); ?>"
											<?php
											if ( $post_type === $t ) {
												echo 'selected'; }
?>
>
					<?php echo esc_html( $t ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Called by WordPress core. Sanitises changes to the Widget's configuration.
	 *
	 * @since 0.1
	 * @param  Array $new_instance the new configuration values.
	 * @param  Array $old_instance the previous configuration values.
	 * @return Array               sanitised version of the new configuration values to be saved
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['post']      = (int) $new_instance['post'];
		$instance['post_type'] = strip_tags( $new_instance['post_type'] );
		$instance['parent']    = (int) $new_instance['parent'];
		return $instance;
	}

	/**
	 * Print the user-visible widget to the page.
	 *
	 * @since 0.1
	 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
	 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
	 */
	public function widget( $args, $instance ) {
		the_section_az_widget( $args, $instance );
	}
}

/**
 * @see A_Z_Widget::the_section_a_z_widget()
 *
 * @since 0.1
 * @deprecated use the_section_a_z_widget()
 */
function the_section_az_widget( $args, $instance ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'the_section_a_z_widget' );
	the_section_a_z_widget( $args, $instance );
}

/**
 * Print the user-visible widget to the page implentation.
 *
 * @since 0.8.0
 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
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
 */
function get_the_section_az_widget( $args, $instance ) {
	_deprecated_function( __FUNCTION__, '0.8.0', 'get_the_section_a_z_widget' );
	return get_the_section_a_z_widget( $args, $instance );
}

/**
 * Get the user-visible widget html.
 *
 * @since 0.8.0
 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
 * @return  string The complete A-Z Widget HTML ready for echoing to the page.
 */
function get_the_section_a_z_widget( $args, $instance ) {
	$classes  = array( 'az-letters' );
	$instance = wp_parse_args(
		$instance, array(
			'title' => '',
			'post'  => 0,
		)
	);

	if ( $instance['post'] ) {
		if ( $instance['post'] instanceof WP_Post ) {
			$target = $instance['post'];
		} elseif ( is_int( $instance['post'] ) && 0 < $instance['post'] ) {
			$target = get_post( (int) $instance['post'] );
		} else {
			return null;
		}
	} else {
		return null;
	}

	$title = $instance['title'];
	if ( empty( $title ) ) {
		$title = $target->post_title;
	}

	$post_type = ( isset( $instance['post_type'] ) ) ? $instance['post_type'] : 'page';
	$my_query  = array(
		'post_type' => $post_type,
	);

	if ( isset( $instance['taxonomy'] ) && isset( $instance['terms'] ) ) {
		if ( ! empty( $instance['taxonomy'] ) && ! empty( $instance['terms'] ) ) {
			$my_query['tax_query'] = array(
				'taxonomy' => $instance['taxonomy'],
				'terms'    => $instance['terms'],
			);
		}
	}

	if ( 'page' === $my_query['post_type'] ) {
		$parent = A_Z_Listing::find_post_parent( $target );
		if ( $parent->ID !== $target->ID ) {
			$my_query['child_of'] = $parent->ID;
		}
	}

	$a_z_query = new A_Z_Listing( $my_query );

	$ret = '';

	ob_start();

	echo $args['before_widget']; // WPCS: XSS OK.
	echo $args['before_title'];  // WPCS: XSS OK.
	echo esc_html( $title );
	echo $args['after_title']; // WPCS: XSS OK.
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php $a_z_query->the_letters( get_permalink( $target ), null ); ?>
		<div class="clear empty"></div>
	</div>
	<?php
	echo $args['after_widget']; // WPCS: XSS OK.

	$ret = ob_get_clean();
	return $ret;
}
