<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Yearly deforestation alerts in the selected territory (DETER)', 'plenamata') }}
        </template>
        <template #filters>
            <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                <option value="ha">{{ __('hectares', 'plenamata') }}</option>
                <option value="km2">{{ __('km²', 'plenamata') }}</option>
            </select>
        </template>
        <template #chart>
            <BarChart :chartData="chartData" :height="300" :options="chartOptions"/>
        </template>
        <template #footer>
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.sync, updated.deter) }}
            {{ sprintf(__('The figures represent deforestation for each year up to %s.', 'plenamata'), previousMonth) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'
    import { BarChart } from 'vue-chart-3'

    import DashboardPanel from './DashboardPanel.vue'
    import { __, _x, sprintf } from '../plugins/i18n'
    import { getAreaKm2 } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    const months = [
        null,
        _x('January', 'months', 'plenamata'),
        _x('February', 'months', 'plenamata'),
        _x('March', 'months', 'plenamata'),
        _x('April', 'months', 'plenamata'),
        _x('May', 'months', 'plenamata'),
        _x('June', 'months', 'plenamata'),
        _x('July', 'months', 'plenamata'),
        _x('August', 'months', 'plenamata'),
        _x('September', 'months', 'plenamata'),
        _x('October', 'months', 'plenamata'),
        _x('November', 'months', 'plenamata'),
        _x('December', 'months', 'plenamata'),
    ]

    export default {
        name: 'YearlyDeforestationEvolutionDeter',
        components: {
            BarChart,
            DashboardPanel,
        },
        props: {
            date: { type: DateTime, required: true },
            filters: { type: Object, required: true },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
        },
        data () {
            return {
                data: [],
            }
        },
        computed: {
            areas () {
                if (this.unit === 'ha') {
                    return this.areasKm2.map((areaKm2) => areaKm2 * 100)
                } else {
                    return this.areasKm2
                }
            },
            areasKm2 () {
                return this.years.map((year) => {
                    const datum = this.data.find((datum) => datum[0]?.year === year)
                    return datum ? getAreaKm2(datum[0]) : 0
                })
            },
            chartData () {
                return {
                    labels: this.years,
                    datasets: [
                        {
                            data: this.areas,
                            backgroundColor: '#FF7373',
                        },
                    ],
                }
            },
            chartOptions () {
                return {
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: ({ raw }) => sprintf(this.unit === 'ha' ? __('%s ha', 'plenamata') : __('%s km²', 'plenamata'), roundNumber(raw)),
                            },
                        },
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: (value) => roundNumber(value),
                            },
                        },
                    },
                }
            },
            intervals () {
                const start = this.date.startOf('year')
                const end = this.date

                const intervals = [[start, end]]
                for (let i = 1; i < 5; i++) {
                    intervals.unshift([start.minus({ years: i }), end.minus({ years: i }).endOf('year')])
                }
                return intervals
            },
            previousMonth () {
                const month = this.date.month
                return months[month]
            },
            unitModel: vModel('unit'),
            years () {
                return this.intervals.map(([start]) => start.year)
            },
        },
        watch: {
            filters: {
                handler: 'fetchData',
                immediate: true,
                deep: true,
            },
        },
        methods: {
            async fetchData () {
                const data = await Promise.all(this.intervals.map(([start, end]) => {
                    return fetchDeterData({ ...this.filters, data1: start.toISODate(), data2: end.toISODate(), group_by: 'ano' })
                }))
                this.data = data
            },
        },
    }
</script>
