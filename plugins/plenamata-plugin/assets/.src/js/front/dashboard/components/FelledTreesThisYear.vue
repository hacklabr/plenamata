<template>
    <DashboardPanel type="measure" icon="arvore.svg" icon2="relogio-small.svg">
        <template #reference>{{ __('Estimates for the year', 'plenamata') }} {{ year }}</template>
        <template #title>
            <strong>{{ __('Trees cut down in', 'plenamata') }} {{ year }}</strong>
            <span>{{ __('By deforestation', 'plenamata') }}</span>
        </template>
        <template #tooltip>
            <Tooltip :alt="__('Understand the calculus', 'plenamata')">
                <a :href="$dashboard.explainerUrl" target="_blank">
                    {{ __('Understand the calculus', 'plenamata') }}
                </a>
            </Tooltip>
        </template>
        <template #measure>
            <DashboardMeasure :number="internalTrees"></DashboardMeasure>
        </template>
        <template #meaning>
            <strong>{{roundNumber(treesPerMinute)}}</strong>
            <span>{{ __('trees/minute', 'plenamata') }}</span>
        </template>
        <template #source>{{ __('Source', 'plenamata') }}: MapBiomas</template>
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
