const numberLocale = new Intl.NumberFormat()

export function humanNumber (number) {
    return numberLocale.format(number)
}

export function roundNumber (number) {
    return numberLocale.format(Math.round(number))
}
