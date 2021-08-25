<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Velocidade do desmatamento em %s', 'plenamata'), year) }}
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" :number="trees / days">
                <template #unit>
                    {{ __( 'árvores por dia', 'plenamata' ) }}
                </template>
            </DashboardMeasure>
            <DashboardMeasure icon="area-icon.svg" :number="area / days">
                <template #unit>
                    <select v-model="unitModel">
                        <option value="ha">{{ __('hectares por dia', 'plenamata') }}</option>
                        <option value="km2">{{ __('km² por dia', 'plenamata') }}</option>
                    </select>
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimativa média de %s árvores por minuto', 'plenamata'), roundNumber(trees / minutes)) }}
        </template>
        <template #footer>
            {{ __('Fonte: INPE/DETER', 'plenamata') }} • Última atualização: 19.07.2021 com alertas detectados até 09.07.2021
        </template>
    </DashboardPanel>
</template>

<script>
    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'DeforestationSpeedThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            areaKm2: { type: Number, required: true },
            days: { type: Number, required: true },
            minutes: { type: Number, required: true },
            unit: { type: String, default: 'ha' },
            trees: { type: Number, required: true },
            year: { type: Number, required: true },
        },
        computed: {
            area () {
                if (this.unit === 'ha') {
                    return this.areaKm2 * 100
                } else {
                    return this.areaKm2
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
        methods: {
            roundNumber,
        },
    }
</script>
