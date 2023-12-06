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
        filters: { required: false }
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
        'filters': {
            handler() {
                this.$nextTick(() => {
                    this.fetchData();
                });
            },
            immediate: true,
            deep: true
        },
    },
    methods: {
        async fetchData() {
            let baseDate = this.date
            let start, end;

            if (this.startDateEndDate && typeof this.startDateEndDate == 'object') {
                start = DateTime.fromISO(this.startDateEndDate[0]);
                end = DateTime.fromISO(this.startDateEndDate[1]);
            } else {
                if (!baseDate || !baseDate.isValid) {
                    return;
                }
                start = baseDate.startOf('year')
                end = baseDate

                if (baseDate.month < 2) {
                    const previousYear = baseDate.minus({ years: 1 })
                    start = previousYear.startOf('year')
                    end = previousYear.endOf('year')
                }
            }

            let requestParams = { data1: start.toISODate(), data2: end.toISODate(), group_by: 'semana' };
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

            const data = await fetchDeterData( requestParams );

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
