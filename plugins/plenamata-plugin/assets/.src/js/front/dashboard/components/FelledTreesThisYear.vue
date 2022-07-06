<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Estimates of trees cut down in %s in the selected territory', 'plenamata'), year) }}
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" :number="internalTrees">
                <template #tooltip>
                    <Tooltip :alt="__('Understand the calculus', 'plenamata')">
                        <a :href="$dashboard.explainerUrl" target="_blank">
                            {{ __('Understand the calculus', 'plenamata') }}
                        </a>
                    </Tooltip>
                </template>
                <template #unit>
                    {{ __('trees', 'plenamata') }}
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimated average of %s trees per minute', 'plenamata'), roundNumber(treesPerMinute)) }}
        </template>
        <template #footer>
            {{ sprintf(__('Source: MapBiomas based on average daily deforestation detected by INPE in %s.', 'plenamata'), year) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime, Interval } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import Tooltip from './Tooltip.vue'
    import { getTrees } from '../../utils'
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'FelledTreesThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
            Tooltip,
        },
        props: {
            lastWeek: { type: Object, default: null },
            minutes: { type: Number, required: true },
            trees: { type: Number, required: true },
        },
        data () {
            return {
                internalTrees: 0,
                interval: null,
                now: DateTime.now().year,
            }
        },
        computed: {
            lastFriday () {
                const now = DateTime.now()
                const lastFriday = DateTime.fromObject({ weekday: 5, hour: 12 })

                if (now < lastFriday) {
                    return lastFriday.minus({ week: 1 })
                } else {
                    return lastFriday
                }
            },
            newTrees () {
                const now = DateTime.now()
                const startDate = (this.lastFriday.year === now.year) ? this.lastFriday : now.startOf('year')
                const elapsedTime = Interval.fromDateTimes(startDate, now)
                return elapsedTime.count('seconds') * this.treesPerSecondLastWeek
            },
            previousTrees () {
                return this.trees - getTrees(this.lastWeek)
            },
            treesPerMinute () {
                return this.trees / this.minutes
            },
            treesPerSecondLastWeek () {
                return getTrees(this.lastWeek) / 604800
            },
        },
        watch: {
            trees: {
                handler: 'recalculateTrees',
                immediate: true,
            },
        },
        methods: {
            recalculateTrees () {
                this.internalTrees = this.previousTrees + this.newTrees

                if (this.interval) {
                    window.clearInterval(this.interval)
                }

                this.interval = window.setInterval(() => {
                    this.internalTrees += this.treesPerSecondLastWeek
                }, 1000)
            },
            roundNumber,
        },
    }
</script>
