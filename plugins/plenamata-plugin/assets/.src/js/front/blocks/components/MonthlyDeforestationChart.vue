<template>
    <BarChart :chartData="chartData" :height="150" :options="chartOptions"/>
</template>

<script>
    import { BarChart } from 'vue-chart-3'

    import api from '../../utils/api'
    import { _x } from '../../dashboard/plugins/i18n'

    const { DateTime } = window.luxon
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
        name: 'MonthlyDeforestationChart',
        components: {
            BarChart,
        },
        data () {
            return {
                data: [],
            }
        },
        computed: {
            areas () {
                return this.data.map(datum => Number(datum.areamunkm))
            },
            chartData () {
                return {
                    labels: this.months,
                    datasets: [
                        {
                            data: this.areas,
                            backgroundColor: '#FF7373',
                        },
                    ],
                }
            },
            chartOptions () {
                return {}
            },
            months () {
                return this.data.map(datum => months[datum.month])
            },
        },
        async created () {
            let baseDate = DateTime.now()
            let start = baseDate.startOf('year')
            let end = baseDate

            if (baseDate.month < 2) {
                const previousYear = baseDate.minus({ years: 1 })
                start = previousYear.startOf('year')
                end = previousYear.endOf('year')
            }

            const data = await api.get(`deter/basica?data1=${start.toISODate()}&data2=${end.toISODate()}&group_by=mes`)
            this.data = data
        },
    }
</script>
