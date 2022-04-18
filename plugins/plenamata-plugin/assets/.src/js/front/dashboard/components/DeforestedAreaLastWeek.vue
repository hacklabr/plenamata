<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ __('Area deforested last week in the selected territory', 'plenamata') }}
        </template>
        <template #measure>
            <DashboardMeasure icon="area-icon.svg" :number="humanNumber(area)">
                <template #unit>
                    <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                        <option value="ha">{{ __('hectares', 'plenamata') }}</option>
                        <option value="km2">{{ __('km²', 'plenamata') }}</option>
                    </select>
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimated average of %s trees per minute', 'plenamata'), roundNumber(trees / 10080)) }}
        </template>
        <template #footer>
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.sync, updated.deter) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import { getAreaKm2, getTrees } from '../../utils'
    import { humanNumber, roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'DeforestedAreaLastWeek',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            lastWeek: { type: Object, default: null },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
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
                return getAreaKm2(this.lastWeek)
            },
            trees () {
                return getTrees(this.lastWeek)
            },
            unitModel: vModel('unit'),
        },
        methods: {
            humanNumber,
            roundNumber,
        },
    }
</script>
