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

        const res = await window.fetch(`${restUrl}wp/v2/verbete?include=${[...verbetesIds].join(',')}&fields=excerpt,id,link,title`)
        const verbetes = await res.json()

        for (const verbete of verbetes) {
            const template = document.createElement('div')
            template.id = `tooltip-template-${verbete.id}`
            template.classList.add('glossary-tooltip__tooltip')
            template.innerHTML = `
                <h2><a href=${verbete.link} target="_blank">${verbete.title.rendered}</a></h2>
                ${verbete.excerpt.rendered}
                <div class="glossary-tooltip__arrow" data-popper-arrow></div>`
            body.append(template)
        }

        for (const destination of tooltips) {
            this.initializeTooltip(destination, document.querySelector(`#tooltip-template-${destination.dataset.verbeteId}`))
        }

        console.log('Glossary tooltips were started')
    }

    initializeTooltip (source, tooltip) {
        console.log(source, tooltip)

        const popper = createPopper(source, tooltip, {
            modifiers: [
                { name: 'offset', options: { offset: [0, 8] } },
            ],
        })

        const show = () => {
            console.log('show')
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
            console.log('hide')
            tooltip.removeAttribute('data-show')
            popper.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    { name: 'eventListeners', enabled: false },
                ],
            }))
        }

        const showEvents = ['mouseenter', 'focus']
        const hideEvents = ['mouseleave', 'blur']

        showEvents.forEach((event) => source.addEventListener(event, show))
        hideEvents.forEach((event) => source.addEventListener(event, hide))
    }
}
