<template>
    <DashboardPanel type="measure" class="deforestation-rate" icon="relogio.svg" icon2="area-small.svg">
        <template #estimativa>{{__( 'Estimates for the year', 'plenamata' )}} {{actualYear}}</template>
        <template #title>
            <strong>{{ __('Deforestation rate', 'plenamata') }}</strong>
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" unitClass="small" :number="trees / days">
                <template #unit>
                    {{ __('trees', 'plenamata') }} <br>{{ __('per day', 'plenamata') }}
                </template>
            </DashboardMeasure>
            <DashboardMeasure :number="area/days">
                <template #unit>
                    <Dropdown 
                        id="unit-dsty" 
                        keyId="key"
                        keyLabel="label"
                        triggerClass="clean small color--3"
                        :options="options" 
                        :title="__('Unit', 'plenamata')"
                        :value="unitModel"
                        :value.sync="unitModel"
                        :activeField="fieldActive"
                        :activeField.sync="fieldActive"
                    />
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            <strong>{{roundNumber(trees/minutes)}}</strong> 
            <span>{{ __('trees per minute', 'plenamata') }}</span>
        </template>
        <template #source>{{ __('Source', 'plenamata') }}: MapBiomas</template>
    </DashboardPanel>
</template>

<script>
    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
    import { DateTime, Interval } from 'luxon'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'
    import { sprintf, __ } from '../plugins/i18n'

    export default {
        name: 'DeforestationSpeedThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
            Dropdown
        },
        props: {
            areaKm2: { type: Number, required: true },
            days: { type: Number, required: true },
            minutes: { type: Number, required: true },
            unit: { type: String, default: 'ha' },
            trees: { type: Number, required: true },
            year: { type: Number, required: true },
            openedFilter : { type: String, default: '' },
            activeField: { type: [ String, Object ], default: '' }
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
                fieldActive : { type: String, defaul: '' },
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
            unitModel: vModel('unit'),
        },
        watch: {
            fieldActive: {
                handler( active ){
                    this.$emit( 'update:activeField', active );
                }
            },
            activeField: {
                handler( active ){
                    this.fieldActive = active;
                }
            }
        },
        methods: {
            roundNumber,
        },
    }
</script>
