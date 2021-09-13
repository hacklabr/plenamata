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
            boxTitle = __('Evolution per period (deforested square kilometers)')
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
            </div>
        )
    },

    save ({ attributes }) {
        const blockProps = useBlockProps.save({ className: 'deforestation-charts-block' })

        return (
            <div { ...blockProps }>
                <h3>{ attributes.boxTitle }</h3>
                <div className="vue-deforestation-charts"/>
            </div>
        )
    }
})
