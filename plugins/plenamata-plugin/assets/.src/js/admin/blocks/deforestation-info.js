import { registerBlockType } from "@wordpress/blocks";
import { TextControl } from '@wordpress/components';
import { __ } from "@wordpress/i18n";
//import './dashboard.scss';

registerBlockType('plenamata/deforestation-info', {
    title: __('Deforestation Info', 'plenamata'),
    icon: 'info-outline',
    category: 'common',

    edit({ attributes, setAttributes }) {
        const {
            boxTitle = __('Area of deforestation alerts detected last week', 'plenamata'),
        } = attributes;

        return (
            <div className="plenamata-block deforestation-info">
                <div>
                    <TextControl
                        label={__('Title', 'plenamata')}
                        value={ boxTitle }
                        onChange={ ( boxTitle ) => setAttributes( { boxTitle } ) }
                        placeholder={__('Type the box title', 'plenamata')}
                    />
                </div>
            </div>
        );
    },

    save: ({ attributes }) => {
        return (
            <div className="deforestation-info">
                <div className="box-content">
                    <span className="box-title">{attributes.boxTitle}</span>
                    <span className="icon"></span>
                    <div className="wrap">
                        <span className="count" data-deter="hectaresLastWeek"/>
                        <span className="legend">{__('hectares', 'plenamata')}</span>
                    </div>
                </div>
                <span className="data-source" data-deter="sourcesLastWeek"/>
            </div>
        );
    },
});