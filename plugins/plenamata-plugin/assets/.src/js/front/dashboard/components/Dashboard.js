import { DateTime, Interval } from 'luxon'
import Vue from 'vue'

import api from '../../utils/api'
import { humanNumber, roundNumber } from '../../utils/filters'

window.api = api

const states = {
    AC: { uf: 'AC', name: 'Acre' },
    AM: { uf: 'AM', name: 'Amazonas' },
    AP: { uf: 'AP', name: 'Amapá' },
    MA: { uf: 'MA', name: 'Maranhão' },
    MT: { uf: 'MT', name: 'Mato Grosso' },
    PA: { uf: 'PA', name: 'Pará' },
    RO: { uf: 'RO', name: 'Rondônia' },
    RR: { uf: 'RR', name: 'Roraima' },
}

export default Vue.extend({
    name: 'Dashboard',
    filters: {
        humanNumber,
        roundNumber,
    },
    data () {
        const now = DateTime.now()
        const startOfYear = now.startOf('year')

        return {
            data: {
                deterStates: [],
            },
            date: {
                now,
                startOfYear,
                year: now.year,
            },
            state: '',
            view: 'data',
        }
    },
    computed: {
        states () {
            return states
        },
        minutes () {
            return Interval.fromDateTimes(this.date.startOfYear, this.date.now).length('minutes')
        },
        trees () {
            if (this.state) {
                const state = this.data.deterStates.find((state) => state.uf === this.state)
                return state.num_arvores || 0
            } else {
                return this.data.deterStates.reduce((acc, state) => acc + (state.num_arvores || 0), 0)
            }
        },
        treesPerMinute () {
            return this.trees / this.minutes
        }
    },
    created () {
        const { now, startOfYear } = this.date

        api.get(`deter/estados?data1=${startOfYear.toISODate()}&data2=${now.toISODate()}`).then((data) => {
            this.data.deterStates = data
        })
    },
})
