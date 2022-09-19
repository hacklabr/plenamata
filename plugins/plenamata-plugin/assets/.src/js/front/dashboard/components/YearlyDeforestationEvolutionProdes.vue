<template>
    <DashboardPanel type="chart">
        <template #title>
            <strong>{{ __('Yearly consolidated deforestation rate', 'plenamata') }}</strong>
            <span>{{ __('in the selected territory', 'plenamata') }} (PRODES)</span>
        </template>
        <template #filters>
            <Dropdown 
                id="unit-ydep" 
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
        </template>
        <template #chart>
            <Bar :chartData="chartData" :chartOptions="chartOptions" :height="300"/>
        </template>
        <template #source>
            {{__( 'Source', 'plenamata' )}} PRODES/INPE. {{ __('Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.', 'plenamata') }}<br>
        </template>
    </DashboardPanel>
</template>

<script>
    import { Bar } from 'vue-chartjs'

    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
    import { __, sprintf } from '../plugins/i18n'
    import { getAreaKm2 } from '../../utils'
    import { fetchProdesData } from '../../utils/api'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'YearlyDeforestationEvolutionProdes',
        components: {
            Bar,
            DashboardPanel,
            Dropdown
        },
        props: {
            filters: { type: Object, required: true },
            unit: { type: String, default: 'ha' },
            year: { type: Number, required: true },
            activeField: { type: [ String, Object ], default: '' },
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
                fieldActive : { type: String, defaul: '' },
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
                            backgroundColor: '#263F30',
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
            fieldActive: {
                handler( active ){
                    this.$emit( 'update:activeField', active );
                }
            },
            activeField: {
                handler( active ){
                    this.fieldActive = active;
                }
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
