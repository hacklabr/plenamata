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
    import { roundNumber } from '../../utils/filters'
    import { getEstimateDeforestation } from '../../utils/estimates'

    export default {
        name: 'FelledTreesThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
            Tooltip,
        },
        props: {
            filters: { type: Object, required: true },
            minutes: { type: Number, required: true },
            trees: { type: Number, required: true },
        },
        data () {
            return {
                internalTrees: 0,
                interval: null,
                year: DateTime.now().year,
            }
        },
        computed: {
            treesPerMinute () {
                return this.trees / this.minutes
            },
        },
        watch: {
            filters: {
                handler: 'recalculateTrees',
                deep: true,
                immediate: true,
            },
        },
        methods: {
            async recalculateTrees () {
                const { trees, treesPerSecond, year } = await getEstimateDeforestation(this.filters, { DateTime, Interval })
                this.internalTrees = trees
                this.year = year

                if (this.interval) {
                    window.clearInterval(this.interval)
                }

                this.interval = window.setInterval(() => {
                    this.internalTrees += treesPerSecond
                }, 1000)
            },
            roundNumber,
        },
    }
</script>
