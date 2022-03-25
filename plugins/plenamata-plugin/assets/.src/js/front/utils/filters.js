const locale = window.PlenamataDashboard.language || 'en'

const longDateLocale = new Intl.DateTimeFormat(locale, { dateStyle: 'long', timeZone: 'UTC' })
const numberLocale = new Intl.NumberFormat(locale, {  maximumFractionDigits: 2 })
const shortDateLocale = new Intl.DateTimeFormat(locale, { dateStyle: 'short', timeZone: 'UTC' })

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
    if (Math.abs(number) < 1) {
        const roundedNumber = numberLocale.format(number)
        return roundedNumber === '-0' ? '0' : roundedNumber
    }
    return numberLocale.format(Math.round(number))
}

export function shortDate (dateStr) {
    const date = new Date(dateStr)
    return shortDateLocale.format(date)
}
export function stateCodeByName( state ) {
    const states = {
        'Acre': 'AC',
        'Alagoas': 'AL',
        'Amazonas':'AM',
        'Amapá': 'AP',
        'Bahia': 'BA',
        'Ceará': 'CE',
        'Espírito Santo': 'ES',
        'Goiás': 'GO',
        'Maranhão': 'MA',
        'Mato Grosso': 'MT',
        'Mato Grosso do Sul': 'MS',
        'Minas Gerais': 'MG',
        'Pará': 'PA',
        'Paraíba': 'PB',
        'Paraná': 'PR',
        'Pernambuco': 'PE',
        'Piauí': 'PI',
        'Rio de Janeiro': 'RJ',
        'Rio Grande do Norte': 'RN',
        'Rondônia':'RO',
        'Roraima': 'RR',
        'Santa Catarina': 'SC',
        'São Paulo': 'SP',
        'Sergipe': 'SE',
        'Tocantins': 'TO',
        'Distrito Federal': 'DF'
    }
    if ( typeof states[state] == 'string') {
        return states[state]
    } else {
        return false
    }
}