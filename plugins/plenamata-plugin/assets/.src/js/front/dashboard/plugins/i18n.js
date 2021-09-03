import { sprintf } from '@wordpress/i18n'

const i18n = window.PlenamataDashboard.i18n

export const __ = (text, domain) => i18n?.__?.[text] ?? text
export const _x = (text, context, domain) => i18n?._x?.[context]?.[text] ?? text
export { sprintf }

export default {
    install (Vue) {
        Vue.prototype.__ = __
        Vue.prototype._x = _x
        Vue.prototype.sprintf = sprintf
    }
}
