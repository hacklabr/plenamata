function request (method, urlFragment) {
    const url = urlFragment.startsWith('http') ? urlFragment : `http://plenamata.solved.eco.br/api/${urlFragment}`
    return new Promise((resolve, reject) => {
        try {
            window.fetch(url, { method }).then((req) => {
                if (req.ok) {
                    resolve(req.json())
                } else {
                    reject(req.json())
                }
            })

        } catch (err) {
            console.error(err)
            reject(undefined)
        }
    })
}

export default {
    get (url) {
        return request('GET', url)
    }
}
