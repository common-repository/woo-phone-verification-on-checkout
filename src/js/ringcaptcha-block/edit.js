import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
// import './style.scss';
import Widget from './Widget';

console.log('RingCaptcha Block: edit.js loaded');

export const Edit = ({ attributes, setAttributes }) => {
    const { phoneVerification } = attributes;
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Phone Verification Settings', 'woo-phone-verification-on-checkout')}>
                    <TextControl
                        label={__('Verification Code', 'woo-phone-verification-on-checkout')}
                        value={phoneVerification}
                        onChange={(value) => setAttributes({ phoneVerification: value })}
                    />
                </PanelBody>
            </InspectorControls>
            <Widget attributes={attributes} setAttributes={setAttributes} />
        </div>
    );
};

export const Save = ({ attributes }) => {
    const { phoneVerification } = attributes;
    return (
        <div {...useBlockProps.save()}>
            <p>{phoneVerification}</p>
        </div>
    );
};