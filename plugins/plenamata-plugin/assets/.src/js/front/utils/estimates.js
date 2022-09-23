import { fetchDeterData } from './api'
import { getAreaKm2, getTrees } from './index'

export async function getEstimateDeforestation (filters, { DateTime, Interval }) {
    const now = DateTime.now()

    let lastFriday = DateTime.fromObject({ weekday: 5, hour: 11, minute: 5 }) // Friday, 11:00 A.M.
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

    const hectaresThisYear = getAreaKm2(thisYear[0]) * 100
    const hectaresLastWeek = getAreaKm2(lastWeek[0]) * 100
    const hectaresPerSecondLastWeek = hectaresLastWeek / 604_800

    const startDate = (lastFriday.year === now.year) ? lastFriday : now.startOf('year')
    const elapsedTime = Interval.fromDateTimes(startDate, now)
    const elapsedSeconds = elapsedTime.count('seconds')

    const treeCount = (treesThisYear - treesLastWeek) + (elapsedSeconds * treesPerSecondLastWeek)
    const hectaresCount = (hectaresThisYear - hectaresLastWeek) + (elapsedSeconds * hectaresPerSecondLastWeek)

    return {
        hectaresPerSecond: hectaresPerSecondLastWeek,
        hectares: hectaresCount,
        treesPerSecond: treesPerSecondLastWeek,
        trees: treeCount,
        year: now.year,
    }
}
