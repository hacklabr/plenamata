import { MediaUpload, MediaUploadCheck, RichText, useBlockProps } from '@wordpress/block-editor'
import { registerBlockType } from '@wordpress/blocks'
import { Button } from '@wordpress/components'
import { box } from '@wordpress/icons'

import { __ } from './utils/i18n'

const ALLOW_IMAGES = ['image']

registerBlockType('plenamata/initiative', {
    apiVersion: 2,
    title: __('The Initiative', 'plenamata'),
    icon: box,
    category: 'text',
    attributes: {
        name: {
            type: 'string',
        },
        what: {
            type: 'string',
        },
        where: {
            type: 'string',
        },
        whereImage: {
            type: 'string',
        },
        who: {
            type: 'string',
        },
    },
    edit({ attributes, setAttributes }) {
        const { name, what, where, whereImage, who } = attributes
        const blockProps = useBlockProps({ className: 'plenamata-block-box plenamata-initiative-block' })

        return (
            <section { ...blockProps }>
                <header className="plenamata-block-box__header plenamata-initiative-block__header">
                    <h4>{ __('The Initiative', 'plenamata') }</h4>
                </header>
                <main className="plenamata-block-box__main plenamata-initiative-block__main">
                    <dl>
                        <dt>{ __('Name', 'plenamata') }</dt>
                        <dd>
                            <RichText
                                value={ name }
                                onChange={ (name) => setAttributes({ name }) }
                            />
                        </dd>
                    </dl>
                    <dl>
                        <dt>{ __("Who's involved", 'plenamata') }</dt>
                        <dd>
                            <RichText
                                value={ who }
                                onChange={ (who) => setAttributes({ who }) }
                            />
                        </dd>
                    </dl>
                    <dl>
                        <dt>{ __('What is it', 'plenamata') }</dt>
                        <dd>
                            <RichText
                                value={ what }
                                onChange={ (what) => setAttributes({ what }) }
                            />
                        </dd>
                    </dl>
                    <dl className="plenamata-initiative-block__map">
                        <dt>{ __('Where is it', 'plenamata') }</dt>
                        <dd>
                            { (whereImage) ? (
                                <img src={ whereImage } alt=""/>
                            ) : null }
                            <RichText
                                value={ where }
                                onChange={ (where) => setAttributes({ where }) }
                            />
                        </dd>
                        <MediaUploadCheck>
                            <MediaUpload
                                allowedTypes={ ALLOW_IMAGES }
                                onSelect={ (media) => setAttributes({ whereImage: media.sizes.medium.url }) }
                                render={ ({ open }) => (
                                    <Button className="is-primary" onClick={ open }>
                                        { __('Upload image', 'plenamata') }
                                    </Button>
                                ) }
                            />
                        </MediaUploadCheck>
                    </dl>
                </main>
            </section>
        )
    },
    save({ attributes }) {
        const { name, what, where, whereImage, who } = attributes
        const blockProps = useBlockProps.save({ className: 'plenamata-block-box plenamata-initiative-block' })

        return (
            <section { ...blockProps }>
                <header className="plenamata-block-box__header plenamata-initiative-block__header">
                    <h4>{ __('The Initiative', 'plenamata') }</h4>
                </header>
                <main className="plenamata-block-box__main plenamata-initiative-block__main">
                    { (name) ? (
                        <dl>
                            <dt>{ __('Name', 'plenamata') }</dt>
                            <dd>
                                <RichText.Content value={ name }/>
                            </dd>
                        </dl>
                    ) : null }
                    { (who) ? (
                        <dl>
                            <dt>{ __("Who's involved", 'plenamata') }</dt>
                            <dd>
                                <RichText.Content value={ who }/>
                            </dd>
                        </dl>
                    ) : null }
                    { (what) ? (
                        <dl>
                            <dt>{ __('What is it', 'plenamata') }</dt>
                            <dd>
                                <RichText.Content value={ what }/>
                            </dd>
                        </dl>
                    ) : null }
                    { (where || whereImage) ? (
                        <dl>
                            <dt>{ __('Where is it', 'plenamata') }</dt>
                            <dd className="plenamata-initiative-block__map">
                                { (whereImage) ? (
                                    <img src={ whereImage } alt=""/>
                                ) : null }
                                { (where) ? (
                                    <RichText.Content value={ where }/>
                                ) : null }
                            </dd>
                        </dl>
                    ) : null }
                </main>
            </section>
        )
    },
})
