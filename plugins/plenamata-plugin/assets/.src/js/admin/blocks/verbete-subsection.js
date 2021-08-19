import { PlainText } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

registerBlockType('plenamata/verbete-subsection', {
    apiVersion: 2,
    title: __('Subseção do verbete', 'plenamata'),
    icon: 'heading',
    category: 'text',
    attributes: {
		content: {
			type: 'string',
		},
	},
    edit({ attributes, setAttributes }) {
        return (
            <div>
                { 'Hello World (from the editor).' }
            </div>
        );
    },
    save({ attributes }) {
        return (
            <div>
                { 'Hello World (from the frontend)' }
            </div>
        );
    },
});
