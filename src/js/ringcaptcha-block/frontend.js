import { registerCheckoutBlock } from '@woocommerce/blocks-checkout';
import { Block } from './block';
import metadata from './block.json';

console.log('RingCaptcha Block: frontend.js loaded');

registerCheckoutBlock({
    metadata,
    component: Block,
});