import { sprintf } from '@wordpress/i18n'

let i18n = null

export const __ = (text, domain) => i18n?.__?.[text] ?? text
export const _x = (text, context, domain) => i18n?._x?.[context]?.[text] ?? text
export { sprintf }

export default {
    install (Vue, { locale }) {
        i18n = locale
        Vue.prototype.__ = __
        Vue.prototype._x = _x
        Vue.prototype.sprintf = sprintf
    }
}
