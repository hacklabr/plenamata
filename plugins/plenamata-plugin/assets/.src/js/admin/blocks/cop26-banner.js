import { useBlockProps } from '@wordpress/block-editor'
import { registerBlockType } from '@wordpress/blocks'
import { TextControl } from '@wordpress/components'

import { __ } from './utils/i18n'

registerBlockType('plenamata/cop26-banner', {
    title: __('COP26 Banner', 'plenamata'),
    icon: 'admin-site',
    category: 'common',

    edit ({ attributes, setAttributes }) {
        const { buttonLink = '', buttonText = '', title = '' } = attributes

        const blockProps = useBlockProps({ className: 'plenamata-block' })

        return (
            <div { ...blockProps }>
                <div>
                    <TextControl
                        label={ __('Title', 'plenamata') }
                        value={ title }
                        onChange={ (title) => setAttributes({ title }) }
                    />
                </div>
                <div>
                    <TextControl
                        label={ __('Button text', 'plenamata') }
                        value={ buttonText }
                        onChange={ (buttonText) => setAttributes({ buttonText }) }
                    />
                </div>
                <div>
                    <TextControl
                        label={ __('Button link', 'plenamata') }
                        type="url"
                        value={ buttonLink }
                        onChange={ (buttonLink) => setAttributes({ buttonLink }) }
                    />
                </div>
            </div>
        )
    },

    save ({ attributes }) {
        const { buttonLink, buttonText, title } = attributes

        const blockProps = useBlockProps.save({ className: 'plenamata-cop26-banner' })

        return (
            <section { ...blockProps }>
                <div>
                    <h3>{ title }</h3>
                    <div>{ __('from November 1st to 12th in Glasgow', 'plenamata') }</div>
                </div>
                <div>
                    <img src="/wp-content/plugins/plenamata-plugin/assets/build/img/cop26.png" alt="COP26"/>
                </div>
                <div>
                    <a className="cop26-banner__button" href={ buttonLink }>{ buttonText }</a>
                </div>
            </section>
        )
    }
})