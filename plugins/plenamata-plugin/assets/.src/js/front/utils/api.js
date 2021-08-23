const cache = {}

function request (method, urlFragment) {
    const url = urlFragment.startsWith('http') ? urlFragment : `http://plenamata.solved.eco.br/api/${urlFragment}`

    const cacheKey = `${method} ${url}`
    if (cache[cacheKey]) {
        Promise.resolve(cache[cacheKey])
    }

    return window.fetch(url, { method }).then((req) => req.json()).then((json) => {
        cache[cacheKey] = json
        return json
    })
}

export default {
    get (url) {
        return request('GET', url)
    }
}
