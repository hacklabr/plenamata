<template>
    <DashboardPanel type="chart">
        <template #title>
            <strong>{{ __('Yearly deforestation alerts', 'plenamata') }}</strong>
            <span>{{ __('in the selected territory', 'plenamata') }} (DETER)</span>
        </template>
        <template #filters>
            <Dropdown 
                id="unit-yded" 
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
            {{__( 'Source', 'plenamata' )}}: DETER/INPE. {{__( 'Latest Update', 'plenamata' )}}: {{updated.sync}} {{__( 'with alerts detected until', 'plenamata' )}} {{updated.deter}}.
            {{__( 'The figures represent deforestation for each year up to', 'plenamata' )}} {{previousMonth}}.
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'
    import { Bar } from 'vue-chartjs'

    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
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
        name: 'YearlyDeforestationEvolutionDeter',
        components: {
            Bar,
            DashboardPanel,
            Dropdown
        },
        props: {
            date: { type: DateTime, required: true },
            filters: { type: Object, required: true },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
            activeField: { type: [ String, Object ], default: '' },
        },
        data () {
            return {
                data: [],
                actualYear: DateTime.now().year,
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
                    const datum = this.data.find((datum) => datum[0]?.year === year)
                    return datum ? getAreaKm2(datum[0]) : 0
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
            intervals () {
                const start = this.date.startOf('year')
                const end = this.date

                const intervals = [[start, end]]
                for (let i = 1; i < 5; i++) {
                    intervals.unshift([start.minus({ years: i }), end.minus({ years: i }).endOf('year')])
                }
                return intervals
            },
            previousMonth () {
                const month = this.date.month
                return months[month]
            },
            unitModel: vModel('unit'),
            years () {
                return this.intervals.map(([start]) => start.year)
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
                const data = await Promise.all(this.intervals.map(([start, end]) => {
                    return fetchDeterData({ ...this.filters, data1: start.toISODate(), data2: end.toISODate(), group_by: 'ano' })
                }))
                this.data = data
            },
        },
    }
</script>
