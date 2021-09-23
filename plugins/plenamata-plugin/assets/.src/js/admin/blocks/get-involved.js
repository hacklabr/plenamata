import { RichText, useBlockProps } from '@wordpress/block-editor'
import { registerBlockType } from '@wordpress/blocks'
import { __ } from '@wordpress/i18n'
import { box } from '@wordpress/icons'

registerBlockType('plenamata/get-involved', {
    apiVersion: 2,
    title: __('How to get involved', 'plenamata'),
    icon: box,
    category: 'text',
    attributes: {
        content: {
            type: 'string',
        },
    },
    edit({ attributes, setAttributes }) {
        const { content } = attributes
        const blockProps = useBlockProps({ className: 'plenamata-block-box plenamata-get-involved-block' })

        return (
            <section { ...blockProps }>
                <header className="plenamata-block-box__header plenamata-get-involved-block__header">
                    <h4>{ __('How to get involved', 'plenamata') }</h4>
                </header>
                <main className="plenamata-block-box__main plenamata-get-involved-block__main">
                    <RichText
                        multiline={ true }
                        onChange={ (content) => setAttributes({ content }) }
                        value={ content }
                    />
                </main>
            </section>
        )
    },
    save({ attributes }) {
        const { content } = attributes
        const blockProps = useBlockProps.save({ className: 'plenamata-block-box plenamata-get-involved-block' })

        return (
            <section { ...blockProps }>
                <header className="plenamata-block-box__header plenamata-get-involved-block__header">
                    <h4>{ __('How to get involved', 'plenamata') }</h4>
                </header>
                <main className="plenamata-block-box__main plenamata-get-involved-block__main">
                    <RichText.Content value={ content }/>
                </main>
            </section>
        )
    },
})
