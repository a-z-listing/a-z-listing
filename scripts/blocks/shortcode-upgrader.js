import { createBlock } from '@wordpress/blocks';
import { useSelect, useDispatch } from '@wordpress/data';
import { attrs } from '@wordpress/shortcode';

const validBlocks = ( blocks ) => blocks && blocks.length > 0;

const blockHandler = ( block ) => {
	const atts = attrs( block.attributes.text )
	return createBlock( 'a-z-listing/wp-a-z-listing-block', atts );
}

const transform = ( block ) => {
	if ( block.name === 'core/shortcode' ) {
		const dispatch = useDispatch( 'core/block-editor' );
		dispatch.replaceBlock( block.clientId, blockHandler( block ) );
	} else if ( validBlocks( block.innerBlocks ) ) {
		convertBlocks( block.innerBlocks );
	}
}

const convertBlocks = ( blocks, depth = 1, maxDepth = 3 ) => {
	for ( const block in blocks) {
		const innerBlocks = { block };
		transform( block );
		if (depth <= maxDepth && validBlocks( innerBlocks ) ) {
			convertBlocks( innerBlocks, depth + 1, maxDepth );
		}
	}
}

export default () => {
	const coreEditor = useSelect( 'core/block-editor' );
	const blocks = coreEditor.getBlocks();

	if ( validBlocks( blocks ) ) {
		convertBlocks( blocks, 1, 3 );
	}
}
