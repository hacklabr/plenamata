const longDateLocale = new Intl.DateTimeFormat('en', { dateStyle: 'long' })
const numberLocale = new Intl.NumberFormat('en')
const shortDateLocale = new Intl.DateTimeFormat('en', { dateStyle: 'short' })

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
