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
            </keep-alive>
        </div>
    </div>
</template>

<script>
    import MonthlyDeforestationChart from './MonthlyDeforestationChart.vue'
    import YearlyDeforestationChart from './YearlyDeforestationChart.vue'

    export default {
        name: 'DeforestationCharts',
        components: {
            MonthlyDeforestationChart,
            YearlyDeforestationChart,
        },
        data () {
            return {
                view: 'year',
            }
        },
    }
</script>
