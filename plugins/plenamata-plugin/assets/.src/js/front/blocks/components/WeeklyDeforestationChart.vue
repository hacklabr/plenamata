<template>
    <BarChart :chartData="chartData" :height="100" :options="chartOptions"/>
</template>

<script>
    import { BarChart } from 'vue-chart-3'

    import { __, sprintf } from '../../dashboard/plugins/i18n'
    import api from '../../utils/api'
    import { roundNumber } from '../../utils/filters'

    const { DateTime } = window.luxon

    export default {
        name: 'WeeklyDeforestationChart',
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
                    labels: this.weeks,
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
                        tooltip: {
                            callbacks: {
                                label: ({ raw }) => sprintf(__('%s km²', 'plenamata'), roundNumber(raw)),
                                title: ([{ label }]) => sprintf(__('Week %s', 'plenamata'), label),
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
            weeks () {
                return this.data.map(datum => String(datum.week))
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

            const data = await api.get(`deter/basica?data1=${start.toISODate()}&data2=${end.toISODate()}&group_by=semana`)
            this.data = data
        },
    }
</script>
