<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ __('Area deforested last week', 'plenamata') }}
        </template>
        <template #measure>
            <DashboardMeasure icon="area-icon.svg" :number="area">
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
    import { DateTime } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import { getAreaKm2, getTrees } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { firstValue, roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'DeforestedAreaLastWeek',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            filters: { type: Object, required: true },
            lastUpdate: { type: Object, required: true },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
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
                return getAreaKm2(this.lastWeek)
            },
            trees () {
                if (!this.lastWeek) {
                    return 0
                }
                return getTrees(this.lastWeek)
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
            roundNumber,
            async fetchData () {
                const endDate = this.lastUpdate.deter_last_date
                const startDate = DateTime.fromISO(this.lastUpdate.deter_last_date).minus({ weeks: 1 })

                const data = await fetchDeterData({ ...this.filters, data1: startDate.toISODate(), data2: endDate })
                this.lastWeek = firstValue(data)
            },
        },
    }
</script>
