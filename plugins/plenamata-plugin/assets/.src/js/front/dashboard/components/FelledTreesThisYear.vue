<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Trees cut down in %s', 'plenamata'), year) }}
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
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'FelledTreesThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            lastUpdate: { type: Object, required: true },
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
                const elapsedTime = Interval.fromDateTimes(DateTime.fromISO(this.lastUpdate.deter_last_date), DateTime.now())
                return elapsedTime.count('seconds') * this.treesDelta
            },
            treesDelta () {
                return this.treesPerMinute / 60
            },
            treesPerMinute () {
                return this.trees / this.minutes
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

                this.interval = window.setInterval(() => {
                    this.internalTrees += this.treesDelta
                }, 1000)
            },
            roundNumber,
        },
    }
</script>
