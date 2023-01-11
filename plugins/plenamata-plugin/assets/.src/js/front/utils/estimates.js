import { fetchDeterData, fetchLastDate } from './api'
import { getAreaKm2, getTrees } from './index'

const CONVERGENCE_FACTOR = 0.1

export function getEstimateYear (currentYear, lastDate, { DateTime }) {
    const lastDt = DateTime.fromISO(lastDate)
    if (currentYear === lastDt.year && lastDt.ordinal >= 8) {
        return currentYear
    } else {
        return currentYear - 1
    }
}

export async function getEstimateDeforestation (filters, { DateTime, Interval }) {
    let now = DateTime.now()
    const lastDate = await fetchLastDate()
    const workingYear = getEstimateYear(now.year, lastDate.deter_last_date, { DateTime })

    // Return early if no data for current year is available
    if (now.year !== workingYear) {
        const workingData = await fetchDeterData({ ...filters, data1: `${workingYear}-01-01`, data2: `${workingYear}-12-31` })

        return {
            hectaresPerSecond: 0,
            hectares: getAreaKm2(workingData[0]) * 100,
            treesPerSecond: 0,
            trees: getTrees(workingData[0]),
            year: workingYear,
        }
    }

    let lastFriday = DateTime.fromObject({ weekday: 5, hour: 11 }) // Friday, 11:00 A.M.
    if (now < lastFriday) {
        lastFriday = lastFriday.minus({ weeks: 1 })
    }

    const previousFriday = lastFriday.minus({ weeks: 1 })
    const penultimateSaturday = previousFriday.minus({ days: 6 })

    const [thisYear, lastWeek, previousWeek] = await Promise.all([
        fetchDeterData({ ...filters, data1: now.startOf('year').toISODate(), data2: previousFriday.toISODate() }),
        fetchDeterData({ ...filters, data1: penultimateSaturday.toISODate(), data2: previousFriday.toISODate() }),
        fetchDeterData({ ...filters, data1: penultimateSaturday.minus({ weeks: 1 }).toISODate(), data2: previousFriday.minus({ weeks: 1 }).toISODate() }),
    ])

    // If previous week has no data, returns 0
    const treesThisYear = getTrees(thisYear[0])
    const treesLastWeek = getTrees(lastWeek[0])
    const treesPreviousWeek = getTrees(previousWeek[0])

    // If previous week has no data, returns 0
    const hectaresThisYear = getAreaKm2(thisYear[0]) * 100
    const hectaresLastWeek = getAreaKm2(lastWeek[0]) * 100
    const hectaresPreviousWeek = getTrees(previousWeek[0])

    now = DateTime.now()
    const startDate = (lastFriday.year === now.year) ? lastFriday : now.startOf('year')
    const elapsedTime = Interval.fromDateTimes(startDate, now)
    const elapsedSeconds = elapsedTime.count('seconds')

    const treesPerSecond = (treesLastWeek || (treesPreviousWeek * CONVERGENCE_FACTOR)) / 604_800
    const hectaresPerSecond = (hectaresLastWeek || (hectaresPreviousWeek * CONVERGENCE_FACTOR)) / 604_800

    /**
     * If tressLastWeek !== 0
     *     treesThisYearUntilLastWeek - treesLastWeek = treesThisYearUntilPreviousWeek
     * If treesLastWeek === 0
     *     treesThisYearUntilPreviousWeek - 0 = treesThisYearUntilPreviousWeek
     * So we obtain the same start (treesThisYearUntilPreviousWeek) no matter if we have last week data
     */
    const treeCount = (treesThisYear - treesLastWeek) + (elapsedSeconds * treesPerSecond)
    const hectaresCount = (hectaresThisYear - hectaresLastWeek) + (elapsedSeconds * hectaresPerSecond)

    return {
        hectaresPerSecond,
        hectares: hectaresCount,
        treesPerSecond,
        trees: treeCount,
        year: now.year,
    }
}
