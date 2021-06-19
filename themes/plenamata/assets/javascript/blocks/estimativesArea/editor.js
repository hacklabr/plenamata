import { DateTimePicker, TextControl, __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { RichText } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import './dashboard.scss'

export default ({ attributes, setAttributes }) => {
    const {
        boxTitle = __("Desmatamento agora", "jaci"),
        headingTitle = __("Na Amazônia", "jaci"),
        preNumberTitle = __("Árvores derrubadas em 2021", "jaci"),
        averageTitle = __("Média de desmatamento em 2021", "jaci"),
        deforestedTitle = __("Desmatados 2021", "jaci"),
        finalInformation = __("Estimativas detectadas e reportadas. Velocidades médias. | Fonte: MapBiomas. | Última atualização: 08.06.2021", "jaci"),
        baseTrees,
        tressPerDay,
        hecPerDay,
        warnings,
        hectares,
        baseDate } = attributes;

    console.log(attributes);

    const updateAttribute = (attribute) => {
        
        return (attributeValue) => {
            console.log(
                {
                    ...attributes,
                    [attribute]: attributeValue,
                }
            )
            setAttributes({
                ...attributes,
                [attribute]: attributeValue,
            })
        }
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    return (
        <div className="estimatives-area">
            <div className="heading">
                <RichText
                    tagName="span"
                    className="box-title"
                    value={boxTitle}
                    onChange={updateAttribute('boxTitle')}
                    placeholder={__('Type the box title', 'jaci')}
                />

                <RichText
                    tagName="h3"
                    className="heading-title"
                    value={headingTitle}
                    onChange={updateAttribute('headingTitle')}
                    placeholder={__('Type the heading text', 'jaci')}
                />
            </div>

            <div className="main-data">
                <RichText
                    tagName="span"
                    className="pre-number-title"
                    value={preNumberTitle}
                    onChange={updateAttribute('preNumberTitle')}
                    placeholder={__('Type the before number title', 'jaci')}
                />

                <div className="number">
                    <span>
                        { numberWithCommas(baseTrees? baseTrees : 0) }
                    </span>
                </div>

                <NumberControl
                    className="base-trees"
                    label={__("Base trees", "jaci")}
                    value={ baseTrees }
                    isShiftStepEnabled={ true }
                    shiftStep={ 1 }
                    onChange={ updateAttribute('baseTrees') }
                />

                { __("Base date", "jaci") }

                <DateTimePicker
                    className="base-date"
                    currentDate={ baseDate }
                    onChange={ updateAttribute('baseDate')  }
                    is12Hour={ true }
                />


            </div>


            <div className="base-data">
                <div>
                    <RichText
                        tagName="span"
                        className="average-title"
                        value={averageTitle}
                        onChange={updateAttribute('averageTitle')}
                        placeholder={__('Type the average title', 'jaci')}
                    />

                    <div className="data">
                        <div className="area">
                            <span>
                                <TextControl
                                    label={__("Trees per day", "jaci")}
                                    value={ tressPerDay }
                                    onChange={ updateAttribute('tressPerDay') }
                                />
                            </span>
                        </div>

                        <div className="area">
                            <span>
                                <TextControl
                                    label={__("Hectares per day", "jaci")}
                                    value={ hecPerDay }
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
                        value={deforestedTitle}
                        onChange={updateAttribute('deforestedTitle')}
                        placeholder={__('Type the desforested title', 'jaci')}
                    />

                    <div className="data">
                        <div className="area">
                            <span>
                                <TextControl
                                    label={__("Warnings", "jaci")}
                                    value={ warnings }
                                    onChange={ updateAttribute('warnings') }
                                />
                            </span>

                            <span>
                                {/* { __("Alertas", "jaci") } */}
                            </span>
                        
                        </div>

                        <div className="area">
                            <span>
                                <TextControl
                                    label={__("Hectares", "jaci")}
                                    value={ hectares }
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
                    value={finalInformation}
                    onChange={updateAttribute('deforestedTitle')}
                    placeholder={__('Type the final information', 'jaci')}
                />
            </div>
        </div>
    );
}