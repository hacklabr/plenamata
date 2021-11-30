<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Yearly consolidated deforestation rate in the selected territory (PRODES)', 'plenamata') }}
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
            {{ __('Source: PRODES/INPE.', 'plenamata') }}
            {{ __('Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.', 'plenamata') }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { BarChart } from 'vue-chart-3'

    import DashboardPanel from './DashboardPanel.vue'
    import { __, sprintf } from '../plugins/i18n'
    import { getAreaKm2 } from '../../utils'
    import { fetchProdesData } from '../../utils/api'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'YearlyDeforestationEvolutionProdes',
        components: {
            BarChart,
            DashboardPanel,
        },
        props: {
            filters: { type: Object, required: true },
            unit: { type: String, default: 'ha' },
            year: { type: Number, required: true },
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
                    const datum = this.data.find((datum) => datum.year === year)
                    return getAreaKm2(datum)
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
            unitModel: vModel('unit'),
            years () {
                if (this.data.length === 0) {
                    return []
                }

                const first = this.data.reduce((acc, curr) => {
                    return (!acc || curr.year < acc) ? curr.year : acc
                }, null)
                const last = this.data.reduce((acc, curr) => {
                    return (!acc || curr.year > acc) ? curr.year : acc
                }, null)

                const years = []
                for (let year = first; year <= last; year++) {
                    years.push(year)
                }
                return years
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
                const data = await fetchProdesData({ ...this.filters, ano1: 2000, ano2: this.year, group_by: 'ano' })
                this.data = data
            },
        },
    }
</script>
