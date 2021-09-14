<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Weekly deforestation rate', 'plenamata') }}
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
            <BarChart :chartData="chartData" :height="300" :options="chartOptions"/>
            <AxisPosition :start="scrollPosition.start" :end="scrollPosition.end" :max="areas.length - 1"/>
        </template>
        <template #footer>
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.sync, updated.deter) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'
    import { BarChart } from 'vue-chart-3'

    import AxisPosition from './AxisPosition.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import HasScrollableChart from '../mixins/HasScrollableChart'
    import { __, sprintf } from '../plugins/i18n'
    import api from '../../utils/api'
    import { humanNumber, roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'WeeklyDeforestationEvolution',
        components: {
            AxisPosition,
            BarChart,
            DashboardPanel,
        },
        mixins: [
            HasScrollableChart,
        ],
        props: {
            now: { type: DateTime, required: true },
            source: { type: String, default: 'prodes' },
            state: { type: String, required: true },
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
                const sortedData = this.data.slice().sort((a, b) => {
                    if (a.year === b.year) {
                        return (a.week > b.week) ? 1 : -1
                    } else {
                        return (a.year > b.year) ? 1 : -1
                    }
                })

                return sortedData.map((datum) => Number(datum.areamunkm))
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
                                label: ({ raw }) => sprintf(this.unit === 'ha' ? __('%s ha', 'plenamata') : __('%s km²', 'plenamata'), humanNumber(raw)),
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
                    const end = DateTime.fromObject({ day: 31, month: 12, year: this.year })
                    return { start, end }
                } else {
                    const start = DateTime.fromObject({ day: 1, month: 8, year: this.yearModel })
                    const end = DateTime.fromObject({ day: 31, month: 7, year: this.yearModel + 1 })
                    return { start, end }
                }
            },
            filterKey () {
                return `${this.state}|${this.year}|${this.source}`
            },
            maxYear () {
                if (this.source === 'deter' || this.now.month >= 8) {
                    return this.now.year
                } else {
                    return this.now.year - 1
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

                const filters = this.state ? `estados?estado=${this.state}&` : 'basica?'

                const data = await api.get(`deter/${filters}data1=${start.toISODate()}&data2=${end.toISODate()}&group_by=semana`)
                this.data = data
            },
        },
    }
</script>
