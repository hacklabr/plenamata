<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Estimativa de árvores derrubadas em %s', 'plenamata'), year) }}
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" :number="trees">
                <template #unit>
                    {{ __( 'árvores', 'plenamata' ) }}
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimativa média de %s árvores por minuto', 'plenamata'), roundNumber(trees / minutes)) }}
        </template>
        <template #footer>
            {{ __('Fonte: INPE/DETER', 'plenamata') }} • Última atualização: 19.07.2021 com alertas detectados até 09.07.2021
        </template>
    </DashboardPanel>
</template>

<script>
    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'FelledTreesThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        props: {
            minutes: { type: Number, required: true },
            trees: { type: Number, required: true },
            year: { type: Number, required: true },
        },
        methods: {
            roundNumber,
        },
    }
</script>
