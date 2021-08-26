import Vue from 'vue'

import DashboardApp from './components/Dashboard.vue'
import I18n from './plugins/i18n'

export class Dashboard {

    constructor() {
        Vue.use(I18n)

        Vue.component('plenamata-dashboard', DashboardApp)

        document.querySelectorAll('.vue-dashboard-app').forEach((el) => {
            new Vue({ el })
        })
    }
}

document.defaultView.document.addEventListener('DOMContentLoaded', () => {
	new Dashboard()
})
