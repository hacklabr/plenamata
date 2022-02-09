<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Trees cut down in %s in the selected territory', 'plenamata'), year) }}
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" :number="internalTrees">
                <template #unit>
                    {{ __( 'trees', 'plenamata' ) }}
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimated average of %s trees per minute', 'plenamata'), roundNumber(treesPerMinute)) }}
        </template>
        <template #footer>
            {{ sprintf(__('Source: MapBiomas based on average daily deforestation detected by DETER in %s.', 'plenamata'), year) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime, Interval } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import { getTrees } from '../../utils'
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'FelledTreesThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            date: { type: Object, required: true },
            lastWeek: { type: Object, default: null },
            minutes: { type: Number, required: true },
            trees: { type: Number, required: true },
            year: { type: Number, required: true },
        },
        data () {
            return {
                internalTrees: 0,
                interval: null,
            }
        },
        computed: {
            newTrees () {
                const now = DateTime.now()
                const endDate = (this.date.year === now.year) ? now : this.date.endOf('year')
                const elapsedTime = Interval.fromDateTimes(this.date, endDate)
                return elapsedTime.count('seconds') * this.treesPerSecondLastWeek
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
                this.internalTrees = this.trees + this.newTrees

                if (this.interval) {
                    window.clearInterval(this.interval)
                }

                const now = DateTime.now()
                if (this.date.year === now.year) {
                    this.interval = window.setInterval(() => {
                        this.internalTrees += this.treesPerSecondLastWeek
                    }, 1000)
                }
            },
            roundNumber,
        },
    }
</script>
