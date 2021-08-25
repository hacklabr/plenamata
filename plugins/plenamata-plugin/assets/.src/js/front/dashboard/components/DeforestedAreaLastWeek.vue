<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ __('Área desmatada na última semana', 'plenamata') }}
        </template>
        <template #measure>
            <DashboardMeasure icon="area-icon.svg" :number="area">
                <template #unit>
                    <select v-model="unitModel">
                        <option value="ha">{{ __('hectares', 'plenamata') }}</option>
                        <option value="km2">{{ __('km²', 'plenamata') }}</option>
                    </select>
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimativa média de %s árvores por minuto', 'plenamata'), roundNumber(trees / 10080)) }}
        </template>
        <template #footer>
            {{ __('Fonte: INPE/DETER', 'plenamata') }} • Última atualização: 19.07.2021 com alertas detectados até 09.07.2021
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import api from '../../utils/api'
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'TotalDeforestationThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            now: { type: DateTime, required: true },
            state: { type: String, required: true },
            unit: { type: String, default: 'ha' },
        },
        data () {
            return {
                lastWeek: null,
            }
        },
        computed: {
            area () {
                if (this.unit === 'ha') {
                    return this.areaKm2 * 100
                } else {
                    return this.areaKm2
                }
            },
            areaKm2 () {
                if (!this.lastWeek) {
                    return 0
                }
                if (this.state) {
                    const state = this.lastWeek.find(state => state.uf === this.state)
                    return Number(state.areamunkm)
                } else {
                    return this.lastWeek.reduce((acc, state) => acc + Number(state.areamunkm), 0)
                }
            },
            trees () {
                if (!this.lastWeek) {
                    return 0
                }
                if (this.state) {
                    const state = this.lastWeek.find(state => state.uf === this.state)
                    return Number(state.num_arvores)
                } else {
                    return this.lastWeek.reduce((acc, state) => acc + Number(state.num_arvores), 0)
                }
            },
            unitModel: {
                get () {
                    return this.unit
                },
                set (value) {
                    this.$emit('unit', value)
                }
            },
        },
        async created () {
            const previousWeek = this.now.minus({ weeks: 1 })
            const startOfWeek = previousWeek.startOf('week')
            const endOfWeek = previousWeek.endOf('week')

            const data = await api.get(`deter/estados?data1=${startOfWeek.toISODate()}&data2=${endOfWeek.toISODate()}`)
            this.lastWeek = data
        },
        methods: {
            roundNumber,
        },
    }
</script>
