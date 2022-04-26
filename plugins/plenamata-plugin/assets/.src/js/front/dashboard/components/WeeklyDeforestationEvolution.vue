<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Weekly deforestation rate in the selected territory', 'plenamata') }}
        </template>
        <template #filters>
            <select :aria-label="__('Period', 'plenamata')" v-model="yearModel">
                <option v-for="year of range" :key="year" :value="year">{{ sprintf(__('Period: %s', 'plenamata'), year) }}</option>
            </select>
            <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                <option value="ha">{{ __('hectares', 'plenamata') }}</option>
                <option value="km2">{{ __('km²', 'plenamata') }}</option>
            </select>
            <select :aria-label="__('Timeframe', 'plenamata')" v-model="sourceModel">
                <option value="deter">{{ __('during DETER year', 'plenamata') }}</option>
                <option value="prodes">{{ __('during PRODES year', 'plenamata') }}</option>
            </select>
        </template>
        <template #chart>
            <ScrollGuard :scrolled="scrolled">
                <BarChart :chartData="chartData" :height="300" :options="chartOptions"/>
            </ScrollGuard>
        </template>
        <template #footer>
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.sync, updated.deter) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'
    import { BarChart } from 'vue-chart-3'

    import DashboardPanel from './DashboardPanel.vue'
    import ScrollGuard from './ScrollGuard.vue'
    import HasScrollableChart from '../mixins/HasScrollableChart'
    import { __, sprintf } from '../plugins/i18n'
    import { getAreaKm2 } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'WeeklyDeforestationEvolution',
        components: {
            BarChart,
            DashboardPanel,
            ScrollGuard,
        },
        mixins: [
            HasScrollableChart,
        ],
        props: {
            date: { type: DateTime, required: true },
            filters: { type: Object, required: true },
            source: { type: String, default: 'prodes' },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
            year: { type: Number, required: true },
        },
        data () {
            return {
                data: [],
            }
        },
        computed: {
            areasKm2 () {
                const { start, end } = this.dateInterval
                const startYear = start.year
                const endYear = end.year

                const sortedData = []

                if (this.source === 'deter') {
                    for (let i = 1; i <= end.weekNumber; i++) {
                        sortedData.push(this.findAreaKm2(i, startYear))
                    }
                } else {
                    if (startYear === endYear) {
                        for (let i = start.weekNumber + 1; i <= end.weekNumber; i++) {
                            sortedData.push(this.findAreaKm2(i, startYear))
                        }
                    } else {
                        for (let i = start.weekNumber + 1; i <= start.weeksInWeekYear; i++) {
                            sortedData.push(this.findAreaKm2(i, startYear))
                        }
                        for (let i = 1; i <= end.weekNumber; i++) {
                            sortedData.push(this.findAreaKm2(i, endYear))
                        }
                    }
                }

                return sortedData
            },
            areas () {
                if (this.unit === 'ha') {
                    return this.areasKm2.map((areaKm2) => areaKm2 * 100)
                } else {
                    return this.areasKm2
                }
            },
            chartData () {
                return {
                    labels: this.areasKm2.map((item, i) => String(i + 1)),
                    datasets: [
                        {
                            data: this.areas,
                            backgroundColor: '#FF7373',
                        },
                    ],
                }
            },
            chartOptions () {
               const weeks = this.areasKm2.length

                return {
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: ({ raw }) => sprintf(this.unit === 'ha' ? __('%s ha', 'plenamata') : __('%s km²', 'plenamata'), roundNumber(raw)),
                                title: ([{ label }]) => sprintf(__('Week %s', 'plenamata'), label),
                            },
                        },
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'x',
                                threashold: 1,
                                onPan: this.onChartPan,
                            },
                        },
                    },
                    scales: {
                        x: {
                            type: 'category',
                            min: Math.max(0, weeks - 15),
                            max: weeks - 1,
                        },
                        y: {
                            type: 'linear',
                            min: 0,
                            suggestedMax: Math.max(...this.areas),
                            ticks: {
                                callback: (value) => roundNumber(value),
                            },
                        },
                    },
                }
            },
            dateInterval () {
                if (this.source === 'deter') {
                    const start = DateTime.fromObject({ day: 1, month: 1, year: this.year })
                    const end = DateTime.min(DateTime.fromObject({ day: 31, month: 12, year: this.year }), this.date)
                    return { start, end }
                } else {
                    const start = DateTime.fromObject({ day: 1, month: 8, year: this.yearModel })
                    const end = DateTime.min(DateTime.fromObject({ day: 31, month: 7, year: this.yearModel + 1 }), this.date)
                    return { start, end }
                }
            },
            filterKey () {
                return JSON.stringify({ ...this.filters, source: this.source, year: this.year })
            },
            maxYear () {
                if (this.source === 'deter' || this.date.month >= 8) {
                    return this.date.year
                } else {
                    return this.date.year - 1
                }
            },
            range () {
                const years = []
                for (let year = 2016; year <= this.maxYear; year++) {
                    years.push(year)
                }
                return years
            },
            sourceModel: vModel('source'),
            unitModel: vModel('unit'),
            yearModel: {
                get () {
                    return this.year > this.maxYear ? this.maxYear : this.year
                },
                set (value) {
                    this.$emit('update:year', value)
                }
            },
        },
        watch: {
            filterKey: {
                handler: 'fetchData',
                immediate: true,
            },
        },
        methods: {
            async fetchData () {
                const { start, end } = this.dateInterval

                const data = await fetchDeterData({ ...this.filters, data1: start.toISODate(), data2: end.toISODate(), group_by: 'semana' })
                this.data = data
            },
            findAreaKm2 (week, year) {
                const found = this.data.find((datum) => {
                    return datum.week === week && datum.year === year
                })
                return found ? getAreaKm2(found) : 0
            },
        },
    }
</script>
