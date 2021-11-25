import { createPopper } from '@popperjs/core'
import clickOutside from 'click-outside'

const i18n = window.PlenamataPlugin.i18n.__
const restUrl = window.PlenamataPlugin.restUrl

export class GlossaryTooltips {

    constructor () {
        this.init()
    }

    async init () {
        const body = document.body
        const tooltips = document.querySelectorAll('.glossary-tooltip[data-verbete-id]')
        const verbetesIds = new Set()

        for (const tooltip of tooltips) {
            verbetesIds.add(tooltip.dataset.verbeteId)
        }

        const res = await window.fetch(`${restUrl}wp/v2/verbete?include=${[...verbetesIds].join(',')}&fields=excerpt,id,link`)
        const verbetes = await res.json()

        for (const verbete of verbetes) {
            const template = document.createElement('div')
            template.id = `tooltip-template-${verbete.id}`
            template.classList.add('glossary-tooltip__tooltip')
            template.innerHTML = `
                <div class="glossary-tooltip__close">
                    <button type="button" aria-label="${i18n.close}"><i class="fas fa-times-circle"></i></button>
                </div>
                ${verbete.excerpt.rendered}
                <a href=${verbete.link} target="_blank">${i18n.seeOnGlossary}</a>`
            body.append(template)
        }

        for (const target of tooltips) {
            const tooltip = document.querySelector(`#tooltip-template-${target.dataset.verbeteId}`)
            this.initializeTooltip(target, tooltip)
        }

        console.log('Glossary tooltips were started')
    }

    initializeTooltip (target, tooltip) {
        const closeButton = tooltip.querySelector('.glossary-tooltip__close button')

        const popper = createPopper(target, tooltip, {
            modifiers: [
                { name: 'offset', options: { offset: [0, 8] } },
            ],
        })

        let unbindClickEvent

        const show = () => {
            tooltip.setAttribute('data-show', '')
            popper.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    { name: 'eventListeners', enabled: true },
                ],
            }))
            popper.update()

            window.setTimeout(() => {
                unbindClickEvent = clickOutside(tooltip, hide)
            }, 100)
        }

        const hide = () => {
            tooltip.removeAttribute('data-show')
            popper.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    { name: 'eventListeners', enabled: false },
                ],
            }))
            unbindClickEvent()
        }

        closeButton.addEventListener('click', hide)
        target.addEventListener('click', show)
    }
}
