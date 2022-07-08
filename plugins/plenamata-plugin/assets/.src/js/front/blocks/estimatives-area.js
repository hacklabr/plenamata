import { sprintf } from '@wordpress/i18n'

import { __ } from '../dashboard/plugins/i18n'
import { fetchDeterData, fetchLastDate } from '../utils/api'
import { roundNumber, shortDate } from '../utils/filters'

const { DateTime, Interval } = window.luxon

document.defaultView.document.addEventListener('DOMContentLoaded', async () => {
    const updated = await fetchLastDate()
    const lastDate = DateTime.fromISO(updated.deter_last_date, { zone: 'utc' })

    const now = DateTime.now()
    const startOfYear = lastDate.startOf('year')
    const thisYear = await fetchDeterData({ data1: startOfYear.toISODate(), data2: updated.deter_last_date })

    const daysThisYear = Interval.fromDateTimes(startOfYear, lastDate)

    const lastWeek = await fetchDeterData({ data1: lastDate.minus({ weeks: 1 }).toISODate(), data2: updated.deter_last_date })

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
            const sourcesLastWeek = sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), shortDate(updated.last_sync), shortDate(updated.deter_last_date))
            el.textContent = sourcesLastWeek
        }
        else if (deterLabel === 'treesEstimative') {
            const treesThisYear = Number(thisYear[0].num_arvores)
            const treesLastWeek = Number(lastWeek[0].num_arvores)
            const treesPerSecondLastWeek = treesLastWeek / 604800

            let lastFriday = DateTime.fromObject({ weekday: 5, hour: 12 })
            if (now < lastFriday) {
                lastFriday = lastFriday.minus({ week: 1 })
            }

            const startDate = (lastFriday.year === now.year) ? lastFriday : now.startOf('year')
            const elapsedTime = Interval.fromDateTimes(startDate, now)

            const divergencePoint = DateTime.fromObject({ year: 2022, month: 7, day: 8, hour: 19 })
            const divergentElapsedTime = now.diff(divergencePoint, 'seconds').seconds
            let divergentTreeCount = 273_310_000 + (divergentElapsedTime * 1.5)

            let treeCount = (treesThisYear - treesLastWeek) + (elapsedTime.count('seconds') * treesPerSecondLastWeek)
            el.textContent = roundNumber(Math.max(treeCount, divergentTreeCount))

            setInterval(() => {
                treeCount += treesPerSecondLastWeek
                divergentTreeCount += 1.5
                el.textContent = roundNumber(Math.max(treeCount, divergentTreeCount))
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
