<template>
    <DashboardPanel type="measure" class="deforestation-rate" icon="relogio.svg" icon2="area-small.svg">
        <template #reference>{{ __('Estimates for the year', 'plenamata') }} {{ actualYear }}</template>
        <template #title>
            <strong>{{ __('Deforestation rate', 'plenamata') }}</strong>
        </template>
        <template #measure>
            <DashboardMeasure unitClass="small" :number="trees / days">
                <template #unit>
                    {{ __('trees', 'plenamata') }}<br>{{ __('per day', 'plenamata') }}
                </template>
            </DashboardMeasure>
            <DashboardMeasure :number="area/days">
                <template #unit>
                    <Dropdown id="unit-dsty" keyId="key" keyLabel="label" triggerClass="clean small color--3" :activeField.sync="fieldModel" :options="options" :title="__('Unit', 'plenamata')" v-model="unitModel"/>
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            <strong>{{ roundNumber(trees / minutes) }}</strong>
            <span>{{ __('trees per minute', 'plenamata') }}</span>
        </template>
        <template #source>{{ __('Source', 'plenamata') }}: MapBiomas</template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
    import { __ } from '../plugins/i18n'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'DeforestationSpeedThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
            Dropdown,
        },
        props: {
            activeField: { type: [Object, String], default: '' },
            areaKm2: { type: Number, required: true },
            days: { type: Number, required: true },
            minutes: { type: Number, required: true },
            trees: { type: Number, required: true },
            unit: { type: String, default: 'ha' },
            year: { type: Number, required: true },
        },
        data () {
            return {
                actualYear: DateTime.now().year,
                options: {
                    'ha' : {
                        key: 'ha',
                        label: __('hectares/day', 'plenamata')
                    },
                    'km2' : {
                        key: 'km2',
                        label: __('km²/day', 'plenamata')
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
            fieldModel: vModel('activeField'),
            unitModel: vModel('unit'),
        },
        methods: {
            roundNumber,
        },
    }
</script>
