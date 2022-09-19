<template>
    <DashboardPanel type="chart">
        <template #title>
            <strong>{{ __('Weekly deforestation rate', 'plenamata') }}</strong>
            <span>{{ __('in the selected territory', 'plenamata') }}</span>
        </template>
        <template #filters>
            <Dropdown 
                id="unit-wdev" 
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
                id="source-wdev" 
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
            {{__('Source', 'planamata')}}: DETER/INPE. {{__( 'Latest Update', 'plenamata' )}}: {{updated.sync}} {{__('with alerts detected until', 'plenamata')}} {{updated.deter}}.
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
    import { __, sprintf } from '../plugins/i18n'
    import { getAreaKm2 } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'WeeklyDeforestationEvolution',
        components: {
            DashboardPanel,
            Dropdown,
            Bar,
            ScrollGuard
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
            year: { type: [ Number, String ], required: true },
            opened : { type : [ Boolean, String ], default: '' },
            activeField: { type: [ String, Object ], default: '' },
        },
        data () {
            return {
                data: [],
                internalYear: DateTime.now().year,
                actualYear: DateTime.now().year,
                fieldActive : { type: String, defaul: '' },
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
                }
            }
        },
        computed: {
            areasKm2 () {
                const { start, end } = this.dateInterval
                const startYear = start.year
                const endYear = end.year

                const sortedData = []

                if (this.source === 'deter') {
                    for (let i = 1; i <= end.weekNumber; i++) {
                        sortedData.push(this.findAreaKm2(i, startYear))
                    }
                } else {
                    if (startYear === endYear) {
                        for (let i = start.weekNumber + 1; i <= end.weekNumber; i++) {
                            sortedData.push(this.findAreaKm2(i, startYear))
                        }
                    } else {
                        for (let i = start.weekNumber + 1; i <= start.weeksInWeekYear; i++) {
                            sortedData.push(this.findAreaKm2(i, startYear))
                        }
                        for (let i = 1; i <= end.weekNumber; i++) {
                            sortedData.push(this.findAreaKm2(i, endYear))
                        }
                    }
                }

                return sortedData
            },
            areas () {
                if (this.unit === 'ha') {
                    return this.areasKm2.map((areaKm2) => areaKm2 * 100)
                } else {
                    return this.areasKm2
                }
            },
            chartData () {
                return {
                    labels: this.areasKm2.map((item, i) => String(i + 1)),
                    datasets: [
                        {
                            data: this.areas,
                            backgroundColor: '#263F30',
                        },
                    ],
                }
            },
            chartOptions () {
               const weeks = this.areasKm2.length

                return {
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: ({ raw }) => sprintf(this.unit === 'ha' ? __('%s ha', 'plenamata') : __('%s km²', 'plenamata'), roundNumber(raw)),
                                title: ([{ label }]) => sprintf(__('Week %s', 'plenamata'), label),
                            },
                        },
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'x',
                                threashold: 1,
                                onPan: this.onChartPan,
                            },
                        },
                    },
                    scales: {
                        x: {
                            type: 'category',
                            min: Math.max(0, weeks - 15),
                            max: weeks - 1,
                        },
                        y: {
                            type: 'linear',
                            min: 0,
                            suggestedMax: Math.max(...this.areas),
                            ticks: {
                                callback: (value) => roundNumber(value),
                            },
                        },
                    },
                }
            },
            dateInterval (){
            
                const year = this.getYear();
            
                if( this.source === 'deter' ){
                    const start = DateTime.fromObject({ day: 1, month: 1, year: year })
                    const end = DateTime.min(DateTime.fromObject({ day: 31, month: 12, year: year }), this.date)
                    return { start, end }
                } 
                else {
                    const start = DateTime.fromObject({ day: 1, month: 8, year: year })
                    const end = DateTime.min(DateTime.fromObject({ day: 31, month: 7, year: year + 1 }), this.date)
                    return { start, end }
                }
            
            },
            filterKey () {
                return JSON.stringify({ ...this.filters, source: this.source, year: this.getYear() })
            },
            maxYear () {
                if (this.source === 'deter' || this.date.month >= 8) {
                    return this.date.year
                } else {
                    return this.date.year - 1
                }
            },
            range () {
                const years = []
                for (let year = 2016; year <= this.maxYear; year++) {
                    years.push(year)
                }
                return years
            },
            sourceModel: vModel('source'),
            unitModel: vModel('unit')
        },
        watch: {
            filterKey: {
                handler: 'fetchData',
                immediate: true,
            },
            year: {
                handler( year ){
                    this.internalYear = ( year === '' ? this.actualYear : year );
                },
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
            },
        },
        methods: {
            async fetchData () {
                const { start, end } = this.dateInterval

                const data = await fetchDeterData({ ...this.filters, data1: start.toISODate(), data2: end.toISODate(), group_by: 'semana' })
                this.data = data
            },
            findAreaKm2( week, year ){
                const found = this.data.find((datum) => {
                    return datum.week === week && datum.year === year
                })
                return found ? getAreaKm2(found) : 0
            },
            getYear(){
                return ( this.internalYear === '' ) ? this.actualYear : this.internalYear;
            }
        },
    }
</script>
