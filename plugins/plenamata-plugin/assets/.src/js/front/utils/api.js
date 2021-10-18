const BASE_URL = 'https://plenamata.solved.eco.br/api/'
const cache = new Map()

async function get (urlFragment, cacheable = true) {
    const url = urlFragment.startsWith('http') ? urlFragment : `${BASE_URL}${urlFragment}`

    if (cacheable && cache.has(url)) {
        return Promise.resolve(cache.get(url))
    }

    try {
        const req = await window.fetch(url, { method: 'GET' })
        const data = await req.json()
        if (cacheable) {
            cache.set(url, data)
        }
        return data
    } catch (err) {
        console.error(err)
        return Promise.reject(undefined)
    }
}

function searchParams (object) {
    const params = new URLSearchParams()
    for (const [key, value] of Object.entries(object)) {
        params.set(key, value)
    }
    return params
}

export async function fetchDeterData ({ estado, municipio, ti, uc, ...args }) {
    const params = searchParams(args)

    if (estado) {
        return get(`${BASE_URL}deter/estados?estado=${estado}&${params}`)
    } else if (municipio) {
        const collection = await get(`${BASE_URL}deter/municipios?${params}`)
        if (municipio === true) {
            return collection
        } else {
            return collection.filter(item => item.geo_cod === municipio)
        }
    } else if (ti) {
        const collection = await get(`${BASE_URL}deter/ti?${params}`)
        if (ti === true) {
            return collection
        } else {
            return collection.filter(item => item.terra_indigena === ti)
        }
    } else if (uc) {
        const collection = await get(`${BASE_URL}deter/uc?${params}`)
        if (uc === true) {
            return collection
        } else {
            return collection.filter(item => item.uc === uc)
        }
    } else {
        return get(`${BASE_URL}deter/basica?${params}`)
    }
}

export async function fetchLastDate () {
    return get(`${BASE_URL}deter/last_date`)
}

export async function fetchNews (state = '') {
    return get(`${window.PlenamataDashboard.restUrl}wp/v2/posts/?_embed&state=${state}`, false)
}

export async function fetchProdesData ({ estado, municipio, ti, uc, ...args }) {
    const params = searchParams(args)

    if (estado) {
        return get(`${BASE_URL}prodes/taxaanoestado?uf=${estado}`)
    } else if (municipio) {
        const collection = await get(`${BASE_URL}prodes/municipios?${params}`)
        if (municipio === true) {
            return collection
        } else {
            return collection.filter(item => item.geo_cod === municipio)
        }
    } else if (ti) {
        const collection = await get(`${BASE_URL}prodes/ti?${params}`)
        if (ti === true) {
            return collection
        } else {
            return collection.filter(item => item.terra_indigena === ti)
        }
    } else if (uc) {
        const collection = await get(`${BASE_URL}prodes/uc?${params}`)
        if (uc === true) {
            return collection
        } else {
            return collection.filter(item => item.uc === uc)
        }
    } else {
        return get(`${BASE_URL}prodes/taxaano`)
    }
}
