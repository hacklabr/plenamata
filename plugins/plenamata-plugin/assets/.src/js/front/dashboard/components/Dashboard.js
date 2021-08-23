import Vue from 'vue'

import api from '../../utils/api'

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
    data () {
        return {
            state: '',
            view: 'data',
        }
    },
    computed: {
        states () {
            return states
        },
    },
    created () {
        api.get('deter/estados?data1=2020-10-02&data2=2020-10-30').then((data) => {
            console.log(data)
        })
    },
})
