<template>
    <Bar :chartData="chartData" :chartOptions="chartOptions" :height="200"/>
</template>

<script>
    import { Bar } from 'vue-chartjs'

    import { __, sprintf } from '../../dashboard/plugins/i18n'
    import { getAreaKm2 } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { roundNumber } from '../../utils/filters'

    const { DateTime } = window.luxon

    export default {
        name: 'YearlyDeforestationChart',
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
                return this.data.map(datum => getAreaKm2(datum[0]))
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
            years () {
                return this.data.map(datum => String(datum[0].year))
            },
        },
        async created () {

            const baseEnd = this.date
            const baseStart = baseEnd.startOf('year')

            const intervals = [[baseStart, baseEnd]]
            for (let i = 1; i < 5; i++) {
                intervals.unshift([baseStart.minus({ years: i }), baseEnd.minus({ years: i }).endOf('year')])
            }

            const data = await Promise.all(intervals.map(([start, end]) => {
                return fetchDeterData({ data1: start.toISODate(), data2: end.toISODate(), group_by: 'ano' })
            }))
            
            this.data = data

        
        },
    
    }
</script>
