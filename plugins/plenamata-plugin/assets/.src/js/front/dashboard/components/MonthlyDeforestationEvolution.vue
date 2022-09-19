<template>
    <DashboardPanel type="chart">
        <template #title>
            <strong>{{ __('Monthly deforestation rate', 'plenamata') }}</strong> 
            <span>{{ __('in the selected territory', 'plenamata') }}</span>
        </template>
        <template #filters>
            <Dropdown 
                id="unit-mdev" 
                keyId="key"
                keyLabel="label"
                triggerClass="clean small color--3"
                :options="units" 
                :value="unitModel" 
                :title="__('Unit', 'plenamata')"
                :value.sync="unitModel"
                :activeField="fieldActive"
                :activeField.sync="fieldActive"
            />
            <Dropdown 
                id="source-mdev" 
                keyId="key"
                keyLabel="label"
                triggerClass="clean small color--3"
                :options="sources" 
                :value="sourceModel" 
                :title="__('Timeframe', 'plenamata')"
                :value.sync="sourceModel"
                :activeField="fieldActive"
                :activeField.sync="fieldActive"
            />
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
    import Color from 'color'
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
            DashboardPanel,
            Dropdown,
            Bar,
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
            activeField: { type: [ String, Object ], default: '' }
        },
        data () {
            return {
                data: [],
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
                fieldActive : { type: String, defaul: '' },
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
                const color = Color('#263F30')
                const datasets = []

                const startYear = this.startDate.year
                for (let i = 1; i <= 5; i++ ) {
                    const referenceYear = startYear + i

                    let backCol;
                    if( i === 1 ) backCol = '#B4E8C9';
                    else if( i === 2 ) backCol = '#82C79E';
                    else if( i === 3 ) backCol = '#629A79';
                    else if( i === 4 ) backCol = '#416951';
                    else backCol = '#263F30';

                    const dataset = {
                        label: referenceYear,
                        data: this.months.map((month) => {
                            const year = this.source === 'prodes' && month < 8 ? referenceYear + 1 : referenceYear
                            return this.data.find((datum) => {
                                return datum.month === month && datum.year === year
                            }) || {}
                        }),
                        backgroundColor: backCol,
                        barThickness: 50,
                    }

                    datasets.push(dataset)
                
                }

                return datasets
            },
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
            fieldActive: {
                handler( active ){
                    this.$emit( 'update:activeField', active );
                }
            },
            activeField: {
                handler( active ){
                    this.fieldActive = active;
                }
            }
        },
        methods: {
            async fetchData () {
                const data = await fetchDeterData({ ...this.filters, data1: this.startDate.toISODate(), data2: this.period.end.toISODate(), group_by: 'mes' })
                this.data = data
            },
        },
    }
</script>
