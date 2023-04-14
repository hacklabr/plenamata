const cache = new Map()
const deterApi = window.PlenamataDashboard.deterUrl
const wpRestApi = window.PlenamataDashboard.restUrl

async function get (url, cacheable = true, saveHeaders = false ) {
    if (cacheable && cache.has(url)) {
        return Promise.resolve(cache.get(url))
    }

    try {
        const req = await window.fetch(url, { method: 'GET' })
        const data = await req.json()
        if (cacheable) {
            cache.set(url, data)
        }
        if (saveHeaders) {
            window.lastGetRequestHeader = req.headers
        }
        return data
    } catch (err) {
        console.error(err)
        return Promise.resolve([])
    }
}

function searchParams (object) {
    const params = new URLSearchParams()
    for (const [key, value] of Object.entries(object)) {
        params.set(key, value)
    }
    return params
}

export async function fetchConservationUnits () {
    return get(`${deterApi}deter/lista/uc`)
}

export async function fetchDeterData ({ estado, municipio, ti, uc, ...args }) {
    const params = searchParams(args)

    if (municipio) {
        return get(`${deterApi}deter/municipios?geocode=${municipio}&${params}`)
    } else if (estado) {
        return get(`${deterApi}deter/estados?estado=${estado}&${params}`)
    } else if (ti) {
        return get(`${deterApi}deter/ti?cod=${ti}&${params}`)
    } else if (uc) {
        return get(`${deterApi}deter/uc?cod=${uc}&${params}`)
    } else {
        return get(`${deterApi}deter/basica?${params}`)
    }
}

export async function fetchIndigenousLands () {
    return get(`${deterApi}deter/lista/ti`)
}

export async function fetchLastDate () {
    return get(`${deterApi}deter/last_date`)
}

export async function fetchMunicipalities (uf) {
    return get(`${deterApi}deter/lista/municipio?uf=${uf}`)
}

export async function fetchNews (state = '', pageNum = 1) {
    return get(`${wpRestApi}wp/v2/posts/?_embed&state=${state}&page=${pageNum}`, false, true)
}

export async function fetchUniqueNews (postId) {
    return get(`${wpRestApi}wp/v2/posts/${postId}/?_embed`, false, false)
}

export async function fetchProdesData ({ estado, municipio, ti, uc, ...args }) {
    const params = searchParams(args)

    if (municipio) {
        return get(`${deterApi}prodes/municipios?geocode=${municipio}&${params}`)
    } else if (estado) {
        return get(`${deterApi}prodes/taxaanoestado?uf=${estado}`)
    } else if (ti) {
        return get(`${deterApi}prodes/ti?cod=${ti}&${params}`)
    } else if (uc) {
        return get(`${deterApi}prodes/uc?cod=${uc}&${params}`)
    } else {
        return get(`${deterApi}prodes/taxaano`)
    }
}
