<template>
    <DashboardPanel type="measure" icon="area.svg" icon2="relogio-small.svg">
        <template #reference>{{ __('Estimates for the year', 'plenamata') }} {{ actualYear }}</template>
        <template #title>
            <strong>{{ __('Area deforested', 'plenamata') }}</strong>
            <span>{{ __('last week', 'plenamata') }}</span>
        </template>
        <template #measure>
            <DashboardMeasure :number="area">
                <template #unit>
                    <Dropdown id="unit-dalw" keyId="key" keyLabel="label" triggerClass="clean small color--3" :activeField.sync="fieldModel" :options="options" :title="__('Unit', 'plenamata')" v-model="unitModel"/>
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            <strong>{{ roundNumber(trees / 10080) }}</strong>
            <span>{{ __('trees/minute', 'plenamata') }}</span>
        </template>
        <template #source>
            {{ __('Source:', 'plenamata') }}: DETER/INPE
         </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
    import { getAreaKm2, getTrees } from '../../utils'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'
    import { __ } from '../plugins/i18n'

    export default {
        name: 'DeforestedAreaLastWeek',
        components: {
            DashboardMeasure,
            DashboardPanel,
            Dropdown,
        },
        props: {
            activeField: { type: [ String, Object ], default: '' },
            lastWeek: { type: Object, default: null },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
        },
        data () {
            return {
                actualYear: DateTime.now().year,
                options : {
                    'ha' : {
                        key : 'ha',
                        label : __('hectares', 'plenamata')
                    },
                    'km2' : {
                        key : 'km2',
                        label : __('km²', 'plenamata')
                    }
                },
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
                return getAreaKm2(this.lastWeek)
            },
            fieldModel: vModel('activeField'),
            trees () {
                return getTrees(this.lastWeek)
            },
            unitModel: vModel('unit'),
        },
        methods: {
            roundNumber,
        },
    }
</script>
