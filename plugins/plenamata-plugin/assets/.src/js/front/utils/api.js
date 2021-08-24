const cache = {}

async function request (method, urlFragment) {
    const url = urlFragment.startsWith('http') ? urlFragment : `http://plenamata.solved.eco.br/api/${urlFragment}`

    const cacheKey = `${method} ${url}`
    if (cache[cacheKey]) {
        Promise.resolve(cache[cacheKey])
    }

    try {
        const req = await window.fetch(url, { method })
        return req.json()
    } catch (err) {
        console.error(err)
        Promise.reject(undefined)
    }
}

export default {
    get (url) {
        return request('GET', url)
    }
}
