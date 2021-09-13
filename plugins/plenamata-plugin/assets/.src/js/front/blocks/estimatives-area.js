import { sprintf } from '@wordpress/i18n'

const { DateTime, Interval } = window.luxon
const i18n = window.PlenamataHomeEstimatives.i18n

const __ = (text, domain) => i18n?.__?.[text] ?? text
const _x = (text, context, domain) => i18n?._x?.[context]?.[text] ?? text

const numberLocale = new Intl.NumberFormat(window.PlenamataHomeEstimatives.language || 'en')
const shortDateLocale = new Intl.DateTimeFormat(window.PlenamataHomeEstimatives.language || 'en', { dateStyle: 'short' })

function formatDate (datetime) {
    return shortDateLocale.format(datetime.toJSDate()).replaceAll('/', '.')
}

function formatNumber (number) {
    return numberLocale.format(Math.round(number))
}

async function fetchApi (urlFragment) {
    const url = `https://plenamata.solved.eco.br/api/${urlFragment}`

    try {
        const req = await window.fetch(url, { method: 'GET' })
        return req.json()
    } catch (err) {
        console.error(err)
    }
}

document.defaultView.document.addEventListener('DOMContentLoaded', async () => {
    if (window.plenamataEstimativesStarted) {
        return
    }
    window.plenamataEstimativesStarted = true

    const now = DateTime.now()
    const startOfYear = now.startOf('year')

    const [updated, thisYear] = await Promise.all([
        fetchApi('deter/last_date'),
        fetchApi(`deter/basica?data1=${startOfYear.toISODate()}&data2=${now.toISODate()}`)
    ])

    const lastSync = DateTime.fromISO(updated.last_sync)
    const lastDate = DateTime.fromISO(updated.deter_last_date)

    const daysThisYear = Interval.fromDateTimes(startOfYear, lastDate)
    const elapsedTime = Interval.fromDateTimes(lastDate, now)

    const lastWeek = await fetchApi(`deter/basica?data1=${lastDate.minus({ weeks: 1 }).toISODate()}&data2=${lastDate.toISODate()}`)

	document.querySelectorAll('[data-deter]').forEach((el) => {
        const deterLabel = el.dataset.deter

        if (deterLabel === 'hectaresLastWeek') {
            const hectaresLastWeek = Number(lastWeek[0].areamunkm) * 100
            el.textContent = formatNumber(hectaresLastWeek)
        }
        else if (deterLabel === 'hectaresPerDay') {
            const hectaresPerDay = (Number(thisYear[0].areamunkm) * 100) / daysThisYear.count('days')
            el.textContent = formatNumber(hectaresPerDay)
        }
        else if (deterLabel === 'hectaresThisYear') {
            const hectaresThisYear = Number(thisYear[0].areamunkm) * 100
            el.textContent = formatNumber(hectaresThisYear)
        }
        else if (deterLabel === 'sourcesLastWeek') {
            const sourcesLastWeek = sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), formatDate(lastSync), formatDate(lastDate))
            el.textContent = sourcesLastWeek
        }
        else if (deterLabel === 'treesEstimative') {
            const treesThisYear = Number(thisYear[0].num_arvores)
            const treesPerSecond = treesThisYear / daysThisYear.count('seconds')

            let treeCount = treesThisYear + (elapsedTime.count('seconds') * treesPerSecond)
            el.textContent = formatNumber(treeCount)

            setInterval(() => {
                treeCount += treesPerSecond
                el.textContent = formatNumber(treeCount)
            }, 1000)
        }
        else if (deterLabel === 'treesPerDay') {
            const treesPerDay = Number(thisYear[0].num_arvores) / daysThisYear.count('days')
            el.textContent = formatNumber(treesPerDay)
        }
    })

    document.querySelectorAll('[data-mask=true]').forEach((el) => {
        const num = Number(el.textContent)
        if (num) {
            el.textContent = numberLocale.format(num)
        }
    })
})
