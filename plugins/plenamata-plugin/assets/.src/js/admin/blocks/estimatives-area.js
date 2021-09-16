import { TextControl } from '@wordpress/components';
import { __ } from "@wordpress/i18n";
import { registerBlockType } from '@wordpress/blocks';

registerBlockType('plenamata/estimatives-area', {
    title: __('Estimatives Area', 'plenamata'),
    icon: 'visibility',
    category: 'common',
    attributes: {
        headingTitle: {
            type: 'string',
            default: __("Desmatamento na Amazônia Brasileira", "plenamata"),
        },
        preNumberTitle: {
            type: 'string',
            default: __("Árvores derrubadas por desmatamento em 2021", "plenamata"),
        },
        averageTitle: {
            type: 'string',
            default: __("Média de desmatamento em 2021", "plenamata"),
        },
        deforestedTitle: {
            type: 'string',
            default: __("Desmatados 2021", "plenamata"),
        },
        finalInformation: {
            type: 'string',
            default: __("Estimativas detectadas e reportadas. Velocidades médias. | Fonte: MapBiomas. | Última atualização: 08.06.2021", "plenamata"),
        },
        warnings: {
            type: 'string',
        },
    },

    edit({ attributes, setAttributes }) {

        const updateAttribute = (attribute) => {
            return (attributeValue) => {
                setAttributes({
                    ...attributes,
                    [attribute]: attributeValue,
                })
            }
        }

        return (
            <div className="plenamata-block estimatives-area">
                <div className="heading">
                    <TextControl
                        label={__('Heading text', 'plenamata')}
                        value={attributes.headingTitle}
                        onChange={updateAttribute('headingTitle')}
                    />
                </div>

                <div className="main-data">
                    <TextControl
                        label={__('Text before number', 'plenamata')}
                        value={attributes.preNumberTitle}
                        onChange={updateAttribute('preNumberTitle')}
                    />
                </div>

                <div className="base-data">
                    <div>
                        <TextControl
                            label={__('Average text', 'plenamata')}
                            value={attributes.averageTitle}
                            onChange={updateAttribute('averageTitle')}
                        />
                    </div>

                    <div>
                        <TextControl
                            label={__('Deforested text', 'plenamata')}
                            value={attributes.deforestedTitle}
                            onChange={updateAttribute('deforestedTitle')}
                        />
                    </div>

                    <div className="area">
                        <TextControl
                            label={__('Warnings', 'plenamata')}
                            value={ attributes.warnings }
                            onChange={ updateAttribute('warnings') }
                        />
                    </div>
                </div>

                <div className="final-info">
                    <TextControl
                        label={__('End text', 'plenamata')}
                        value={attributes.finalInformation}
                        onChange={updateAttribute('finalInformation')}
                    />
                </div>
            </div>
        );
    },
    save() {
       return null;
    },
});