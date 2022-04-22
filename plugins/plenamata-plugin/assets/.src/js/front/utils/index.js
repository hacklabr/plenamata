import _capitalize from 'capitalize-pt-br'

export function capitalize (phrase) {
    return _capitalize(phrase)
}

export function getAreaKm2 (datum) {
    if (!datum) {
        return 0
    }
    return Number(datum.areakm || datum.areamunkm || datum.areauckm || datum.areatikm || 0)
}

export function getTrees (datum) {
    if (!datum) {
        return 0
    }
    return Number(datum.num_arvores || 0)
}

export function localeSortBy (fn, asc = true) {
    return (a, b) => {
        const newA = fn(a)
        const newB = fn(b)
        const compare = newA.localeCompare(newB)
        if (compare === 0) {
            return 0
        } else {
            return asc ? compare : -compare
        }
    }
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

export function wait (condition, callback, ms = 500) {
    let result = condition()
    if (result) {
        callback(result)
    } else {
        const interval = window.setInterval(() => {
            result = condition()
            if (result) {
                window.clearInterval(interval)
                callback(result)
            }
        }, ms)
    }
}
