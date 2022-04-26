import { Chart, BarController, BarElement, CategoryScale, Legend, LinearScale, Tooltip } from 'chart.js'
import ZoomPlugin from 'chartjs-plugin-zoom'
import FloatingVue from 'floating-vue'
import Vue from 'vue'

import DashboardApp from './components/Dashboard.vue'
import Globals from'./plugins/globals'
import I18n from './plugins/i18n'

export class Dashboard {

    constructor() {
        Chart.register(BarController, BarElement, CategoryScale, Legend, LinearScale, Tooltip, ZoomPlugin)

        Vue.use(FloatingVue)
        Vue.use(Globals)
        Vue.use(I18n)

        document.querySelectorAll('.vue-dashboard-app').forEach((el) => {
            new Vue({
                el,
                render: (h) => h(DashboardApp),
            })
        })
    }
}

document.defaultView.document.addEventListener('DOMContentLoaded', () => {
	new Dashboard()
})
