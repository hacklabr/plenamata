<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Evolução anual do desmatamento consolidado', 'plenamata') }}
        </template>
        <template #filters>
            <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                <option value="ha">{{ __('Hectares desmatados', 'plenamata') }}</option>
                <option value="km2">{{ __('Km² desmatados', 'plenamata') }}</option>
            </select>
        </template>
        <template #chart>
            <BarChart :chartData="chartData" :height="200"/>
        </template>
        <template #footer>
            {{ __('Fonte: PRODES', 'plenamata') }}
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
        name: 'YearlyDeforestationEvolutionProdes',
        components: {
            BarChart,
            DashboardPanel,
        },
        props: {
            state: { type: String, required: true },
            unit: { type: String, default: 'ha' },
        },
        data () {
            const end = DateTime.now().year
            const start = end - 5

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
                return this.data.map(datum => Number(datum.areakm))
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
                    scales: {
                        y: {
                            beginAtZero: true,
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

                const data = await api.get(`prodes/${filters}ano1=${start}&ano2=${end}&group_by=ano`)
                this.data = data
            },
        },
    }
</script>
