<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Evolução mensal do desmatamento', 'plenamata') }}
        </template>
        <template #filters>
            <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                <option value="ha">{{ __('Hectares desmatados', 'plenamata') }}</option>
                <option value="km2">{{ __('Km² desmatados', 'plenamata') }}</option>
            </select>
            <select :aria-label="__('Timeframe', 'plenamata')" v-model="sourceModel">
                <option value="prodes">{{ __('No ano PRODES', 'plenamata') }}</option>
                <option value="deter">{{ __('No ano DETER', 'plenamata') }}</option>
            </select>
        </template>
        <template #chart>
            <BarChart :chartData="chartData" :height="300" :options="chartOptions"/>
        </template>
        <template #footer>
            {{ __('Fonte: INPE/DETER', 'plenamata') }} • {{ __('Última atualização: 28.06.2021 com dados detectados até 18.06.2021.', 'plenamata') }}
            {{ __('Os dados anuais refletem o desmatamento de cada ano até junho.', 'plenamata') }}
            {{ __('Os dados mensais são de 2021.', 'plenamata') }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { _x } from '@wordpress/i18n'
    import Color from 'color'
    import { DateTime } from 'luxon'
    import { BarChart } from 'vue-chart-3'

    import DashboardPanel from './DashboardPanel.vue'
    import api from '../../utils/api'
    import { vModel } from '../../utils/vue'

    const months = [
        null,
        _x('January', 'month', 'plenamata'),
        _x('February', 'month', 'plenamata'),
        _x('March', 'month', 'plenamata'),
        _x('April', 'month', 'plenamata'),
        _x('May', 'month', 'plenamata'),
        _x('June', 'month', 'plenamata'),
        _x('July', 'month', 'plenamata'),
        _x('August', 'month', 'plenamata'),
        _x('September', 'month', 'plenamata'),
        _x('October', 'month', 'plenamata'),
        _x('November', 'month', 'plenamata'),
        _x('December', 'month', 'plenamata'),
    ]

    export default {
        name: 'MonthlyDeforestationEvolution',
        components: {
            BarChart,
            DashboardPanel,
        },
        props: {
            source: { type: String, default: 'prodes' },
            state: { type: String, required: true },
            unit: { type: String, default: 'ha' },
        },
        data () {
            const start = DateTime.now().minus({ years: 5 }).startOf('year')
            const end = DateTime.now()

            return {
                date: {
                    start,
                    end,
                },
                data: [],
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
                            data: dataset.data.map((datum) => Number(datum.areamunkm) * factor),
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
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'x',
                            },
                        },
                    },
                    scales: {
                        x: {
                            min: Math.max(0, months - 2),
                            max: months,
                        },
                    },
                }
            },
            datasets () {
                const color = Color('#FF7373')
                const datasets = []

                const startYear = this.startDate.year
                for (let i = 1; i <= 5; i++ ) {
                    const referenceYear = startYear + i

                    const dataset = {
                        label: referenceYear,
                        data: this.months.map((month) => {
                            const year = this.source === 'prodes' && month < 8 ? referenceYear + 1 : referenceYear

                            return this.data.find((datum) => {
                                return datum.month === month && datum.year === year
                            }) || {}
                        }),
                        backgroundColor: color.lighten(0.4 - (0.08 * i)).string(),
                        barThickness: 50,
                    }

                    datasets.push(dataset)
                }

                return datasets
            },
            filterKey () {
                return `${this.state}|${this.source}`
            },
            months () {
                if (this.data.length === 0) {
                    return []
                }

                const months = this.source === 'deter'
                    ? [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    : [8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7]

                const maxMonth = this.date.end.minus({ months: 1 }).month
                const index = months.findIndex((i) => i === maxMonth)
                return months.slice(0, index + 1)
            },
            sourceModel: vModel('source'),
            startDate () {
                if (this.source === 'deter') {
                    return this.date.start
                } else if (this.date.start.month < 8) {
                    return this.date.end.minus({ years: 5 }).set({ month: 8 })
                } else {
                    return this.date.end.minus({ years: 4 }).set({ month: 8 })
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
                const filters = this.state ? `estados?estado=${this.state}&` : 'basica?'

                const data = await api.get(`deter/${filters}data1=${this.startDate.toISODate()}&data2=${this.date.end.toISODate()}&group_by=mes`)
                this.data = data
            },
        },
    }
</script>
