import { RichText, useBlockProps } from '@wordpress/block-editor'
import { registerBlockType } from '@wordpress/blocks'
import { box } from '@wordpress/icons'

import { __ } from './utils/i18n'

registerBlockType('plenamata/get-involved', {
    apiVersion: 2,
    title: __('How to get involved', 'plenamata'),
    icon: box,
    category: 'text',
    attributes: {
        contactInfo: {
            type: 'string',
        },
        content: {
            type: 'string',
        },
        socialNetworks: {
            type: 'string',
        },
        website: {
            type: 'string',
        },
    },
    edit({ attributes, setAttributes }) {
        const { contactInfo, content, socialNetworks, website } = attributes
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
                    <dl>
                        <dt>{ __('Project website', 'plenamata') }</dt>
                        <dd>
                            <RichText
                                value={ website }
                                onChange={ (website) => setAttributes({ website }) }
                            />
                        </dd>
                    </dl>
                    <dl>
                        <dt>{ __('Contact information', 'plenamata') }</dt>
                        <dd>
                            <RichText
                                value={ contactInfo }
                                onChange={ (contactInfo) => setAttributes({ contactInfo }) }
                            />
                        </dd>
                    </dl>
                    <dl>
                        <dt>{ __('Social media', 'plenamata') }</dt>
                        <dd>
                            <RichText
                                value={ socialNetworks }
                                onChange={ (socialNetworks) => setAttributes({ socialNetworks }) }
                            />
                        </dd>
                    </dl>
                </main>
            </section>
        )
    },
    save({ attributes }) {
        const { contactInfo, content, socialNetworks, website } = attributes
        const blockProps = useBlockProps.save({ className: 'plenamata-block-box plenamata-get-involved-block' })

        return (
            <section { ...blockProps }>
                <header className="plenamata-block-box__header plenamata-get-involved-block__header">
                    <h4>{ __('How to get involved', 'plenamata') }</h4>
                </header>
                <main className="plenamata-block-box__main plenamata-get-involved-block__main">
                    <RichText.Content value={ content }/>
                    { (website) ? (
                        <dl>
                            <dt>{ __('Project website', 'plenamata') }</dt>
                            <dd>
                                <RichText.Content value={ website }/>
                            </dd>
                        </dl>
                    ) : null }
                    { (contactInfo) ? (
                        <dl>
                            <dt>{ __('Contact information', 'plenamata') }</dt>
                            <dd>
                                <RichText.Content value={ contactInfo }/>
                            </dd>
                        </dl>
                    ) : null }
                    { (socialNetworks) ? (
                        <dl>
                            <dt>{ __('Social media', 'plenamata') }</dt>
                            <dd>
                                <RichText.Content value={ socialNetworks } />
                            </dd>
                        </dl>
                    ) : null }
                </main>
            </section>
        )
    },
})
