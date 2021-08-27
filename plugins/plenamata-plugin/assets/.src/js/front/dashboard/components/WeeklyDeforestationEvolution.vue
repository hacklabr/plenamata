<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Evolução semanal do desmatamento', 'plenamata') }}
        </template>
        <template #filters>
            <select :aria-label="__('Period', 'plenamata')" v-model="yearModel">
                <option v-for="year of range" :key="year" :value="year">{{ sprintf(__('Período: %s', 'plenamata'), year) }}</option>
            </select>
            <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                <option value="ha">{{ __('Hectares desmatados', 'plenamata') }}</option>
                <option value="km2">{{ __('Km² desmatados', 'plenamata') }}</option>
            </select>
            <select :aria-label="__('Timeframe', 'plenamata')" v-model="sourceModel">
                <option value="prodes">{{ __('No ano PRODES', 'plenamata') }}</option>
                <option value="deter">{{ __('No ano DETER', 'plenamata') }}</option>
            </select>
        </template>
        <template #chart>
            <img :src="`${$dashboard.pluginUrl}assets/build/img/chart-1.png`" :alt="__('Chart', 'plenamata')">
        </template>
        <template #footer>
            {{ __('Fonte: INPE/DETER', 'plenamata') }} • {{ __('Última atualização: 28.06.2021 com dados detectados até 18.06.2021.', 'plenamata') }}
            {{ __('Os dados anuais refletem o desmatamento de cada ano até junho.', 'plenamata') }}
            {{ __('Os dados semanais são de 2021.', 'plenamata') }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

    import DashboardPanel from './DashboardPanel.vue'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'WeeklyDeforestationEvolution',
        components: {
            DashboardPanel,
        },
        props: {
            now: { type: DateTime, required: true },
            source: { type: String, default: 'prodes' },
            state: { type: String, required: true },
            unit: { type: String, default: 'ha' },
            year: { type: Number, required: true },
        },
        computed: {
            range () {
                const years = []
                for (let year = 2016; year <= this.now.year; year++) {
                    years.push(year)
                }
                return years
            },
            sourceModel: vModel('source'),
            unitModel: vModel('unit'),
            yearModel: vModel('year'),
        },
    }
</script>
