<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Deforestation rate in %s', 'plenamata'), year) }}
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" :number="trees / days">
                <template #unit>
                    {{ __( 'trees per day', 'plenamata' ) }}
                </template>
            </DashboardMeasure>
            <DashboardMeasure icon="area-icon.svg" :number="area / days">
                <template #unit>
                    <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                        <option value="ha">{{ __('hectares por dia', 'plenamata') }}</option>
                        <option value="km2">{{ __('km² por dia', 'plenamata') }}</option>
                    </select>
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimated average of %s trees per minute', 'plenamata'), roundNumber(trees / minutes)) }}
        </template>
        <template #footer>
            {{ __('Source: MapBiomas based on DETER/INPE data.', 'plenamata') }}
        </template>
    </DashboardPanel>
</template>

<script>
    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

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
            unitModel: vModel('unit'),
        },
        methods: {
            roundNumber,
        },
    }
</script>
