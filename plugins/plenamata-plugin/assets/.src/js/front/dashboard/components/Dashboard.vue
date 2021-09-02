<template>
    <div class="dashboard">
        <header class="dashboard__header">
            <div class="container">
                <h1>{{ __('Dashboard', 'plenamata') }}</h1>
                <form>
                    <div>
                        <label for="select-estados">{{ __('States', 'plenamata') }}</label>
                        <select id="select-estados" v-model="state">
                            <option value="">{{ __('All states', 'plenamata') }}</option>
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
                        <img :src="`${$dashboard.pluginUrl}assets/build/img/dashboard-chart-icon.svg`" alt="">
                        {{ __('Data', 'plenamata') }}
                    </label>
                    <label class="dashboard__tab" :class="{ active: view === 'news' }">
                        <input type="radio" name="dashboard-tabs" value="news" v-model="view">
                        <img :src="`${$dashboard.pluginUrl}assets/build/img/dashboard-newspaper-icon.svg`" alt="">
                        {{ __('News', 'plenamata') }}
                    </label>
                </fieldset>

                <div class="dashboard__panels" v-if="view === 'data'">
                    <FelledTreesThisYear :minutes="minutes" :trees="trees" :year="date.year"/>
                    <TotalDeforestationThisYear :areaKm2="areaKm2" :now="date.now" :state="state" :unit.sync="unit" :year="date.year"/>
                    <DeforestationSpeedThisYear :areaKm2="areaKm2" :days="days" :minutes="minutes" :trees="trees" :unit.sync="unit" :year="date.year"/>
                    <DeforestedAreaLastWeek :now="date.now" :state="state" :unit.sync="unit"/>
                    <WeeklyDeforestationEvolution :now="date.now" :source.sync="source" :state="state" :unit.sync="unit" :year.sync="year"/>
                    <MonthlyDeforestationEvolution :source.sync="source" :state="state" :unit.sync="unit"/>
                    <YearlyDeforestationEvolutionDeter :state="state" :unit.sync="unit"/>
                    <YearlyDeforestationEvolutionProdes :state="state" :unit.sync="unit" :year="date.year"/>
                </div>

                <div class="dashboard__news" v-else-if="view === 'news'">
                    <DashboardNewsCard v-for="post of news" :key="post.id" :post="post"/>
                    <p v-if="news.length === 0">{{ __('No news to be shown.', 'plenamata') }}</p>
                </div>
            </div>
        </main>

        <div class="dashboard__map" v-once>
            <div ref="map"/>
        </div>
    </div>
</template>

