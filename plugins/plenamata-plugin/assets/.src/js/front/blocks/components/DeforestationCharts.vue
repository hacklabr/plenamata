<template>
    <div class="dashboard-panel chart deforestation-charts">
        <main>
            <header>
                <h2>
                    <strong>{{ __('Evolution per period', 'plenamata') }}</strong>
                    <span>{{ __('(deforested square kilometers)', 'plenamata') }}</span>
                </h2>
                <fieldset class="deforestation-charts__toggle">
                    <label :class="{ active: view === 'week' }">
                        <input type="radio" name="charts-view" value="week" v-model="view">
                        <span>{{ __('Weekly', 'plenamata') }}</span>
                    </label>
                    <label :class="{ active: view === 'month' }">
                        <input type="radio" name="charts-view" value="month" v-model="view">
                        <span>{{ __('Monthly', 'plenamata') }}</span>
                    </label>
                    <label :class="{ active: view === 'year' }">
                        <input type="radio" name="charts-view" value="year" v-model="view">
                        <span>{{ __('Yearly', 'plenamata') }}</span>
                    </label>
                </fieldset>
            </header>
            <template v-if="lastUpdate">
                <div class="deforestation-charts__chart">
                    <keep-alive>
                        <WeeklyDeforestationChart key="week" :date="date" :startDateEndDate="startDateEndDate"
                            v-if="view === 'week'" />
                        <MonthlyDeforestationChart key="month" :date="date" :startDateEndDate="startDateEndDate"
                            v-if="view === 'month'" />
                        <YearlyDeforestationChart key="year" :date="date" :startDateEndDate="startDateEndDate"
                            v-if="view === 'year'" />
                    </keep-alive>
                </div>
                <footer>
                    <span class="source">
                        {{ __('Source', 'plenamata') }}: DETER/INPE • {{ __('Latest Update', 'plenamata') }}: {{
                            updated.sync }} {{ __('with alerts detected until', 'plenamata') }} {{ updated.deter }}.
                        {{ __('The figures represent deforestation for each year up to', 'plenamata') }} {{ previousMonth
                        }}.
                        {{ sprintf(__('Weekly and monthly data are from %s.', 'plenamata'), year) }}
                    </span>
                </footer>
            </template>
        </main>
    </div>
</template>

<script>
import { DateTime } from 'luxon'

import MonthlyDeforestationChart from './MonthlyDeforestationChart.vue'
import WeeklyDeforestationChart from './WeeklyDeforestationChart.vue'
import YearlyDeforestationChart from './YearlyDeforestationChart.vue'
import { _x } from '../../dashboard/plugins/i18n'
import { fetchLastDate } from '../../utils/api'
import { shortDate } from '../../utils/filters'

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
    name: 'DeforestationCharts',
    components: {
        MonthlyDeforestationChart,
        WeeklyDeforestationChart,
        YearlyDeforestationChart,
    },
    props: {
        filteredYear: {
            required: false
        }
    },
    data() {
        return {
            lastUpdate: {},
            view: 'week',
        }
    },
    methods: {
        updateDate() {
            if (this.filteredYear) {
                this.startDateEndDate = [
                    `${this.filteredYear}-01-01`,
                    `${this.filteredYear}-12-31`
                ]
            } else {
                this.startDateEndDate = false
            }

            if (this.view === 'week') {
                this.view = '';
                this.view = 'week';
            }
            if (this.view === 'month') {
                this.view = '';
                this.view = 'month';
            }

            if (this.view === 'year') {
                this.view = '';
                this.view = 'year';
            }

        }
    },
    watch: {
        'filteredYear': {
            handler() {
                this.updateDate();
            },
            immediate: true,
        },
    },
    computed: {
        date() {
            return DateTime.fromISO(this.lastUpdate.deter_last_date)
        },
        previousMonth() {
            const month = this.date.month
            return months[month]
        },
        updated() {
            return {
                deter: shortDate(this.lastUpdate?.deter_last_date).replaceAll('/', '.'),
                sync: shortDate(this.lastUpdate?.last_sync).replaceAll('/', '.'),
            }
        },
        year() {
            return this.date.year
        },
    },
    async created() {
        const lastUpdate = await fetchLastDate()
        this.lastUpdate = lastUpdate

        this.updateDate();
    },
}
</script>
