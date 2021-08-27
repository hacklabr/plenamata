<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Total de desmatamento em %s no território selecionado', 'plenamata'), year) }}
        </template>
        <template #measure>
            <p>
                {{ sprintf(__('Área total de desmatamento em %s (com base na semana anterior)', 'plenamata'), year) }}
                <span v-if="state">{{ __('no Estado', 'plenamata') }}</span>
            </p>
            <DashboardMeasure :number="area">
                <template #unit>
                    <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                        <option value="ha">{{ __('hectares', 'plenamata') }}</option>
                        <option value="km2">{{ __('km²', 'plenamata') }}</option>
                    </select>
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            <template v-if="increase > 0">
                {{ sprintf(__('aumento de %s%% em relação ao ano passado', 'plenamata'), roundNumber(increase)) }}
            </template>
            <template v-else>
                {{ sprintf(__('diminuição de %s%% em relação ao ano passado', 'plenamata'), roundNumber(-increase)) }}
            </template>
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
    import { vModel } from '../../utils/vue'

    export default {
        name: 'TotalDeforestationThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            areaKm2: { type: Number, required: true },
            now: { type: DateTime, required: true },
            state: { type: String, default: '' },
            unit: { type: String, default: 'ha' },
            year: { type: Number, required: true },
        },
        data () {
            return {
                lastYear: null,
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
            increase () {
                if (this.areaKm2 && this.previousAreaKm2) {
                    return 100 * ((this.areaKm2 / this.previousAreaKm2) - 1)
                }
                return 0
            },
            previousAreaKm2 () {
                if (!this.lastYear) {
                    return 0
                }
                if (this.state) {
                    const state = this.lastYear.find(state => state.uf === this.state)
                    return Number(state.areamunkm)
                } else {
                    return this.lastYear.reduce((acc, state) => acc + Number(state.areamunkm), 0)
                }
            },
            unitModel: vModel('unit'),
        },
        async created () {
            const lastYear = this.now.minus({ years: 1 })
            const startOfYear = lastYear.startOf('year')

            const data = await api.get(`deter/estados?data1=${startOfYear.toISODate()}&data2=${lastYear.toISODate()}`)
            this.lastYear = data
        },
        methods: {
            roundNumber,
        },
    }
</script>
