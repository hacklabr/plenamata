const locale = window.PlenamataDashboard.language || 'en'

const longDateLocale = new Intl.DateTimeFormat(locale, { dateStyle: 'long' })
const numberLocale = new Intl.NumberFormat(locale, {  maximumFractionDigits: 2 })
const shortDateLocale = new Intl.DateTimeFormat(locale, { dateStyle: 'short' })

export function firstValue (value) {
    return Array.isArray(value) ? value[0] : value
}

export function longDate (dateStr) {
    const date = new Date(dateStr)
    return longDateLocale.format(date)
}

export function humanNumber (number) {
    return numberLocale.format(number)
}

export function roundNumber (number) {
    return numberLocale.format(Math.round(number))
}

export function shortDate (dateStr) {
    const date = new Date(dateStr)
    return shortDateLocale.format(date)
}
