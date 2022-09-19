<template>
    <DashboardPanel type="measure" icon="arvore.svg" icon2="percent-small.svg">
        <template #estimativa>{{__( 'Estimates for the year', 'plenamata' )}} {{actualYear}}</template>
        <template #title>
            <strong>{{__('Total deforestation', 'plenamata')}}</strong>
        </template>
        <template #measure>
            <DashboardMeasure :number="area">
                <template #unit>
                    <Dropdown 
                        id="unit-tdty" 
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
            <strong>{{roundNumber(increase)}}%</strong>
            <span class="small" :class="{ faster: increase >= 0, slower: increase <= 0 }">
                <template v-if="increase >= 0">
                    {{ __('Faster than last year', 'plenamata') }}
                </template>
                <template v-else>
                    {{ __('Slower than last year', 'plenamata') }}
                </template>
            </span>
        </template>
        <template #source>{{ __('Source', 'plenamata') }}: DETER/INPE</template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import Dropdown from './Dropdown.vue'
    import { getAreaKm2 } from '../../utils'
    import { fetchDeterData } from '../../utils/api'
    import { firstValue, roundNumber } from '../../utils/filters'
    import { vModel } from '../../utils/vue'
    import { sprintf, __ } from '../plugins/i18n'

    export default {
        name: 'TotalDeforestationThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
            Dropdown
        },
        props: {
            areaKm2: { type: Number, required: true },
            date: { type: DateTime, required: true },
            filters: { type: Object, default: '' },
            unit: { type: String, default: 'ha' },
            updated: { type: Object, required: true },
            year: { type: Number, required: true },
            activeField: { type: [ String, Object ], default: '' }
        },
        data () {
            return {
                lastYear: null,
                actualYear: DateTime.now().year,
                fieldActive : { type: String, defaul: '' },
                options: {
                    'ha' : {
                        key: 'ha',
                        label: __('hectares', 'plenamata')
                    }, 
                    'km2' : {
                        key: 'km2',
                        label: __('km²', 'plenamata')
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
            async fetchData () {
                const lastYear = this.date.minus({ years: 1 })
                const startOfYear = lastYear.startOf('year')

                const data = await fetchDeterData({ ...this.filters, data1: startOfYear.toISODate(), data2: lastYear.toISODate() })
                this.lastYear = firstValue(data)
            },
            roundNumber,
        },
    }
</script>
