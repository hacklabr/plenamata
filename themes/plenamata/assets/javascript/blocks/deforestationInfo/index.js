import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { RichText } from '@wordpress/block-editor';

import './dashboard.scss'

registerBlockType('jaci/deforestation-info', {
    title: __('Deforestation Information', 'jaci'),
    icon: 'visibility',
    category: 'common',
    edit: ({ attributes, setAttributes }) => {
        const {
            boxTitle = "Valor padrão do richtext"
        } = attributes;

        return (
            <>
                <RichText
                    tagName="span"
                    className="box-title"
                    value={ boxTitle }
                    onChange={ (boxTitle) => setAttributes({ boxTitle }) }
                    placeholder={__('Type the box title', 'jaci')}
                />

                <RichText
                    tagName="span"
                    className="box-title"
                    value={ boxTitle }
                    onChange={ (boxTitle) => setAttributes({ boxTitle }) }
                    placeholder={__('Type the box title', 'jaci')}
                />
            </>
        );
    },
    save: ({ attributes }) => {
        return (
            <RichText.Content tagName="span" value={ attributes.boxTitle } />
        );
    }
});