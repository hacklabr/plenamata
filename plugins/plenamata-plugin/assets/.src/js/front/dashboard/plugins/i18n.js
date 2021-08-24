import { __, _n, _nx, _x, sprintf } from '@wordpress/i18n'

export default {
    install (Vue) {
        Vue.prototype.__ = __
        Vue.prototype._n = _n
        Vue.prototype._nx = _nx
        Vue.prototype._x = _x
        Vue.prototype.sprintf = sprintf
    }
}
