import { DateTimePicker, TextControl, __experimentalNumberControl as NumberControl, ServerSideRender } from '@wordpress/components';
import { RichText } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { registerBlockType } from '@wordpress/blocks';

registerBlockType('plenamata/estimatives-area', {
    title: __('Estimatives Area', 'jeo'),
    icon: 'visibility',
    category: 'common',
    attributes: {
        boxTitle: {
            type: 'string',
            default:  __("Desmatamento agora 2", "jeo")
        },
        headingTitle: {
            type: 'string',
            default: __("Na Amazônia", "jeo"),
        },
        preNumberTitle: {
            type: 'string',
            default: __("Árvores derrubadas em 2021", "jeo"),
        },
        averageTitle: {
            type: 'string',
            default: __("Média de desmatamento em 2021", "jeo"),
        },
        deforestedTitle: {
            type: 'string',
            default: __("Desmatados 2021", "jeo"),
        },
        finalInformation: {
            type: 'string',
            default: __("Estimativas detectadas e reportadas. Velocidades médias. | Fonte: MapBiomas. | Última atualização: 08.06.2021", "jeo"),
        },
        baseTrees: {
            type: 'string',
        },
        tressPerDay: {
            type: 'string',
        },
        hecPerDay: {
            type: 'string',
        },
        warnings: {
            type: 'string',
        },
        hectares: {
            type: 'string',
        },
        baseDate: {
            type: 'string',
        }
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

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calculateTreeEstimative(baseTrees, tressPerDay, baseDate ) {
            const startDate = new Date(baseDate);
            const currentDate = new Date(Date.now());
            const secondsBetween = Math.abs((startDate.getTime() - currentDate.getTime()) / 1000);;
            const treesDestroiedInAsec = parseInt(tressPerDay) / 86400;

            return Math.floor(parseInt(baseTrees) + treesDestroiedInAsec * secondsBetween)
        }

        return (
            <div className="estimatives-area">
                <div className="heading">
                    <RichText
                        tagName="span"
                        className="box-title"
                        value={attributes.boxTitle}
                        onChange={updateAttribute('boxTitle')}
                        placeholder={__('Type the box title', 'jaci')}
                    />

                    <RichText
                        tagName="h3"
                        className="heading-title"
                        value={attributes.headingTitle}
                        onChange={updateAttribute('headingTitle')}
                        placeholder={__('Type the heading text', 'jaci')}
                    />
                </div>

                <div className="main-data">
                    <RichText
                        tagName="span"
                        className="pre-number-title"
                        value={attributes.preNumberTitle}
                        onChange={updateAttribute('preNumberTitle')}
                        placeholder={__('Type the before number title', 'jaci')}
                    />

                    <div className="number">
                        <span>
                            {/* { numberWithCommas(baseTrees? baseTrees : 0) } */}
                            { numberWithCommas(calculateTreeEstimative(attributes.baseTrees? attributes.baseTrees : 0, attributes.tressPerDay, attributes.baseDate)) }
                        </span>
                    </div>

                    <NumberControl
                        className="base-trees"
                        label={__("Base trees", "jaci")}
                        value={ attributes.baseTrees }
                        isShiftStepEnabled={ true }
                        shiftStep={ 1 }
                        onChange={ updateAttribute('baseTrees') }
                    />

                    { __("Base date", "jaci") }

                    <DateTimePicker
                        className="base-date"
                        currentDate={ attributes.baseDate }
                        onChange={ updateAttribute('baseDate')  }
                        is12Hour={ true }
                    />
                </div>

                <div className="base-data">
                    <div>
                        <RichText
                            tagName="span"
                            className="average-title"
                            value={attributes.averageTitle}
                            onChange={updateAttribute('averageTitle')}
                            placeholder={__('Type the average title', 'jaci')}
                        />

                        <div className="data">
                            <div className="area">
                                <span>
                                    <NumberControl
                                        label={__("Trees per day", "jaci")}
                                        value={ attributes.tressPerDay }
                                        onChange={ updateAttribute('tressPerDay') }
                                    />
                                </span>
                            </div>

                            <div className="area">
                                <span>
                                    <NumberControl
                                        label={__("Hectares per day", "jaci")}
                                        value={ attributes.hecPerDay }
                                        onChange={ updateAttribute('hecPerDay') }
                                    />
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <RichText
                            tagName="span"
                            className="deforested-title"
                            value={attributes.deforestedTitle}
                            onChange={updateAttribute('deforestedTitle')}
                            placeholder={__('Type the desforested title', 'jaci')}
                        />

                        <div className="data">
                            <div className="area">
                                <span>
                                    <NumberControl
                                        label={__("Warnings", "jaci")}
                                        value={ attributes.warnings }
                                        onChange={ updateAttribute('warnings') }
                                    />
                                </span>

                                <span>
                                    {/* { __("Alertas", "jaci") } */}
                                </span>
                            
                            </div>

                            <div className="area">
                                <span>
                                    <NumberControl
                                        label={__("Hectares", "jaci")}
                                        value={ attributes.hectares }
                                        onChange={ updateAttribute('hectares') }
                                    />
                                </span>

                                <span>
                                    {/* { __("hectares", "jaci") } */}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <div className="final-info">
                    <RichText
                        tagName="span"
                        className="deforested-title"
                        value={attributes.finalInformation}
                        onChange={updateAttribute('deforestedTitle')}
                        placeholder={__('Type the final information', 'jaci')}
                    />
                </div>
            </div>
        );
    },
    save({attributes}) {
       return null;
    },
});