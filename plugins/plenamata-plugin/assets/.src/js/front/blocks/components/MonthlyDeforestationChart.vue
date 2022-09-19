<template>
    <Bar :chartData="chartData" :chartOptions="chartOptions" :height="200"/>
</template>

<script>
    import { Bar } from 'vue-chartjs'

    import { __, _x, sprintf } from '../../dashboard/plugins/i18n'
    import { getAreaKm2 } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { roundNumber } from '../../utils/filters'

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
            Bar,
        },
        props: {
            date: { type: DateTime, required: true },
        },
        data () {
            return {
                data: [],
            }
        },
        computed: {
            areas () {
                return this.data.map(getAreaKm2)
            },
            chartData () {
                return {
                    labels: this.months,
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
                                label: ({ raw }) => sprintf(__('%s km²', 'plenamata'), roundNumber(raw)),
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
            months () {
                return this.data.map(datum => months[datum.month].slice(0, 3))
            },
        },
        async created () {
            let baseDate = this.date
            let start = baseDate.startOf('year')
            let end = baseDate

            if (baseDate.month < 2) {
                const previousYear = baseDate.minus({ years: 1 })
                start = previousYear.startOf('year')
                end = previousYear.endOf('year')
            }

            const data = await fetchDeterData({ data1: start.toISODate(), data2: end.toISODate(), group_by: 'mes' })
            this.data = data
        },
    }
</script>
