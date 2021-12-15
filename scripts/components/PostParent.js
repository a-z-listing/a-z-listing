/**
 * External dependencies
 */
import {
	get,
	unescape as unescapeString,
	debounce,
	flatMap,
	repeat,
	find,
} from 'lodash';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ComboboxControl } from '@wordpress/components';
import { useState, useMemo } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { buildTermsTree } from './utils/terms';

function getTitle( post ) {
	return post?.title?.rendered
		? post.title.rendered
		: `#${ post.id } (${ __( 'no title' ) })`;
}

export function PostParent( { pageId, postTypeSlug, onChange } ) {
	const [ fieldValue, setFieldValue ] = useState( '' );
	const isSearching = fieldValue;
	const { parentPost, parentPostId, items, postType } = useSelect(
		( select ) => {
			const { getPostType, getEntityRecords, getEntityRecord } = select(
				'core'
			);
			const pType = getPostType( postTypeSlug );
			const isHierarchical = get( pType, [ 'hierarchical' ], false );
			const query = {
				per_page: 100,
				orderby: 'menu_order',
				order: 'asc',
				_fields: 'id,title,parent',
			};

			// Perform a search when the field is changed.
			if ( isSearching ) {
				query.search = fieldValue;
			}

			return {
				parentPostId: pageId,
				parentPost: pageId
					? getEntityRecord( 'postType', postTypeSlug, pageId )
					: null,
				items: isHierarchical
					? getEntityRecords( 'postType', postTypeSlug, query )
					: [],
				postType: pType,
			};
		},
		[ fieldValue ]
	);

	const isHierarchical = get( postType, [ 'hierarchical' ], false );
	const pageItems = items || [];
	const getOptionsFromTree = ( tree, level = 0 ) => {
		return flatMap( tree, ( treeNode ) => [
			{
				value: treeNode.id,
				label: repeat( '— ', level ) + unescapeString( treeNode.name ),
			},
			...getOptionsFromTree( treeNode.children || [], level + 1 ),
		] );
	};

	const parentOptions = useMemo( () => {
		let tree = pageItems.map( ( item ) => ( {
			id: item.id,
			parent: item.parent,
			name: getTitle( item ),
		} ) );

		// Only build a hierarchical tree when not searching.
		if ( ! isSearching ) {
			tree = buildTermsTree( tree );
		}

		const opts = getOptionsFromTree( tree );

		// Ensure the current parent is in the options list.
		const optsHasParent = find(
			opts,
			( item ) => item.value === parentPostId
		);
		if ( parentPost && ! optsHasParent ) {
			opts.unshift( {
				value: parentPostId,
				label: getTitle( parentPost ),
			} );
		}
		return opts;
	}, [ pageItems ] );

	if ( ! isHierarchical ) {
		return null;
	}
	/**
	 * Handle user input.
	 *
	 * @param {string} inputValue The current value of the input field.
	 */
	const handleKeydown = ( inputValue ) => {
		setFieldValue( inputValue );
	};

	return (
		<ComboboxControl
			label={ __( 'Parent post', 'a-z-listing' ) }
			value={ parentPostId }
			options={ parentOptions }
			onFilterValueChange={ debounce( handleKeydown, 300 ) }
			onChange={ onChange }
		/>
	);
}

export default PostParent;
