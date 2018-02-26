( function( wp ) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 * @see https://github.com/WordPress/gutenberg/tree/master/element#element
	 */
	var el = wp.element.createElement;
	/**
	 * Retrieves the translation of text.
	 * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
	 */
	var __ = wp.i18n.__;

	var map = Array.map,
	    keys = Object.keys;

	var withAPIData = wp.components.withAPIData;

	var InspectorControls = wp.blocks.InspectorControls,
	    RangeControl      = wp.components.RangeControl,
	    SelectControl     = wp.components.SelectControl,
	    TextControl       = wp.components.TextControl,
	    ToggleControl     = wp.components.ToggleControl;

	function getFilteredTaxonomies( props ) {
		if ( 'posts' === props.attributes.display ) {
			let r = map( props.postTypes.data[ props.attributes['post-type'] ].taxonomies, ( tax, idx ) => ( {
					value: tax,
					label: props.taxonomies.data[ tax ].name || taxonomy
				} ) );
			r.unshift( { value: '', label: '' } );
			return r;
		}

		let r = map( keys( props.taxonomies.data ), ( tax, idx ) => ( {
				value: tax,
				label: props.taxonomies.data[ tax ].name,
			} ) );
		r.unshift( { value: '', label: '' } );
		return r;
	}

	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/block-api/
	 */
	registerBlockType( 'a-z-listing/a-z-listing', {
		title: __( 'A-Z Listing' ),
		icon: 'translation',

		/**
		 * Blocks are grouped into categories to help users browse and discover them.
		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
		 */
		category: 'widgets',
		supports: {
			// Removes support for an HTML mode.
			html: false,
		},

		attributes: {
			display:         { type: 'string',  default: 'posts' },
			'post-type':     { type: 'string',  default: 'page'  },
			terms:           { type: 'string',  default: ''      },
			taxonomy:        { type: 'string',  default: ''      },
			numbers:         { type: 'string',  default: ''      },
			grouping:        { type: 'number',  default: 1       },
			'group-numbers': { type: 'boolean', default: false   },
		},

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		edit: withAPIData( function() {
			return {
				postTypes: '/wp/v2/types',
				taxonomies: '/wp/v2/taxonomies'
			};
		} )( function( props ) {
			if ( ! props.postTypes.data || ! props.taxonomies.data ) {
				return __( "Loading..." );
			}

			function onChange( prop ) {
				return function( val ) {
					var propObj = {}
					propObj[prop] = val
					props.setAttributes( propObj );
				}
			}

			var postTypeControl = <React.Fragment></React.Fragment>,
			    termsControl    = <React.Fragment></React.Fragment>;

			if ( 'posts' === props.attributes.display ) {
				postTypeControl = <SelectControl
					label={ __( 'Post Type' ) }
					value={ props.attributes['post-type'] }
					options={ map( keys( props.postTypes.data ), ( type, idx ) => ( {
						value: type,
						label: props.postTypes.data[type].name,
					} ) ) }
					onChange={ onChange( 'post-type' ) }
				/>

				if ( !! props.attributes.taxonomy ) {
					termsControl = <TextControl
						label={ __( 'Taxonomy terms' ) }
						value={ props.attributes.terms }
						onChange={ onChange( 'terms' ) }
					/>
				}
			}

			var preview =
				<React.Fragment>
					<p>{ __( 'The A-Z Listing will appear here when viewed on your website' ) }</p>
				</React.Fragment>

			return (
				<React.Fragment>
					<InspectorControls>
						<SelectControl
							label={ __( 'Display mode' ) }
							value={ props.attributes.display }
							options={ [
								{ value: 'posts', label: __( 'Posts' ) },
								{ value: 'terms', label: __( 'Taxonomy terms' ) }
							] }
							onChange={ onChange( 'display' ) }
						/>

						{ postTypeControl }

						<SelectControl
							label={ __( 'Taxonomy' ) }
							value={ props.attributes.taxonomy }
							options={ getFilteredTaxonomies( props ) }
							onChange={ onChange( 'taxonomy' ) }
						/>

						{ termsControl }

						<SelectControl
							label={ __( 'Numbers' ) }
							value={ props.attributes.numbers }
							options={ [
								{ value: '',       label: __( 'Hide numbers' ) },
								{ value: 'before', label: __( 'Prepend before alphabet' ) },
								{ value: 'after',  label: __( 'Append after alphabet' ) },
							] }
							onChange={ onChange( 'numbers' ) }
						/>

						<RangeControl
							label={ __( 'Group letters' ) }
							help={ __( 'The number of letters to include in a single group' ) }
							value={ props.attributes.grouping || 1 }
							min={ 1 }
							max={ 10 }
							onChange={ onChange( 'grouping' ) }
						/>

						<ToggleControl
							label={ __( 'Group numbers' ) }
							help={ __( 'Group 0-9 as a single letter' ) }
							checked={ !! props.attributes['group-numbers'] }
							onChange={ onChange( 'group-numbers' ) }
						/>
					</InspectorControls>

					{ preview }

				</React.Fragment>
			)
		} ),

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
		save: function() {
			return (
				<div>{ __( 'The A-Z Listing plugin is not currently enabled' ) }</div>
			);
		}
	} );
} )(
	window.wp
);
