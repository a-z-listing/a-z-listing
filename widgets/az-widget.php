<?php
/**
 * Definition for the a-z-listing's main widget.
 * @package  a-z-listing
 */

/**
 * Definition for the AZ_Widget which displays alphabetically-ordered list of latin letters linking to the A-Z Listing page.
 */
class AZ_Widget extends WP_Widget {
	/**
	 * Register the widget's meta information.
	 */
	function __construct() {
		parent::__construct('bh_az_widget', __( 'A-Z Site Map', 'a-z-listing' ), array(
			'classname' => 'a-z-listing-widget',
			'description' => __( 'Alphabetised links to the A-Z site map', 'a-z-listing' ),
		));
	}

	/**
	 * Deprecated constructor.
	 */
	function AZ_Widget() {
		$this->__construct();
	}

	/**
	 * Print-out the configuration form for the widget.
	 * @param  Array $instance Widget instance as provided by WordPress core.
	 */
	function form( $instance ) {
		$title = $instance['title'];
		$titleID = $this->get_field_id( 'title' );
		$titleName = $this->get_field_name( 'title' );

		$post = isset( $instance['post'] ) ? $instance['post'] : ( isset( $instance['page'] ) ? $instance['page'] : 0 );
		$postID = $this->get_field_id( 'post' );
		$postName = $this->get_field_name( 'post' );

		?>
		<div><label for="<?php echo esc_attr( $postID ); ?>">
			<?php esc_html_e( 'Site map A-Z page', 'a-z-listing' ); ?>
		</label></div>
		<?php
		wp_dropdown_pages(array(
			'id' => intval( $postID ),
			'name' => esc_html( $postName ),
			'selected' => intval( $post ),
		));
		?>
		<div><label for="<?php echo esc_attr( $titleID ); ?>">
			<?php esc_html_e( 'Widget Title', 'a-z-listing' ); ?>
		</label></div>
		<input class="widefat" type="text"
				id="<?php echo esc_attr( $titleID ); ?>"
				name="<?php echo esc_attr( $titleName ); ?>"
				placeholder="<?php esc_attr_e( 'Widget Title', 'a-z-listing' ); ?>"
				value="<?php echo esc_attr( $title ); ?>" />
		<p style="color: #333;">
			<?php esc_html_e( 'Leave blank to use the title specified by the page', 'a-z-listing' ); ?>
		</p>
		<?php
	}

	/**
	 * Called by WordPress core. Sanitises changes to the Widget's configuration.
	 * @param  Array $new_instance the new configuration values.
	 * @param  Array $old_instance the previous configuration values.
	 * @return Array               sanitised version of the new configuration values to be saved
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post'] = (int) $new_instance['post'];
		return $instance;
	}

	/**
	 * Print the user-visible widget to the page.
	 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
	 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
	 */
	function widget( $args, $instance ) {
		the_section_az_widget( $args, $instance );
	}
}

/**
 * Print the user-visible widget to the page implentation.
 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
 */
function the_section_az_widget( $args, $instance ) {
	echo get_the_section_az_widget( $args, $instance ); // WPCS: XSS OK.
}

/**
 * Get the user-visible widget html.
 * @param  Array $args     General widget configuration. Often shared between all widgets on the site.
 * @param  Array $instance Configuration of this Widget. Unique to this invocation.
 * @return  string The complete A-Z Widget HTML ready for echoing to the page.
 */
function get_the_section_az_widget( $args, $instance ) {
	extract( $args );

	$instance = wp_parse_args( $instance, array(
		'title' => '',
		'post' => 0,
	) );

	if ( $instance['post'] ) {
		$target = get_post( (int) $instance['post'] );
	} else {
		return;
	}

	$title = $instance['title'];
	if ( empty( $title ) ) {
		$title = $target->post_title;
	}

	$ret = $before_widget; // WPCS: XSS OK.
	$ret .= $before_title.esc_html( $title ).$after_title; // WPCS: XSS OK.
	$ret .= '<div class="az-letters">';
	$ret .= get_the_az_letters( null, get_permalink( $target ) );
	$ret .= '<div class="clear empty"></div></div>';
	$ret .= $after_widget; // WPCS: XSS OK.

	return $ret;
}
