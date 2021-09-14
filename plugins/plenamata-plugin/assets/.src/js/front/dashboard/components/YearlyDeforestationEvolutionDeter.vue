<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Yearly deforestation alerts (DETER)', 'plenamata') }}
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
    import api from '../../utils/api'
    import { humanNumber, roundNumber } from '../../utils/filters'
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
            lastUpdate: { type: Object, required: true },
            state: { type: String, required: true },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
        },
        data () {
            const end = DateTime.now()
            const start = end.startOf('year')

            return {
                date: {
                    start,
                    end,
                },
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
                return this.data.map(datum => Number(datum[0].areamunkm))
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
                                label: ({ raw }) => sprintf(this.unit === 'ha' ? __('%s ha', 'plenamata') : __('%s km²', 'plenamata'), humanNumber(raw)),
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
            previousMonth () {
                const month = DateTime.fromISO(this.lastUpdate.deter_last_date).month
                return months[month]
            },
            unitModel: vModel('unit'),
            years () {
                return this.data.map(datum => datum[0].year)
            },
        },
        watch: {
            state: {
                handler: 'fetchData',
                immediate: true,
            },
        },
        methods: {
            async fetchData () {
                const { start, end } = this.date

                const filters = this.state ? `estados?estado=${this.state}&` : 'basica?'

                const intervals = [[start, end]]
                for (let i = 1; i < 5; i++) {
                    intervals.unshift([start.minus({ years: i }), end.minus({ years: i })])
                }

                const data = await Promise.all(intervals.map(([start, end]) => {
                    return api.get(`deter/${filters}data1=${start.toISODate()}&data2=${end.toISODate()}&group_by=ano`)
                }))
                this.data = data
            },
        },
    }
</script>
