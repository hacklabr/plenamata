import { sprintf } from '@wordpress/i18n'

import { __ } from '../dashboard/plugins/i18n'
import { fetchDeterData, fetchLastDate } from '../utils/api'
import { roundNumber, shortDate } from '../utils/filters'

const { DateTime, Interval } = window.luxon

document.defaultView.document.addEventListener('DOMContentLoaded', async () => {
    const now = DateTime.now()
    const startOfYear = now.startOf('year')

    const [updated, thisYear] = await Promise.all([
        fetchLastDate(),
        fetchDeterData({ data1: startOfYear.toISODate(), data2: now.toISODate() }),
    ])

    const lastSync = DateTime.fromISO(updated.last_sync)
    const lastDate = DateTime.fromISO(updated.deter_last_date)

    const daysThisYear = Interval.fromDateTimes(startOfYear, lastDate)
    const elapsedTime = Interval.fromDateTimes(lastDate, now)

    const lastWeek = await fetchDeterData({ data1: lastDate.minus({ weeks: 1 }).toISODate(), data2: lastDate.toISODate() })

	document.querySelectorAll('[data-deter]').forEach((el) => {
        const deterLabel = el.dataset.deter

        if (deterLabel === 'hectaresLastWeek') {
            const hectaresLastWeek = Number(lastWeek[0].areamunkm) * 100
            el.textContent = roundNumber(hectaresLastWeek)
        }
        else if (deterLabel === 'hectaresPerDay') {
            const hectaresPerDay = (Number(thisYear[0].areamunkm) * 100) / daysThisYear.count('days')
            el.textContent = roundNumber(hectaresPerDay)
        }
        else if (deterLabel === 'hectaresThisYear') {
            const hectaresThisYear = Number(thisYear[0].areamunkm) * 100
            el.textContent = roundNumber(hectaresThisYear)
        }
        else if (deterLabel === 'sourcesLastWeek') {
            const sourcesLastWeek = sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), shortDate(lastSync.toJSDate()), shortDate(lastDate.toJSDate()))
            el.textContent = sourcesLastWeek
        }
        else if (deterLabel === 'treesEstimative') {
            const treesThisYear = Number(thisYear[0].num_arvores)
            const treesPerSecond = treesThisYear / daysThisYear.count('seconds')

            let treeCount = treesThisYear + (elapsedTime.count('seconds') * treesPerSecond)
            el.textContent = roundNumber(treeCount)

            setInterval(() => {
                treeCount += treesPerSecond
                el.textContent = roundNumber(treeCount)
            }, 1000)
        }
        else if (deterLabel === 'treesPerDay') {
            const treesPerDay = Number(thisYear[0].num_arvores) / daysThisYear.count('days')
            el.textContent = roundNumber(treesPerDay)
        }
    })

    document.querySelectorAll('[data-mask=true]').forEach((el) => {
        const num = Number(el.textContent)
        if (num) {
            el.textContent = roundNumber(+num)
        }
    })
})
