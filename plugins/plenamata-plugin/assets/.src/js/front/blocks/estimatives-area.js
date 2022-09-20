import { sprintf } from '@wordpress/i18n'

import { __ } from '../dashboard/plugins/i18n'
import { fetchDeterData, fetchLastDate } from '../utils/api'
import { getEstimateDeforestation } from '../utils/estimates'
import { roundNumber, shortDate } from '../utils/filters'

const { DateTime, Interval } = window.luxon

document.defaultView.document.addEventListener('DOMContentLoaded', async () => {
    const updated = await fetchLastDate()
    const lastDate = DateTime.fromISO(updated.deter_last_date, { zone: 'utc' })

    const startOfYear = lastDate.startOf('year')
    const thisYear = await fetchDeterData({ data1: startOfYear.toISODate(), data2: updated.deter_last_date })

    const daysThisYear = Interval.fromDateTimes(startOfYear, lastDate)

    const lastWeek = await fetchDeterData({ data1: lastDate.minus({ days: 6 }).toISODate(), data2: updated.deter_last_date })

	document.querySelectorAll('[data-deter]').forEach(async (el) => {
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
            const { hectares, hectaresPerSecond } = await getEstimateDeforestation({}, { DateTime, Interval })

            let hectaresCount = hectares
            el.textContent = roundNumber(hectaresCount)

            setInterval(() => {
                hectaresCount += hectaresPerSecond
                el.textContent = roundNumber(hectaresCount)
            }, 1000)
        }
        else if (deterLabel === 'sourcesLastWeek') {
            const sourcesLastWeek = sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), shortDate(updated.last_sync), shortDate(updated.deter_last_date))
            el.textContent = sourcesLastWeek
        }
        else if (deterLabel === 'treesEstimative') {
            const { trees, treesPerSecond } = await getEstimateDeforestation({}, { DateTime, Interval })

            let treeCount = trees
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
