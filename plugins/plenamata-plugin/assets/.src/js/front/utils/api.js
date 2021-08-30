const cache = new Map()

async function request (method, urlFragment, cacheable = true) {
    const url = urlFragment.startsWith('http') ? urlFragment : `https://plenamata.solved.eco.br/api/${urlFragment}`

    const cacheKey = `${method} ${url}`
    if (cacheable && cache.has(cacheKey)) {
        return Promise.resolve(cache.get(cacheKey))
    }

    try {
        const req = await window.fetch(url, { method })
        const data = await req.json()
        if (cacheable) {
            cache.set(cacheKey, data)
        }
        return data
    } catch (err) {
        console.error(err)
        return Promise.reject(undefined)
    }
}

export default {
    get (url, cacheable = true) {
        return request('GET', url, cacheable)
    }
}
