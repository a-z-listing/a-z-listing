import { createSlotFill } from '@wordpress/components';

export const { Fill, Slot } = createSlotFill( 'AZListingExtensions' );

const Extensions = ( { children } ) => <Fill>{ children }</Fill>;

Extensions.Slot = Slot;

export default Extensions;
