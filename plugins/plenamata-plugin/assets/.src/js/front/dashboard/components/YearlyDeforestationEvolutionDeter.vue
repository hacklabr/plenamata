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
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.deter, updated.sync) }}
            {{ sprintf(__('The figures represent deforestation for each year up to %s.', 'plenamata'), _x('July', 'month', 'plenamata')) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'
    import { BarChart } from 'vue-chart-3'

    import DashboardPanel from './DashboardPanel.vue'
    import api from '../../utils/api'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'YearlyDeforestationEvolutionDeter',
        components: {
            BarChart,
            DashboardPanel,
        },
        props: {
            state: { type: String, required: true },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
        },
        data () {
            const start = DateTime.now().minus({ years: 5 }).startOf('year')
            const end = DateTime.now().minus({ years: 1 }).endOf('year')

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
                return this.data.map(datum => Number(datum.areamunkm))
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
                    },
                }
            },
            unitModel: vModel('unit'),
            years () {
                return this.data.map(datum => datum.year)
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

                const data = await api.get(`deter/${filters}data1=${start.toISODate()}&data2=${end.toISODate()}&group_by=ano`)
                this.data = data
            },
        },
    }
</script>
