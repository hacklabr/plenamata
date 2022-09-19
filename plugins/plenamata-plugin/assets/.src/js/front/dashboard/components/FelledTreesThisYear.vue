<template>
    <DashboardPanel type="measure" icon="arvore.svg" icon2="relogio-small.svg">
        <template #estimativa>{{__( 'Estimates for the year', 'plenamata' )}} {{year}}</template>
        <template #title>
            <strong>{{__('Trees cut down in', 'plenamata')}} {{year}}</strong>
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
            <span>{{__('trees/minute', 'plenamata')}}</span>
        </template>
        <template #source>{{ __('Source', 'plenamata') }}: MapBiomas</template>
    </DashboardPanel>
</template>

<script>
    import { DateTime, Interval } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import Tooltip from './Tooltip.vue'
    import { getTrees } from '../../utils'
    import { roundNumber } from '../../utils/filters'
    import { sprintf, __ } from '../plugins/i18n'

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
                year: DateTime.now().year,
            }
        },
        computed: {
            lastFriday () {
                const now = DateTime.now()
                const lastFriday = DateTime.fromObject({ weekday: 5, hour: 3 })

                if (now < lastFriday) {
                    return lastFriday.minus({ weeks: 1 })
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
