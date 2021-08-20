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
        const { content } = attributes;

        return (
            <h3 className="glossary-entry__subsection">
                <PlainText
                    value={ content }
                    onChange={ (content) => setAttributes({ content }) }
                />
            </h3>
        );
    },
    save({ attributes }) {
        const { content } = attributes;

        return (
            <h3 className="glossary-entry__subsection">
                { content }
            </h3>
        );
    },
});
