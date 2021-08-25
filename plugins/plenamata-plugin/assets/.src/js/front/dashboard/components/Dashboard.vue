<template>
    <div class="dashboard">
        <header class="dashboard__header">
            <div class="container">
                <h1>{{ __('Painel de dados', 'plenamata') }}</h1>
                <form>
                    <div>
                        <label for="estados">{{ __('Estado', 'plenamata') }}</label>
                        <select v-model="state">
                            <option value="">{{ __('Todos os estados', 'plenamata') }}</option>
                            <option v-for="state of states" :key="state.uf" :value="state.uf">{{ state.name }}</option>
                        </select>
                    </div>
                </form>
            </div>
        </header>

        <main>
            <div class="container">
                <fieldset class="dashboard__tabs">
                    <label class="dashboard__tab" :class="{ active: view === 'data' }">
                        <input type="radio" name="dashboard-tabs" value="data" v-model="view">
                        {{ __('Dados', 'plenamata') }}
                    </label>
                    <label class="dashboard__tab" :class="{ active: view === 'news' }">
                        <input type="radio" name="dashboard-tabs" value="news" v-model="view">
                        {{ __('Notícias', 'plenamata') }}
                    </label>
                </fieldset>

                <div v-if="view === 'data'">
                    <FelledTreesThisYear :trees="trees" :treesPerMinute="treesPerMinute" :year="date.year"/>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import { DateTime, Interval } from 'luxon'

    import FelledTreesThisYear from './FelledTreesThisYear.vue'
    import api from '../../utils/api'

    export default {
        name: 'Dashboard',
        components: {
            FelledTreesThisYear,
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
                state: '',
                thisYear: null,
                view: 'data',
            }
        },
        computed: {
            minutes () {
                return Interval.fromDateTimes(this.date.startOfYear, this.date.now).length('minutes')
            },
            states () {
                return {
                    AC: { uf: 'AC', name: 'Acre' },
                    AM: { uf: 'AM', name: 'Amazonas' },
                    AP: { uf: 'AP', name: 'Amapá' },
                    MA: { uf: 'MA', name: 'Maranhão' },
                    MT: { uf: 'MT', name: 'Mato Grosso' },
                    PA: { uf: 'PA', name: 'Pará' },
                    RO: { uf: 'RO', name: 'Rondônia' },
                    RR: { uf: 'RR', name: 'Roraima' },
                }
            },
            trees () {
                if (!this.thisYear) {
                    return 0
                }
                if (this.state) {
                    const state = this.thisYear.find(state => state.uf === this.state)
                    return Number(state.num_arvores)
                } else {
                    return this.thisYear.reduce((acc, state) => acc + Number(state.num_arvores), 0)
                }
            },
            treesPerMinute () {
                return this.trees / this.minutes
            },
        },
        async created () {
            const { now, startOfYear } = this.date

            const data = await api.get(`deter/estados?data1=${startOfYear.toISODate()}&data2=${now.toISODate()}`)
            this.thisYear = data
        },
    }
</script>
