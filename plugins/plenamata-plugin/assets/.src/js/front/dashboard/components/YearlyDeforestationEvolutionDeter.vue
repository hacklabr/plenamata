<template>
    <DashboardPanel type="chart">
        <template #title>
            {{ __('Evolução anual do desmatamento', 'plenamata') }}
        </template>
        <template #filters>
            <select :aria-label="__('Unit', 'plenamata')" v-model="unitModel">
                <option value="ha">{{ __('Hectares desmatados', 'plenamata') }}</option>
                <option value="km2">{{ __('Km² desmatados', 'plenamata') }}</option>
            </select>
        </template>
        <template #chart>
            <img :src="`${$dashboard.pluginUrl}assets/build/img/chart-3.png`" :alt="__('Chart', 'plenamata')">
        </template>
        <template #footer>
            {{ __('Fonte: INPE/DETER', 'plenamata') }} • {{ __('Última atualização: 28.06.2021 com dados detectados até 18.06.2021.', 'plenamata') }}
            {{ __('Os dados anuais refletem o desmatamento de cada ano até junho.', 'plenamata') }}
            {{ __('Os dados mensais e semanais são de 2021.', 'plenamata') }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

    import DashboardPanel from './DashboardPanel.vue'
    import { vModel } from '../../utils/vue'

    export default {
        name: 'YearlyDeforestationEvolutionDeter',
        components: {
            DashboardPanel,
        },
        props: {
            state: { type: String, required: true },
            unit: { type: String, default: 'ha' },
        },
        computed: {
            unitModel: vModel('unit'),
        },
    }
</script>
