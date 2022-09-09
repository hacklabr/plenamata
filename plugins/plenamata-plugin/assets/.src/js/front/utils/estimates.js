import { fetchDeterData } from './api'
import { getTrees } from './index'

export async function getEstimateDeforestation (filters, { DateTime, Interval }) {
    const now = DateTime.now()

    let lastFriday = DateTime.fromObject({ weekday: 5, hour: 3 }) // Friday, 03:00 A.M.
    if (now < lastFriday) {
        lastFriday = lastFriday.minus({ weeks: 1 })
    }

    const previousFriday = lastFriday.minus({ weeks: 1 })
    const penultimateSaturday = previousFriday.minus({ days: 6 })

    const [thisYear, lastWeek, previousWeek] = await Promise.all([
        fetchDeterData({ ...filters, data1: now.startOf('year').toISODate(), data2: previousFriday.toISODate() }),
        fetchDeterData({ ...filters, data1: penultimateSaturday.toISODate(), data2: previousFriday.toISODate() }),
        fetchDeterData({ ...filters, data1: penultimateSaturday.minus({ weeks: 1 }).toISODate(), data2: penultimateSaturday.toISODate() }),
    ])

    const treesThisYear = getTrees(thisYear[0])
    const treesLastWeek = getTrees(lastWeek[0])
    const treesPreviousWeek = getTrees(previousWeek[0])

    const treesPerSecond = (treesLastWeek || treesPreviousWeek) / 604_800
    const startDate = (lastFriday.year === now.year) ? lastFriday : now.startOf('year')
    const elapsedTime = Interval.fromDateTimes(startDate, now)

    const treeCount = (treesThisYear - treesLastWeek) + (elapsedTime.count('seconds') * treesPerSecond)

    return {
        treesPerSecond,
        trees: treeCount,
        year: now.year,
    }
}
