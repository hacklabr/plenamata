<template>
    <DashboardPanel type="measure">
        <template #title>
            {{ sprintf(__('Estimated number of trees cut down in %s', 'plenamata'), year) }}
        </template>
        <template #measure>
            <DashboardMeasure icon="tree-icon.svg" :number="internalTrees">
                <template #unit>
                    {{ __( 'trees', 'plenamata' ) }}
                </template>
            </DashboardMeasure>
        </template>
        <template #meaning>
            {{ sprintf(__('estimated average of %s trees per minute', 'plenamata'), roundNumber(trees / minutes)) }}
        </template>
        <template #footer>
            {{ sprintf(__('Source: MapBiomas based on average daily deforestation detected by DETER in %s.', 'plenamata'), year) }}
        </template>
    </DashboardPanel>
</template>

<script>
    import { DateTime } from 'luxon'

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
        props: {
            lastUpdate: { type: Object, required: true },
            minutes: { type: Number, required: true },
            trees: { type: Number, required: true },
            updated: { type: Object, required: true },
            year: { type: Number, required: true },
        },
        data () {
            return {
                internalTrees: 0,
                interval: null,
                lastData: null,
            }
        },
        computed: {
            treesDelta () {
                if (!this.lastData) {
                    return 0
                }

                return Number(this.lastData.num_arvores) / 2419200
            },
        },
        watch: {
            lastUpdate: {
                handler: 'fetchData',
                immediate: true,
            },
            state: {
                handler: 'fetchData',
                immediate: true,
            },
            trees: {
                handler () {
                    this.internalTrees = this.trees

                    if (this.interval) {
                        window.clearInterval(this.interval)
                    }

                    this.interval = window.setInterval(() => {
                        this.internalTrees += this.treesDelta
                    }, 1000)
                },
                immediate: true,
            },
        },
        methods: {
            async fetchData () {
                if (this.lastUpdate.deter_last_date) {
                    const endDate = this.lastUpdate.deter_last_date
                    const startDate = DateTime.fromISO(this.lastUpdate.deter_last_date).minus({ days: 28 })

                    const filters = this.state ? `estados?estado=${this.state}&` : 'basica?'

                    const data = await api.get(`deter/${filters}data1=${startDate.toISODate()}&data2=${endDate}`)
                    this.lastData = Array.isArray(data) ? data[0] : data
                }
            },
            roundNumber,
        },
    }
</script>
