const cache = {}

async function request (method, urlFragment, cacheable = true) {
    const url = urlFragment.startsWith('http') ? urlFragment : `https://plenamata.solved.eco.br/api/${urlFragment}`

    const cacheKey = `${method} ${url}`
    if (cacheable && cache[cacheKey]) {
        Promise.resolve(cache[cacheKey])
    }

    try {
        const req = await window.fetch(url, { method })
        const data = await req.json()
        if (cacheable) {
            cache[cacheKey] = data
        }
        return data
    } catch (err) {
        console.error(err)
        Promise.reject(undefined)
    }
}

export default {
    get (url, cacheable = true) {
        return request('GET', url, cacheable)
    }
}
