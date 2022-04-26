import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip } from 'chart.js'
import FloatingVue from 'floating-vue'
import Vue from 'vue'

import DeforestationChartsApp from './components/DeforestationCharts.vue'
import EstimateTooltip from './components/EstimateTooltip.vue'
import Globals from '../dashboard/plugins/globals'
import I18n from '../dashboard/plugins/i18n'

export class DeforestationCharts {

    constructor() {
        Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip)

        Vue.use(FloatingVue)
        Vue.use(Globals)
        Vue.use(I18n)

        document.querySelectorAll('.vue-deforestation-charts').forEach((el) => {
            new Vue({
                el,
                render: (h) => h(DeforestationChartsApp),
            })
        })
        document.querySelectorAll('.vue-estimate-tooltip').forEach((el) => {
            new Vue({
                el,
                render: (h) => h(EstimateTooltip),
            })
        })
    }
}

document.defaultView.document.addEventListener('DOMContentLoaded', () => {
	new DeforestationCharts()
})
