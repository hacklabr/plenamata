import { useBlockProps } from '@wordpress/block-editor'
import { registerBlockType } from '@wordpress/blocks'
import { TextControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'

registerBlockType('plenamata/deforestation-charts', {
    title: __('Deforestation Charts', 'plenamata'),
    icon: 'chart-bar',
    category: 'common',

    edit ({ attributes, setAttributes }) {
        const {
            boxTitle = __('Evolution per period (deforested square kilometers)'),
            parenthical = '',
        } = attributes

        const blockProps = useBlockProps({ className: 'plenamata-block deforestation-charts' })

        return (
            <div { ...blockProps }>
                <div>
                    <TextControl
                        label={ __('Title', 'plenamata') }
                        value={ boxTitle }
                        onChange={ ( boxTitle ) => setAttributes( { boxTitle } ) }
                        placeholder={ __('Type the box title', 'plenamata') }
                    />
                </div>
                <div>
                    <TextControl
                        label={ __('Parenthical', 'plenamata') }
                        value={ parenthical }
                        onChange={ ( parenthical ) => setAttributes( { parenthical } ) }
                        placeholder={ __('Type the text inside parenthesis', 'plenamata') }
                    />
                </div>
            </div>
        )
    },

    save ({ attributes }) {
        const blockProps = useBlockProps.save({ className: 'deforestation-charts-block' })

        const { boxTitle, parenthical } = attributes

        return (
            <div { ...blockProps }>
                <h3>{ boxTitle } <small>({ parenthical })</small></h3>
                <div className="vue-deforestation-charts"/>
            </div>
        )
    }
})
