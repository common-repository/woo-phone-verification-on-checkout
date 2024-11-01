/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';

const render = () => {};

registerPlugin( 'woo-phone-verification-on-checkout', {
	render,
	scope: 'woocommerce-checkout',
} );
