<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Estimativa de árvores derrubadas em %s', 'plenamata'), date.year) }}
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" :number="trees">
                <template #unit>
                    {{ __( 'árvores', 'plenamata' ) }}
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ __('estimativa média de', 'plenamata') }} {{ roundNumber(treesPerMinute) }} {{ __('árvores por minuto', 'plenamata') }}
        </template>
        <template #footer>
            Fonte: INPE/DETER • Última atualização: 19.07.2021 com alertas detectados até 09.07.2021
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime, Interval } from 'luxon'

    import DashboardMeasure from './DashboardMeasure.vue'
    import DashboardPanel from './DashboardPanel.vue'
    import api from '../../utils/api'
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'FelledTreesThisYear',
        components: {
            DashboardMeasure,
            DashboardPanel,
        },
        data () {
            const now = DateTime.now()
            const startOfYear = now.startOf('year')

            return {
                date: {
                    now,
                    startOfYear,
                    year: now.year,
                },
                trees: 0,
            }
        },
        computed: {
            minutes () {
                return Interval.fromDateTimes(this.date.startOfYear, this.date.now).length('minutes')
            },
            treesPerMinute () {
                return this.trees / this.minutes
            }
        },
        async created () {
            const { now, startOfYear } = this.date

            const data = await api.get(`deter/basica?data1=${startOfYear.toISODate()}&data2=${now.toISODate()}`)
            this.trees = Number(data.num_arvores)
        },
        methods: {
            roundNumber,
        },
    }
</script>
