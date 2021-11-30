<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Total deforestation in %s in the selected territory', 'plenamata'), year) }}
        </template>
        <template #measure>
            <p>
                {{ sprintf(__('Total deforested area in %s (until last week)', 'plenamata'), year) }}
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
                {{ sprintf(__('%s%% increase compared to last year', 'plenamata'), roundNumber(increase)) }}
            </template>
            <template v-else>
                {{ sprintf(__('%s%% decrease compared to last year', 'plenamata'), roundNumber(-increase)) }}
            </template>
        </template>
        <template #footer>
            {{ sprintf(__('Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata'), updated.sync, updated.deter) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import { getAreaKm2 } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { firstValue, roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'TotalDeforestationThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            areaKm2: { type: Number, required: true },
            filters: { type: Object, default: '' },
            now: { type: DateTime, required: true },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
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
                return getAreaKm2(this.lastYear)
            },
            unitModel: vModel('unit'),
        },
        watch: {
            filters: {
                handler: 'fetchData',
                immediate: true,
                deep: true,
            },
        },
        methods: {
            async fetchData () {
                const lastYear = this.now.minus({ years: 1 })
                const startOfYear = lastYear.startOf('year')

                const data = await fetchDeterData({ ...this.filters, data1: startOfYear.toISODate(), data2: lastYear.toISODate() })
                this.lastYear = firstValue(data)
            },
            roundNumber,
        },
    }
</script>
