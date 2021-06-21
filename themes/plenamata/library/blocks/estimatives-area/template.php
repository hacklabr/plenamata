<?php 

$params = get_query_var('block_params', false);
extract($params['attributes']);

?>

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
                {/* { numberWithCommas(baseTrees? baseTrees : 0) } */}
                { numberWithCommas(calculateTreeEstimative(baseTrees? baseTrees : 0, tressPerDay, baseDate)) }
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
                        <NumberControl
                            label={__("Trees per day", "jaci")}
                            value={ tressPerDay }
                            onChange={ updateAttribute('tressPerDay') }
                        />
                    </span>
                </div>

                <div className="area">
                    <span>
                        <NumberControl
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
                        <NumberControl
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
                        <NumberControl
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