import Vue from 'vue'

import DashboardApp from './components/Dashboard'

export class Dashboard {

    constructor() {
        Vue.component('plenamata-dashboard', DashboardApp)

        document.querySelectorAll('.vue-app').forEach((el) => {
            new Vue({ el })
        })
    }
}

document.defaultView.document.addEventListener('DOMContentLoaded', () => {
	new Dashboard()
})
