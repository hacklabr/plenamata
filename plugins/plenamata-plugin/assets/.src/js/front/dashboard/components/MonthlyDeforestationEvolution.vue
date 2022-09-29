<template>
    <DashboardPanel type="chart">
        <template #title>
            <strong>{{ __('Monthly deforestation rate', 'plenamata') }}</strong>
            <span>{{ __('in the selected territory', 'plenamata') }}</span>
        </template>
        <template #filters>
            <Dropdown id="unit-mdev" keyId="key" keyLabel="label" triggerClass="clean small color--3" :activeField.sync="fieldModel" :options="units" :title="__('Unit', 'plenamata')" v-model="unitModel"/>
            <Dropdown id="source-mdev" keyId="key" keyLabel="label" triggerClass="clean small color--3" :activeField.sync="fieldModel" :options="sources" :title="__('Timeframe', 'plenamata')" v-model="sourceModel"/>
        </template>
        <template #chart>
            <ScrollGuard :scrolled="scrolled">
                <Bar :chartData="chartData" :chartOptions="chartOptions" :height="263"/>
            </ScrollGuard>
        </template>
        <template #source>
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.sync, updated.deter) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'
    import { Bar } from 'vue-chartjs'

    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
    import ScrollGuard from './ScrollGuard.vue'
    import HasScrollableChart from '../mixins/HasScrollableChart'
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
        name: 'MonthlyDeforestationEvolution',
        components: {
            Bar,
            DashboardPanel,
            Dropdown,
            ScrollGuard,
        },
        mixins: [
            HasScrollableChart,
        ],
        props: {
            activeField: { type: [Object, String], default: '' },
            date: { type: DateTime, required: true },
            filters: { type: Object, required: true },
            source: { type: String, default: 'prodes' },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
        },
        data () {
            return {
                data: [],
                sources: {
                    'deter': {
                        key : 'deter',
                        label : __('during DETER year', 'plenamata')
                    },
                    'prodes': {
                        key : 'prodes',
                        label : __('during PRODES year', 'plenamata')
                    }
                },
                units: {
                    'ha': {
                        key : 'ha',
                        label : __('hectares', 'plenamata')
                    },
                    'km2': {
                        key : 'km2',
                        label : __('km²', 'plenamata')
                    }
                },
            }
        },
        computed: {
            chartData () {
                const factor = this.unit === 'ha' ? 100 : 1

                return {
                    labels: this.months.map((month) => months[month]),
                    datasets: this.datasets.map((dataset) => {
                        return {
                            label: dataset.label,
                            data: dataset.data.map((datum) => getAreaKm2(datum) * factor),
                            backgroundColor: dataset.backgroundColor,
                        }
                    }),
                }
            },
            chartOptions () {
                const months = this.months.length - 1

                return {
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: ({ raw }) => sprintf(this.unit === 'ha' ? __('%s ha', 'plenamata') : __('%s km²', 'plenamata'), roundNumber(raw)),
                                title: ([{ dataset, label }]) => `${label} (${dataset.label})`,
                            },
                        },
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'x',
                                onPan: this.onChartPan,
                            },
                        },
                    },
                    scales: {
                        x: {
                            min: Math.max(0, months - 2),
                            max: months,
                        },
                        y: {
                            type: 'linear',
                            min: 0,
                            suggestedMax: this.maxValue,
                            ticks: {
                                callback: (value) => roundNumber(value),
                            },
                        },
                    },
                }
            },
            datasets () {
                const datasets = []

                const backgroundColors = ['#B4E8C9', '#82C79E', '#629A79', '#416951', '#263F30']

                const startYear = this.startDate.year
                for (let i = 1; i <= 5; i++) {
                    const referenceYear = startYear + i

                    const dataset = {
                        label: referenceYear,
                        data: this.months.map((month) => {
                            const year = this.source === 'prodes' && month < 8 ? referenceYear + 1 : referenceYear
                            return this.data.find((datum) => {
                                return datum.month === month && datum.year === year
                            }) || {}
                        }),
                        backgroundColor: backgroundColors[i - 1],
                        barThickness: 50,
                    }
                    datasets.push(dataset)
                }

                return datasets
            },
            fieldModel: vModel('activeField'),
            filterKey () {
                return JSON.stringify({ ...this.filters, source: this.source })
            },
            maxValue () {
                return Math.max(...this.chartData.datasets.map((dataset) => {
                    return Math.max(...dataset.data.filter(Boolean))
                }))
            },
            months () {
                if (this.data.length === 0) {
                    return []
                }

                const months = this.source === 'deter'
                    ? [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    : [8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7]

                const maxMonth = this.period.end.minus({ months: 1 }).month
                const index = months.findIndex((i) => i === maxMonth)
                return months.slice(0, index + 1)
            },
            period () {
                return {
                    start: this.date.minus({ years: 5 }).startOf('year'),
                    end: this.date,
                }
            },
            sourceModel: vModel('source'),
            startDate () {
                if (this.source === 'deter') {
                    return this.period.start
                } else if (this.period.start.month < 8) {
                    return this.period.end.minus({ years: 5 }).set({ month: 8 })
                } else {
                    return this.period.end.minus({ years: 4 }).set({ month: 8 })
                }
            },
            unitModel: vModel('unit'),
        },
        watch: {
            filterKey: {
                handler: 'fetchData',
                immediate: true,
            },
        },
        methods: {
            async fetchData () {
                const data = await fetchDeterData({ ...this.filters, data1: this.startDate.toISODate(), data2: this.period.end.toISODate(), group_by: 'mes' })
                this.data = data
            },
        },
    }
</script>
