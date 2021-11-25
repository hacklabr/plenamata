import { createPopper } from '@popperjs/core'

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

        const res = await window.fetch(`${restUrl}wp/v2/verbete?include=${[...verbetesIds].join(',')}&fields=excerpt,id,title`)
        const verbetes = await res.json()

        for (const verbete of verbetes) {
            const template = document.createElement('div')
            template.id = `tooltip-template-${verbete.id}`
            template.classList.add('glossary-tooltip__tooltip')
            template.innerHTML = `
                <h2>${verbete.title.rendered}</h2>
                ${verbete.excerpt.rendered}
                <div class="glossary-tooltip__arrow" data-popper-arrow></div>`
            body.append(template)
        }

        for (const target of tooltips) {
            const tooltip = document.querySelector(`#tooltip-template-${target.dataset.verbeteId}`)
            this.initializeTooltip(target, tooltip)
        }

        console.log('Glossary tooltips were started')
    }

    initializeTooltip (target, tooltip) {
        const popper = createPopper(target, tooltip, {
            modifiers: [
                { name: 'offset', options: { offset: [0, 8] } },
            ],
        })

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
        }

        target.addEventListener('blur', hide)
        target.addEventListener('focus', show)
        target.addEventListener('mouseenter', show)
        target.addEventListener('mouseleave', hide)
    }
}
