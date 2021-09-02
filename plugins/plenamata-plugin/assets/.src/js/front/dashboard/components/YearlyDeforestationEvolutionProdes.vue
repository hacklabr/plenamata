<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Yearly consolidated deforestation rate (PRODES)', 'plenamata') }}
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
        </template>
    </DashboardPanel>
</template>

<script>
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
                    plugins: {
                        legend: {
                            display: false,
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
                            min: this.areas.length - 5,
                            max: this.areas.length,
                        },
                        y: {
                            min: 0,
                            suggestedMax: Math.max(...this.areas),
                        },
                    }
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
                const filters = this.state ? `estados?estado=${this.state}&` : 'basica?'

                const data = await api.get(`prodes/${filters}ano1=2008&ano2=${this.year}&group_by=ano`)
                this.data = data
            },
        },
    }
</script>
