<template>
    <div class="deforestation-charts">
        <fieldset class="deforestation-charts__toggle">
            <label :class="{ active: view === 'year' }">
                <input type="radio" name="charts-view" value="year" v-model="view">
                <span>{{ __('Yearly', 'plenamata') }}</span>
            </label>
            <label :class="{ active: view === 'month' }">
                <input type="radio" name="charts-view" value="month" v-model="view">
                <span>{{ __('Monthly', 'plenamata') }}</span>
            </label>
            <label :class="{ active: view === 'week' }">
                <input type="radio" name="charts-view" value="week" v-model="view">
                <span>{{ __('Weekly', 'plenamata') }}</span>
            </label>
        </fieldset>
        <div class="deforestation-charts__chart">
            <keep-alive>
                <YearlyDeforestationChart key="year" v-if="view === 'year'"/>
                <MonthlyDeforestationChart key="month" v-if="view === 'month'"/>
                <WeeklyDeforestationChart key="week" v-if="view === 'week'"/>
            </keep-alive>
        </div>
        <p class="deforestation-charts__source">
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.sync, updated.deter) }}
            {{ sprintf(__('The figures represent deforestation for each year up to %s.', 'plenamata'), previousMonth) }}
            {{ sprintf(__('Weekly and monthly data are from %s.', 'plenamata'), year) }}
        </p>
    </div>
</template>

<script>
    import MonthlyDeforestationChart from './MonthlyDeforestationChart.vue'
    import WeeklyDeforestationChart from './WeeklyDeforestationChart.vue'
    import YearlyDeforestationChart from './YearlyDeforestationChart.vue'
    import { _x } from '../../dashboard/plugins/i18n'
    import api from '../../utils/api'
    import { shortDate } from '../../utils/filters'

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
        name: 'DeforestationCharts',
        components: {
            MonthlyDeforestationChart,
            WeeklyDeforestationChart,
            YearlyDeforestationChart,
        },
        data () {
            return {
                lastUpdate: {},
                view: 'year',
            }
        },
        computed: {
            previousMonth () {
                const month = DateTime.fromISO(this.lastUpdate.deter_last_date).month
                return months[month]
            },
            updated () {
                const today = DateTime.now().toISODate()

                const deterDate = DateTime.fromISO(this.lastUpdate.deter_last_date || today)
                const syncDate = DateTime.fromISO(this.lastUpdate.last_sync || today)

                return {
                    deter: shortDate(deterDate.toJSDate()).replaceAll('/', '.'),
                    sync: shortDate(syncDate.toJSDate()).replaceAll('/', '.'),
                }
            },
            year () {
                return this.lastUpdate.deter_last_date?.slice(0, 4)
            },
        },
        async created () {
            const lastUpdate =  await api.get('deter/last_date')
            this.lastUpdate = lastUpdate
        },
    }
</script>
