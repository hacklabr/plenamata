import { computePosition, flip, offset, shift } from '@floating-ui/dom'

const i18n = window.PlenamataPlugin.i18n.__
const restUrl = window.PlenamataPlugin.restUrl

const OFFSET = 8

export class GlossaryTooltips {

    constructor () {
        this.init()
    }

    async init () {
        const anchor = this.createAnchor()
        const tooltips = document.querySelectorAll('.glossary-tooltip[data-verbete-id]')
        const verbetesIds = new Set()

        for (const tooltip of tooltips) {
            verbetesIds.add(tooltip.dataset.verbeteId)
        }

        if (verbetesIds.size > 0) {
            const res = await window.fetch(`${restUrl}wp/v2/verbete?include=${[...verbetesIds].join(',')}&fields=excerpt,id,link`)
            const verbetes = await res.json()

            for (const verbete of verbetes) {
                const tooltipTemplate = this.createTooltipTemplate(verbete)
                anchor.append(tooltipTemplate)
            }

            for (const target of tooltips) {
                const tooltip = document.querySelector(`#tooltip-template-${target.dataset.verbeteId}`)
                this.initializeTooltip(target, tooltip)
            }
        }

        console.log('Glossary tooltips were started')
    }

    createAnchor () {
        const anchor = document.createElement('div')
        document.body.append(anchor)
        return anchor
    }

    createTooltipTemplate (verbete) {
        const template = document.createElement('div')
        template.id = `tooltip-template-${verbete.id}`
        template.classList.add('glossary-tooltip__tooltip')
        template.innerHTML = `
            <div class="glossary-tooltip__close">
                <button type="button" aria-label="${i18n.close}"><i class="fas fa-times-circle"></i></button>
            </div>
            ${verbete.plenamata_thumbnail
                ? `<img class="glossary-tooltip__thumbnail" src="${verbete.plenamata_thumbnail}">`
                : ''
            }
            ${verbete.excerpt.rendered}
            <a href=${verbete.link} target="_blank">${i18n.seeMore}</a>`
        return template
    }

    initializeTooltip (target, tooltip) {
        const closeButton = tooltip.querySelector('.glossary-tooltip__close button')

        let mouseOnTooltip = false

        const show = async () => {
            target.classList.add('-show')
            tooltip.classList.add('-show')
            const { x, y } = await computePosition(target, tooltip, {
                middleware: [flip(), offset(OFFSET), shift()],
            })
            tooltip.style.transform = `translate(${Math.round(x)}px, ${Math.round(y)}px)`
        }

        const hide = () => {
            target.classList.remove('-show')
            tooltip.classList.remove('-show')
            mouseOnTooltip = false
        }

        closeButton.addEventListener('click', hide)
        this.onEnter(target, show)
        this.onLeave(target, () => {
            window.setTimeout(() => {
                if (!mouseOnTooltip) {
                    hide()
                }
            }, 150)
        })
        this.onEnter(tooltip, () => {
            mouseOnTooltip = true
        })
        this.onLeave(tooltip, hide)
    }

    onEnter (element, callback) {
        element.addEventListener('pointerenter', callback)
        element.addEventListener('touchstart', callback)
    }

    onLeave (element, callback) {
        element.addEventListener('pointerleave', callback)
        element.addEventListener('touchend', callback)
    }
}
