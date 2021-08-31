<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Evolução semanal do desmatamento', 'plenamata') }}
        </template>
        <template #filters>
            <select :aria-label="__('Period', 'plenamata')" v-model="yearModel">
                <option v-for="year of range" :key="year" :value="year">{{ sprintf(__('Período: %s', 'plenamata'), year) }}</option>
            </select>
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
            {{ __('Os dados semanais são de 2021.', 'plenamata') }}
        </template>
    </DashboardPanel>
</template>

<script>
    import Color from 'color'
    import { DateTime } from 'luxon'
    import { BarChart } from 'vue-chart-3'

    import DashboardPanel from './DashboardPanel.vue'
    import api from '../../utils/api'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'WeeklyDeforestationEvolution',
        components: {
            BarChart,
            DashboardPanel,
        },
        props: {
            now: { type: DateTime, required: true },
            source: { type: String, default: 'prodes' },
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
            areasKm2 () {
                const sortedData = this.data.slice().sort((a, b) => {
                    if (a.year === b.year) {
                        return (a.week > b.week) ? 1 : -1
                    } else {
                        return (a.year > b.year) ? 1 : -1
                    }
                })

                return sortedData.map((datum) => Number(datum.areamunkm))
            },
            chartData () {
                const factor = this.unit === 'ha' ? 100 : 1

                return {
                    labels: this.areasKm2.map((item, i) => i + 1),
                    datasets: [
                        {
                            data: this.areasKm2.map((areaKm2) => areaKm2 * factor),
                            backgroundColor: this.colors,
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
            colors () {
                const color = Color('#FF7373')
                return [color.alpha(0.5).string(), '#FF7373']
            },
            dateInterval () {
                if (this.source === 'deter') {
                    const start = DateTime.fromObject({ day: 1, month: 1, year: this.year })
                    const end = DateTime.fromObject({ day: 31, month: 12, year: this.year })
                    return { start, end }
                } else {
                    const start = DateTime.fromObject({ day: 1, month: 8, year: this.yearModel })
                    const end = DateTime.fromObject({ day: 31, month: 7, year: this.yearModel + 1 })
                    return { start, end }
                }
            },
            filterKey () {
                return `${this.state}|${this.year}|${this.source}`
            },
            maxYear () {
                if (this.source === 'deter' || this.now.month >= 8) {
                    return this.now.year
                } else {
                    return this.now.year - 1
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
            unitModel: vModel('unit'),
            yearModel: {
                get () {
                    return this.year > this.maxYear ? this.maxYear : this.year
                },
                set (value) {
                    this.$emit('update:year', value)
                }
            },
        },
        watch: {
            filterKey: {
                handler: 'fetchData',
                immediate: true,
            },
        },
        methods: {
            async fetchData () {
                const { start, end } = this.dateInterval

                const filters = this.state ? `estados?estado=${this.state}&` : 'basica?'

                const data = await api.get(`deter/${filters}data1=${start.toISODate()}&data2=${end.toISODate()}&group_by=semana`)
                this.data = data
            },
        },
    }
</script>
