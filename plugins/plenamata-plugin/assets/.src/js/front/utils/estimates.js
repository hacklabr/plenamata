import { fetchDeterData } from './api'
import { getTrees } from './index'

export async function getEstimateDeforestation (filters, { DateTime, Interval }) {
    const now = DateTime.now()

    let lastFriday = DateTime.fromObject({ weekday: 5, hour: 3 })
    if (now < lastFriday) {
        lastFriday = lastFriday.minus({ weeks: 1 })
    }

    const previousFriday = lastFriday.minus({ weeks: 1 })
    const penultimateFriday = previousFriday.minus({ days: 6 })

    const [thisYear, lastWeek] = await Promise.all([
        fetchDeterData({ ...filters, data1: now.startOf('year').toISODate(), data2: previousFriday.toISODate() }),
        fetchDeterData({ ...filters, data1: penultimateFriday.toISODate(), data2: previousFriday.toISODate() }),
    ])

    const treesThisYear = getTrees(thisYear[0])
    const treesLastWeek = getTrees(lastWeek[0])
    const treesPerSecondLastWeek = treesLastWeek / 604_800

    const startDate = (lastFriday.year === now.year) ? lastFriday : now.startOf('year')
    const elapsedTime = Interval.fromDateTimes(startDate, now)

    let treeCount = (treesThisYear - treesLastWeek) + (elapsedTime.count('seconds') * treesPerSecondLastWeek)

    return {
        treesPerSecond: treesPerSecondLastWeek,
        trees: treeCount,
        year: now.year,
    }
}
