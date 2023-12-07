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
    name: 'YearlyDeforestationChart',
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
            immediate: true,
        },
    },
    methods: {
        async fetchData() {
            let baseDate = this.date
            let start, end;

            if (this.startDateEndDate && typeof this.startDateEndDate == 'object') {
                end = DateTime.fromISO(this.startDateEndDate[1]);
                start = end.startOf('year');
            } else {
                start = baseDate.startOf('year')
                end = baseDate
            }

            const intervals = [[start, end]]
            for (let i = 1; i < 5; i++) {
                intervals.unshift([start.minus({ years: i }), end.minus({ years: i }).endOf('year')])
            }

            let requestParams = {};
            if( this.filters && typeof this.filters == 'object' ) {
                if( this.filters.uc ) {
                    requestParams.uc = this.filters.uc
                } else if (this.filters.ti ) {
                    requestParams.ti = this.filters.ti
                } else if (this.filters.municipio ) {
                    requestParams.municipio = this.filters.municipio
                } else if( this.filters.estado ) {
                    requestParams.estado = this.filters.estado
                }
            }

            const data = await Promise.all(intervals.map(([start, end]) => {
                return fetchDeterData({ data1: start.toISODate(), data2: end.toISODate(), group_by: 'ano', ...requestParams })
            }))

            this.data = data
        }
    },
    computed: {
        areas() {
            return this.data.map(datum => getAreaKm2(datum[0]))
        },
        chartData() {
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
        chartOptions() {
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
        years() {
            const validYears = [];
            for (const datum of this.data) {
                if (datum[0] && datum[0].year !== undefined) {
                    validYears.push(String(datum[0].year));
                }
            }
            return validYears;
        },
    },
    async created() {
        this.fetchData();
    },
}
</script>