<script>
    import { DateTime, Interval } from 'luxon'

    import DashboardNewsCard from './DashboardNewsCard.vue'
    import DeforestationSpeedThisYear from './DeforestationSpeedThisYear.vue'
    import DeforestedAreaLastWeek from './DeforestedAreaLastWeek.vue'
    import FelledTreesThisYear from './FelledTreesThisYear.vue'
    import MonthlyDeforestationEvolution from './MonthlyDeforestationEvolution.vue'
    import TotalDeforestationThisYear from './TotalDeforestationThisYear.vue'
    import WeeklyDeforestationEvolution from './WeeklyDeforestationEvolution.vue'
    import YearlyDeforestationEvolutionDeter from './YearlyDeforestationEvolutionDeter.vue'
    import YearlyDeforestationEvolutionProdes from './YearlyDeforestationEvolutionProdes.vue'
    import api from '../../utils/api'

    export default {
        name: 'Dashboard',
        components: {
            DashboardNewsCard,
            DeforestationSpeedThisYear,
            DeforestedAreaLastWeek,
            FelledTreesThisYear,
            MonthlyDeforestationEvolution,
            TotalDeforestationThisYear,
            WeeklyDeforestationEvolution,
            YearlyDeforestationEvolutionDeter,
            YearlyDeforestationEvolutionProdes,
        },
        data () {
            const now = DateTime.now()
            const startOfYear = now.startOf('year')
            const year = now.year

            return {
                date: {
                    now,
                    startOfYear,
                    year,
                },
                lastMonth: [],
                news: [],
                source: 'deter',
                state: '',
                trees: 0,
                thisYear: [],
                unit: 'ha',
                view: 'data',
                year,
            }
        },
        computed: {
            areaKm2 () {
                if (!this.thisYear) {
                    return 0
                }
                if (this.state) {
                    const state = this.thisYear.find(state => state.uf === this.state)
                    return Number(state.areamunkm)
                } else {
                    return this.thisYear.reduce((acc, state) => acc + Number(state.areamunkm), 0)
                }
            },
            days () {
                return Interval.fromDateTimes(this.date.startOfYear, this.date.now).length('days')
            },
            minutes () {
                return Interval.fromDateTimes(this.date.startOfYear, this.date.now).length('minutes')
            },
            states () {
                return {
                    AC: { uf: 'AC', name: 'Acre', lat: -8.77, long: -70.55, zoom: 6 },
                    AM: { uf: 'AM', name: 'Amazonas', lat: -3.07, long: -61.66, zoom: 5 },
                    AP: { uf: 'AP', name: 'Amapá', lat: 1.41, long: -51.77, zoom: 6 },
                    MA: { uf: 'MA', name: 'Maranhão', lat: -2.55, long: -44.30, zoom: 5 },
                    MT: { uf: 'MT', name: 'Mato Grosso', lat: -12.64, long: -55.42, zoom: 5 },
                    PA: { uf: 'PA', name: 'Pará', lat: -5.53, long: -52.29, zoom: 5 },
                    RO: { uf: 'RO', name: 'Rondônia', lat: -11.22, long: -62.80, zoom: 6 },
                    RR: { uf: 'RR', name: 'Roraima', lat: 1.89, long: -61.22, zoom: 6 },
                }
            },
            treesDelta () {
                let trees = 0
                if (this.state) {
                    const state = this.lastMonth.find(state => state.uf === this.state)
                    trees = Number(state.num_arvores)
                } else {
                    trees = this.lastMonth.reduce((acc, state) => acc + Number(state.num_arvores), 0)
                }
                return trees / 2592000
            },
        },
        watch: {
            state: {
                async handler () {
                    await this.fetchNews(this.state)
                    this.updateTrees()
                    this.centerMap(this.state)
                },
                immediate: true,
            },
            thisYear () {
                this.updateTrees()
            },
        },
        async created () {
            const { now, startOfYear } = this.date

            const monthAgo = now.minus({ months: 1 })
            const twoMonthsAgo = now.minus({ months: 2 })

            const [thisYear, lastMonth] = await Promise.all([
                api.get(`deter/estados?data1=${startOfYear.toISODate()}&data2=${now.toISODate()}`),
                api.get(`deter/estados?data1=${twoMonthsAgo.toISODate()}&data2=${monthAgo.toISODate()}`),
            ])
            this.thisYear = thisYear
            this.lastMonth = lastMonth
        },
        mounted () {
            const mapEl = document.querySelector('.jeomap')
            this.$refs.map.appendChild(mapEl)

            setInterval(() => {
                if (this.trees) {
                    this.trees += this.treesDelta
                }
            }, 1000)
        },
        methods: {
            centerMap (state = '') {
                const mapEl = this.$refs.map.lastChild
                if (mapEl) {
                    const uuid = mapEl.dataset['uui_id']
                    const JeoMap = window.jeomaps[uuid]
                    if (state) {
                        /* One state */
                        const stateData = this.states[state]
                        JeoMap.map.flyTo({ center: [stateData.long, stateData.lat], zoom: stateData.zoom || JeoMap.getArg('initial_zoom') })
                    } else {
                        /* All Brasil */
                        JeoMap.map.flyTo({ center: [JeoMap.getArg('center_lon'), JeoMap.getArg('center_lat')], zoom: JeoMap.getArg('initial_zoom') })
                    }
                }
            },
            async fetchNews (state = '') {
                const news = await api.get(`${this.$dashboard.restUrl}wp/v2/posts/?_embed&state=${state}`, false)
                this.news = news
            },
            updateTrees () {
                if (!this.thisYear) {
                    this.trees = 0
                } else if (this.state) {
                    const state = this.thisYear.find(state => state.uf === this.state)
                    this.trees = Number(state.num_arvores)
                } else {
                    this.trees = this.thisYear.reduce((acc, state) => acc + Number(state.num_arvores), 0)
                }
            },
        },
    }
</script>
