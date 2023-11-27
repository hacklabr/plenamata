<template>
    <Bar :chartData="chartData" :chartOptions="chartOptions" :height="200" />
</template>

<script>
import { DateTime } from 'luxon'
import { Bar } from 'vue-chartjs'

import { __, sprintf } from '../../dashboard/plugins/i18n'
import { getAreaKm2 } from '../../utils'
import { fetchDeterData } from '../../utils/api'
import { roundNumber } from '../../utils/filters'

export default {
    name: 'WeeklyDeforestationChart',
    components: {
        Bar,
    },
    props: {
        date: { type: DateTime, required: true },
        startDateEndDate: { required: false },
    },
    data() {
        return {
            data: [],
        }
    },
    watch: {
        'startDateEndDate': {
            handler() {
                this.$nextTick(() => {
                    this.fetchData();
                });
            },
            immediate: true, // para garantir que a função seja chamada imediatamente na criação
        },
    },
    methods: {
        async fetchData() {
            let baseDate = this.date
            let start, end;

            if (this.startDateEndDate && typeof this.startDateEndDate == 'object' ) {
                start = DateTime.fromISO(this.startDateEndDate[0]);
                end = DateTime.fromISO(this.startDateEndDate[1]);
            } else {
                start = baseDate.startOf('year')
                end = baseDate

                if (baseDate.month < 2) {
                    const previousYear = baseDate.minus({ years: 1 })
                    start = previousYear.startOf('year')
                    end = previousYear.endOf('year')
                }
            }


            const data = await fetchDeterData({ data1: start.toISODate(), data2: end.toISODate(), group_by: 'semana' })
            this.data = data
        }
    },
    computed: {
        areas() {
            return this.data.map(getAreaKm2)
        },
        chartData() {
            return {
                labels: this.weeks,
                datasets: [
                    {
                        data: this.areas,
                        backgroundColor: '#263F30',
                    },
                ],
            }
        },
        chartOptions() {
            return {
                plugins: {
                    legend: {
                        display: false,
                    },
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
        weeks() {
            return this.data.map(datum => String(datum.week))
        },
    },
    async created() {
        this.fetchData();
    },
}
</script>
