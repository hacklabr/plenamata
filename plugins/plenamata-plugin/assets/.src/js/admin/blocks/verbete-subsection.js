import { PlainText, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { archiveTitle } from '@wordpress/icons';

registerBlockType('plenamata/verbete-subsection', {
    apiVersion: 2,
    title: __('Subseção do verbete', 'plenamata'),
    icon: archiveTitle,
    category: 'text',
    attributes: {
		content: {
			type: 'string',
		},
	},
    edit({ attributes, setAttributes }) {
        const { content } = attributes;
        const blockProps = useBlockProps({ className: 'glossary-entry__subsection' });

        return (
            <h3 { ...blockProps }>
                <span className="glossary-entry__subsection-hint">
                    { __('Nome da subseção:', 'plenamata') }
                </span>
                <PlainText
                    value={ content }
                    onChange={ (content) => setAttributes({ content }) }
                />
            </h3>
        );
    },
    save({ attributes }) {
        const { content } = attributes;
        const blockProps = useBlockProps.save({ className: 'glossary-entry__subsection' });

        return (
            <h3 { ...blockProps }>
                { content }
            </h3>
        );
    },
});
