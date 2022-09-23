import { fetchDeterData } from './api'
import { getAreaKm2, getTrees } from './index'

const CONVERGENCE_FACTOR = 0.1

export async function getEstimateDeforestation (filters, { DateTime, Interval }) {
    const now = DateTime.now()

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

    const treesThisYear = getTrees(thisYear[0])
    const treesLastWeek = getTrees(lastWeek[0])
    const treesPreviousWeek = getTrees(previousWeek[0])

    const hectaresThisYear = getAreaKm2(thisYear[0]) * 100
    const hectaresLastWeek = getAreaKm2(lastWeek[0]) * 100
    const hectaresPreviousWeek = getTrees(previousWeek[0])

    const startDate = (lastFriday.year === now.year) ? lastFriday : now.startOf('year')
    const elapsedTime = Interval.fromDateTimes(startDate, now)

    const treesPerSecond = (treesLastWeek || (treesPreviousWeek * CONVERGENCE_FACTOR)) / 604_800
    const hectaresPerSecond = (hectaresLastWeek || (hectaresPreviousWeek * CONVERGENCE_FACTOR)) / 604_800

    const treeCount = (treesThisYear - treesLastWeek) + (elapsedTime.count('seconds') * treesPerSecond)
    const hectaresCount = (hectaresThisYear - hectaresLastWeek) + (elapsedTime.count('seconds') * hectaresPerSecond)

    return {
        hectaresPerSecond,
        hectares: hectaresCount,
        treesPerSecond,
        trees: treeCount,
        year: now.year,
    }
}
