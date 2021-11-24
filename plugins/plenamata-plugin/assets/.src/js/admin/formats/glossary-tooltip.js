import { RichTextToolbarButton } from '@wordpress/block-editor'
import { __ } from '@wordpress/i18n'
import { registerFormatType, toggleFormat } from '@wordpress/rich-text'

registerFormatType('plenamata/glossary-tooltip', {
    title: __('Glossary tooltip', 'plenamata'),
    tagName: 'span',
    className: 'glossary-tooltip',

    edit ({ isActive, onChange, value }) {
        return (
            <RichTextToolbarButton
                icon="testimonial"
                isActive={ isActive }
                onClick={ () => {
                    console.log('toggling...', value)
                    onChange(toggleFormat(value, { type: 'plenamata/glossary-tooltip' }))
                } }
                title={ __('Glossary tooltip', 'plenamata') }
            />
        );
    },
})
