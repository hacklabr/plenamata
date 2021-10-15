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

export async function fetchDeterData ({ estado, municipio, uc, ti, ...args }) {
    if (estado) {
        return get(`${BASE_URL}deter/estados?estado=${estado}&${searchParams(args)}`)
    } else {
        return get(`${BASE_URL}deter/basica?${searchParams(args)}`)
    }
}

export async function fetchLastDate () {
    return get(`${BASE_URL}deter/last_date`)
}

export async function fetchNews (state = '') {
    return get(`${window.PlenamataDashboard.restUrl}wp/v2/posts/?_embed&state=${state}`, false)
}

export async function fetchProdesData ({ estado, municipio, uc, ti, ...args }) {
    if (estado) {
        return get(`${BASE_URL}prodes/taxaanoestado?uf=${estado}`)
    } else {
        return get(`${BASE_URL}prodes/taxaano`)
    }
}
