import { RichTextToolbarButton } from '@wordpress/block-editor'
import { Modal, SelectControl } from '@wordpress/components'
import { useSelect } from '@wordpress/data'
import { useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getActiveFormat, registerFormatType, removeFormat, toggleFormat } from '@wordpress/rich-text'

const FORMAT_NAME = 'plenamata/glossary-tooltip'

function fetchVerbetes (select) {
    return select('core').getEntityRecords('postType', 'verbete', {
        order: 'asc',
        orderby: 'title',
        per_page: -1,
    })
}

function findVerbete (value, glossary) {
    if (!value || !glossary) {
        return null
    }
    const selection = value.text.slice(value.start, value.end).toLowerCase()
    for (const verbete of glossary) {
        if (verbete.title.raw.toLowerCase() === selection) {
            return String(verbete.id)
        }
    }
    return null
}

function verbeteOptions (glossary) {
    if (!glossary) {
        return []
    }
    const options = glossary.map((verbete) => {
        return { label: verbete.title.raw, value: verbete.id }
    })
    return [
        { label: __('Select a verbete', 'plenamata'), value: null },
        ...options,
    ]
}

registerFormatType(FORMAT_NAME, {
    title: __('Glossary tooltip', 'plenamata'),
    tagName: 'span',
    className: 'glossary-tooltip',
    attributes: {
        verbeteId: 'data-verbete-id',
    },

    edit ({ isActive, onChange, value }) {
        const [addingTooltip, setAddingTooltip] = useState(false)
        const [currentValue, setValue] = useState(value)

        const glossary = useSelect(fetchVerbetes, [])

        return <>
            <RichTextToolbarButton
                icon="testimonial"
                isActive={ isActive }
                title={ __('Glossary tooltip', 'plenamata') }
                onClick={ () => {
                    const verbeteId = findVerbete(value, glossary)
                    if (verbeteId) {
                        onChange(toggleFormat(value, { type: FORMAT_NAME, attributes: { verbeteId } }))
                    } else if (getActiveFormat(value, FORMAT_NAME)) {
                        onChange(removeFormat(value, FORMAT_NAME))
                    } else {
                        setAddingTooltip(true)
                        setValue(value)
                    }
                } }
            />
            { (addingTooltip) ? (
                <Modal
                    title={ __('Glossary tooltip', 'plenamata') }
                    onRequestClose={ () => {
                        setAddingTooltip(false)
                    } }
                >
                    <div className="select-glossary-tooltip">
                        <SelectControl
                            label={ __('Select verbete:', 'plenamata') }
                            options={ verbeteOptions(glossary) }
                            onChange={ (verbeteId) => {
                                setAddingTooltip(false)
                                if (verbeteId) {
                                    onChange(toggleFormat(currentValue, { type: FORMAT_NAME, attributes: { verbeteId } }))
                                }
                            } }
                        />
                    </div>
                </Modal>
            ) : null }
        </>;
    },
})
