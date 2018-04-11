'use strict';

(function (wp) {
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

	var withState = wp.components.withState,
	    withAPIData = wp.components.withAPIData,
	    SandBox = wp.components.SandBox;

	var InspectorControls = wp.blocks.InspectorControls,
	    RangeControl = wp.components.RangeControl,
	    SelectControl = wp.components.SelectControl,
	    TextControl = wp.components.TextControl,
	    ToggleControl = wp.components.ToggleControl;

	function getFilteredTaxonomies(props) {
		if ('posts' === props.attributes.display) {
			var _r = map(props.postTypes.data[props.attributes['post-type']].taxonomies, function (tax, idx) {
				return {
					value: tax,
					label: props.taxonomies.data[tax].name || taxonomy
				};
			});
			_r.unshift({ value: '', label: '' });
			return _r;
		}

		var r = map(keys(props.taxonomies.data), function (tax, idx) {
			return {
				value: tax,
				label: props.taxonomies.data[tax].name
			};
		});
		r.unshift({ value: '', label: '' });
		return r;
	}

	function getAppendQueryFactory() {
		var query = '';

		return function (param) {
			if (!param) {
				return query;
			}

			if ('' !== query) {
				query = query + '&' + param;
			} else {
				query = param;
			}

			return query;
		};
	}

	function getPreview(props) {
		if (!props.preview.data) {
			if ('terms' === props.attributes.display && !props.attributes.taxonomy) {
				return wp.element.createElement(
					'p',
					null,
					__('Your settings are incomplete. Try selecting a taxonomy...')
				);
			}
			return wp.element.createElement(
				'p',
				null,
				__('Loading...')
			);
		}
		if (!props.preview.data.rendered) {
			return wp.element.createElement(
				'p',
				null,
				__('There was an error generating the preview. Check your settings.')
			);
		}

		return wp.element.createElement(SandBox, { html: props.preview.data.rendered });
	}

	/**
  * Every block starts by registering a new block type definition.
  * @see https://wordpress.org/gutenberg/handbook/block-api/
  */
	registerBlockType('a-z-listing/a-z-listing', {
		title: __('A-Z Listing'),
		icon: 'translation',

		/**
   * Blocks are grouped into categories to help users browse and discover them.
   * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
   */
		category: 'widgets',
		supports: {
			// Removes support for an HTML mode.
			html: false
		},

		attributes: {
			display: { type: 'string', default: 'posts' },
			'post-type': { type: 'string', default: 'page' },
			terms: { type: 'string', default: '' },
			taxonomy: { type: 'string', default: '' },
			numbers: { type: 'string', default: '' },
			grouping: { type: 'number', default: 1 },
			'group-numbers': { type: 'boolean', default: false }
		},

		/**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
   *
   * @param {Object} [props] Properties passed from the editor.
   * @return {Element}       Element to render.
   */
		edit: withState(function () {
			return {
				display: 'posts',
				postType: 'page',
				taxonomy: 'category',
				terms: '',
				grouping: 1,
				groupNumbers: false
			};
		})(withAPIData(function (props) {
			var display = 'posts';
			if ('posts' === props.attributes.display || 'terms' === props.attributes.display) {
				display = props.attributes.display || 'posts';
			}

			var postType = void 0;
			if ('terms' === display) {
				postType = props.attributes.taxonomy || 'category';
			} else {
				postType = props.attributes.postType || 'page';
			}

			var getAppendQuery = getAppendQueryFactory();

			if (!!props.attributes.alphabet) {
				getAppendQuery('alphabet=' + props.attributes.alphabet);
			}

			if (!!props.attributes.grouping) {
				getAppendQuery('grouping=' + props.attributes.grouping);
			}

			if (!!props.attributes.groupNumbers) {
				getAppendQuery('group-numbers=' + props.attributes.groupNumbers);
			}

			if (!!props.attributes.numbers) {
				getAppendQuery('numbers=' + props.attributes.numbers);
			}

			if (!!props.attributes.taxonomy) {
				getAppendQuery('taxonomy=' + props.attributes.taxonomy);
			}

			if ('posts' === display && !!props.attributes.terms) {
				getAppendQuery('terms=' + props.attributes.terms);
			}

			var query = getAppendQuery('include-styles=true');

			return {
				postTypes: '/wp/v2/types',
				taxonomies: '/wp/v2/taxonomies',
				preview: '/a-z-listing/v1/' + display + '/' + postType + '?' + query
			};
		})(function (props) {
			if (!props.postTypes.data || !props.taxonomies.data) {
				return __("Loading...");
			}

			function onChange(prop) {
				return function (val) {
					var propObj = {};
					propObj[prop] = val;
					props.setAttributes(propObj);
				};
			}

			var preview = getPreview(props);

			return wp.element.createElement(
				React.Fragment,
				null,
				wp.element.createElement(
					InspectorControls,
					null,
					wp.element.createElement(SelectControl, {
						label: __('Display mode'),
						value: props.attributes.display,
						options: [{ value: 'posts', label: __('Posts') }, { value: 'terms', label: __('Taxonomy terms') }],
						onChange: onChange('display')
					}),
					'posts' === props.attributes.display ? wp.element.createElement(SelectControl, {
						label: __('Post Type'),
						value: props.attributes['post-type'],
						options: map(keys(props.postTypes.data), function (type, idx) {
							return {
								value: type,
								label: props.postTypes.data[type].name
							};
						}),
						onChange: onChange('post-type')
					}) : null,
					wp.element.createElement(SelectControl, {
						label: __('Taxonomy'),
						value: props.attributes.taxonomy,
						options: getFilteredTaxonomies(props),
						onChange: onChange('taxonomy')
					}),
					'posts' === props.attributes.display && !!props.attributes.taxonomy ? wp.element.createElement(TextControl, {
						label: __('Taxonomy terms'),
						value: props.attributes.terms,
						onChange: onChange('terms')

					}) : null,
					wp.element.createElement(SelectControl, {
						label: __('Numbers'),
						value: props.attributes.numbers,
						options: [{ value: 'hide', label: __('Hide numbers') }, { value: 'before', label: __('Prepend before alphabet') }, { value: 'after', label: __('Append after alphabet') }],
						onChange: onChange('numbers')
					}),
					wp.element.createElement(RangeControl, {
						label: __('Group letters'),
						help: __('The number of letters to include in a single group'),
						value: props.attributes.grouping || 1,
						min: 1,
						max: 10,
						onChange: onChange('grouping')
					}),
					wp.element.createElement(ToggleControl, {
						label: __('Group numbers'),
						help: __('Group 0-9 as a single letter'),
						checked: !!props.attributes['group-numbers'],
						onChange: onChange('group-numbers')
					})
				),
				preview
			);
		})),

		/**
   * The save function defines the way in which the different attributes should be combined
   * into the final markup, which is then serialized by Gutenberg into `post_content`.
   * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
   *
   * @return {Element}       Element to render.
   */
		save: function save() {
			return wp.element.createElement(
				'div',
				null,
				__('The A-Z Listing plugin is not currently enabled')
			);
		}
	});
})(window.wp);
//# sourceMappingURL=block.js.map
