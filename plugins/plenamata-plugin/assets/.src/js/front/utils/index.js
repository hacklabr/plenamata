import _capitalize from 'capitalize-pt-br'

export function capitalize (phrase) {
    return _capitalize(phrase)
}

export function getAreaKm2 (datum) {
    return Number(datum.areakm || datum.areamunkm || datum.areauckm || datum.areatikm || 0)
}

export function getTrees (datum) {
    return Number(datum.num_arvores || 0)
}

export function sortBy (fn, asc = true) {
    return (a, b) => {
        const newA = fn(a)
        const newB = fn(b)
        if (newA > newB) {
            return asc ? 1 : -1
        } else if (newA < newB) {
            return asc ? -1 : 1
        } else {
            return 0
        }
    }
}
