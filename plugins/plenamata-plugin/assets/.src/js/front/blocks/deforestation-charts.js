import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip } from 'chart.js'
import Vue from 'vue'

import DeforestationChartsApp from './components/DeforestationCharts.vue'
import I18n from '../dashboard/plugins/i18n'

export class DeforestationCharts {

    constructor() {
        Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip)

        Vue.use(I18n)

        document.querySelectorAll('.vue-deforestation-charts').forEach((el) => {
            console.log(el)
            new Vue({
                el,
                render: (h) => h(DeforestationChartsApp),
            })
        })
    }
}

document.defaultView.document.addEventListener('DOMContentLoaded', () => {
	new DeforestationCharts()
})
