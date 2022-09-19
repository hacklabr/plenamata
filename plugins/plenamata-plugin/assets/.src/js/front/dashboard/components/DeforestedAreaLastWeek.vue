<template>
    <DashboardPanel type="measure" icon="area.svg" icon2="relogio-small.svg">
        <template #estimativa>{{__( 'Estimates for the year', 'plenamata' )}} {{actualYear}}</template>
        <template #title>
            <strong>{{ __('Area deforested', 'plenamata') }}</strong>
            <span>{{ __('last week', 'plenamata') }}</span>
        </template>
        <template #measure>
            <DashboardMeasure :number="area">
                <template #unit>
                    <Dropdown 
                        id="unit-dalw" 
                        keyId="key"
                        keyLabel="label"
                        triggerClass="clean small color--3"
                        :options="options" 
                        :value="unitModel" 
                        :title="__( 'Unit', 'plenamata' )"
                        :value.sync="unitModel"
                        :activeField="fieldActive"
                        :activeField.sync="fieldActive"
                    />
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            <strong>{{roundNumber(trees / 10080)}}</strong>
            <span>{{ __( 'trees/minute', 'plenamata' ) }}</span>
        </template>
        <template #source>
            {{__('Source:')}}: DETER/INPE
         </template>
    </DashboardPanel>
</template>

<script>
    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
    import { DateTime } from 'luxon'
    import { getAreaKm2, getTrees } from '../../utils'
    import { roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'
    import { sprintf, __ } from '../plugins/i18n'

    export default {
        name: 'DeforestedAreaLastWeek',
        components: {
            DashboardMeasure,
            DashboardPanel,
            Dropdown
        },
        props: {
            lastWeek: { type: Object, default: null },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
            activeField: { type: [ String, Object ], default: '' }
        },
        data(){
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
            areaKm2 () {
                return getAreaKm2(this.lastWeek)
            },
            trees () {
                return getTrees(this.lastWeek)
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
