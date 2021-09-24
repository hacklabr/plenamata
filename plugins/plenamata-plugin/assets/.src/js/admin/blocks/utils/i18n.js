import { sprintf } from '@wordpress/i18n/build-types'

const i18n = window.PlenamataBlocks.i18n

export const __ = (text, domain) => i18n?.__?.[text] ?? text
export const _x = (text, context, domain) => i18n?._x?.[context]?.[text] ?? text
export { sprintf }
