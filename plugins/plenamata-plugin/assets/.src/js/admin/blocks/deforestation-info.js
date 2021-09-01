import { registerBlockType } from "@wordpress/blocks";
import { __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { TextControl } from '@wordpress/components';
import { __ } from "@wordpress/i18n";
//import './dashboard.scss';

registerBlockType('plenamata/deforestation-info', {
    title: __('Deforestation Info', 'plenamata'),
    icon: 'info-outline',
    category: 'common',

    edit({ attributes, setAttributes }) {
        const {
            boxTitle = __('Desmatados na Amazônia nos últimos 07 dias.', 'plenamata'),
            count = 0,
            dataSource = ''
        } = attributes;

        return (
            <>
                <div className="deforestation-info">
                    <TextControl
                        label={__('Title', 'plenamata')}
                        value={ boxTitle }
                        onChange={ ( boxTitle ) => setAttributes( { boxTitle } ) }
                        placeholder={__('Type the box title', 'plenamata')}
                    />
                    <NumberControl
                        label={__('Count', 'plenamata')}
                        isShiftStepEnabled={ true }
                        onChange={ ( count ) => setAttributes( { count } ) }
                        shiftStep={ 1 }
                        value={ count }
                        placeholder={__('Type the count', 'plenamata')}
                    />
                    <TextControl
                        label={__('Source', 'plenamata')}
                        value={ dataSource }
                        onChange={ ( dataSource ) => setAttributes( { dataSource } ) }
                        placeholder={__('Type the source of data', 'plenamata')}
                    />
                </div>
            </>
        );
    },

    save: ({ attributes }) => {
        return (
            <div className="deforestation-info">
                <div className="box-content">
                    <span className="box-title">{attributes.boxTitle}</span>
                    <span className="icon"></span>
                    <div className="wrap">
                        <span className="count" data-mask="true">{attributes.count}</span>
                        <span className="legend">{__('hectares', 'plenamata')}</span>
                    </div>
                </div>
                <span className="data-source">{attributes.dataSource}</span>
            </div>
        );
    },
});