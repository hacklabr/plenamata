<template>
    <div class="dashboard">
        <header class="dashboard__header">
            <div class="container">
                <h1>{{ __('Forestry Dashboard - Legal Amazon', 'plenamata') }}</h1>
                <form>
                    <div>
                        <label for="select-estados">{{ __('States', 'plenamata') }}</label>
                        <select id="select-estados" name="select-estados" v-model="filters.estado">
                            <option value="">{{ __('All states', 'plenamata') }}</option>
                            <option v-for="state of states" :key="state.uf" :value="state.uf">{{ state.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="select-municipios">{{ __('Municipalities', 'plenamata') }}</label>
                        <select id="select-municipios" name="select-municipios" v-model="filters.municipio" :disabled="!filters.estado">
                            <option value="">{{ __('All municipalities', 'plenamata') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="select-municipios">{{ __('Indigenous Lands', 'plenamata') }}</label>
                        <select id="select-municipios" name="select-municipios" v-model="filters.ti">
                            <option value="">{{ __('All ILs', 'plenamata') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="select-municipios">{{ __('Conservation Units', 'plenamata') }}</label>
                        <select id="select-municipios" name="select-municipios" v-model="filters.uc">
                            <option value="">{{ __('All CUs', 'plenamata') }}</option>
                        </select>
                    </div>

                    <a href="javascript:void(0)" @click="clearFilters" @keypress.enter="clearFilters">{{ __('Clear filters', 'plenamata') }}</a>
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
                    <FelledTreesThisYear :lastUpdate="lastUpdate" :minutes="minutes" :trees="trees" :year="date.year" v-if="lastUpdate"/>
                    <TotalDeforestationThisYear :areaKm2="areaKm2" :filters="filters" :now="date.now" :unit.sync="unit" :updated="updated" :year="date.year"/>
                    <DeforestationSpeedThisYear :areaKm2="areaKm2" :days="days" :minutes="minutes" :trees="trees" :unit.sync="unit" :year="date.year"/>
                    <DeforestedAreaLastWeek :filters="filters" :lastUpdate="lastUpdate" :unit.sync="unit" :updated="updated" v-if="lastUpdate"/>
                    <WeeklyDeforestationEvolution :filters="filters" :now="date.now" :source.sync="source" :unit.sync="unit" :updated="updated" :year.sync="year"/>
                    <MonthlyDeforestationEvolution :filters="filters" :source.sync="source" :unit.sync="unit" :updated="updated"/>
                    <YearlyDeforestationEvolutionDeter :filters="filters" :lastUpdate="lastUpdate" :unit.sync="unit" :updated="updated" v-if="lastUpdate"/>
                    <YearlyDeforestationEvolutionProdes :filters="filters" :unit.sync="unit" :year="date.year"/>
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
    import { fetchDeterData, fetchLastDate, fetchNews } from '../../utils/api'
    import { firstValue, shortDate } from '../../utils/filters'

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
                filters: {
                    estado: '',
                    municipio: '',
                    ti: '',
                    uc: '',
                },
                lastMonth: null,
                lastUpdate: null,
                news: [],
                source: 'deter',
                thisYear: null,
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
                return Number(this.thisYear.areamunkm)
            },
            days () {
                const lastDay = this.lastUpdate ? DateTime.fromISO(this.lastUpdate.deter_last_date) : this.date.now
                return Interval.fromDateTimes(this.date.startOfYear, lastDay).count('days')
            },
            minutes () {
                const lastDay = this.lastUpdate ? DateTime.fromISO(this.lastUpdate.deter_last_date) : this.date.now
                return Interval.fromDateTimes(this.date.startOfYear, lastDay).count('minutes')
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
                    TO: { uf: 'TO', name: 'Tocantins', lat: -10.18, long: -48.33, zoom: 5 },
                }
            },
            trees () {
                if (!this.thisYear) {
                    return 0
                }
                return Number(this.thisYear.num_arvores)
            },
            updated () {
                const today = DateTime.now().toISODate()

                return {
                    deter: shortDate(DateTime.fromISO(this.lastUpdate?.deter_last_date || today).toJSDate()).replaceAll('/', '.'),
                    sync: shortDate(DateTime.fromISO(this.lastUpdate?.last_sync || today).toJSDate()).replaceAll('/', '.'),
                }
            },
        },
        watch: {
            filters: {
                handler: 'fetchData',
                immediate: true,
                deep: true,
            },
            'filters.estado': {
                async handler () {
                    this.filters.municipio = ''
                    await this.fetchNews(this.filters.estado)
                    this.centerMap(this.filters.estado)
                },
                immediate: true,
            },
        },
        async created () {
            const lastUpdate = await fetchLastDate()
            this.lastUpdate = lastUpdate
        },
        mounted () {
            const mapEl = document.querySelector('.jeomap')
            this.$refs.map.appendChild(mapEl)
            this.setMapObject()
        },
        methods: {
            centerMap (state = '') {
                const mapEl = this.$refs.map.lastChild
                this.setMapObject();

                if (mapEl) {
                    if (state) {
                        /* One state */
                        const stateData = this.states[state]
                        this.jeomap.map.flyTo({ center: [stateData.long, stateData.lat], zoom: stateData.zoom || JeoMap.getArg('initial_zoom') })
                    } else {
                        /* All Brasil */
                        this.jeomap.map.flyTo({ center: [this.jeomap.getArg('center_lon'), this.jeomap.getArg('center_lat')], zoom: this.jeomap.getArg('initial_zoom') })
                    }
                }
            },
            clearFilters () {
                this.filters = {
                    estado: '',
                    municipio: '',
                    ti: '',
                    uc: '',
                }
            },
            async fetchData () {
                const { now, startOfYear } = this.date

                const monthAgo = now.minus({ months: 1 })
                const twoMonthsAgo = now.minus({ months: 2 })

                const [thisYear, lastMonth] = await Promise.all([
                    fetchDeterData({ ...this.filters, data1: startOfYear.toISODate(), data2: now.toISODate() }),
                    fetchDeterData({ ...this.filters, data1: twoMonthsAgo.toISODate(), data2: monthAgo.toISODate() }),
                ])
                this.thisYear = firstValue(thisYear)
                this.lastMonth = firstValue(lastMonth)
            },
            async fetchNews (state = '') {
                const news = await fetchNews(state)
                this.news = news
            },
            setMapObject() {
                let mapEl = document.querySelector('.jeomap')
                let uuid = mapEl.dataset['uui_id']
                this.jeomap = window.jeomaps[uuid]
                if ( window.innerWidth >= 900 ) {
                    this.jeomap.map.scrollZoom.enable()
                    this.jeomap.map.dragPan.enable()
                    this.jeomap.map.touchZoomRotate.enable()
                    this.jeomap.map.dragRotate.enable()
                } else {
                    this.jeomap.map.scrollZoom.disable()
                    this.jeomap.map.dragPan.disable()
                    this.jeomap.map.touchZoomRotate.disable()
                    this.jeomap.map.dragRotate.disable()
                }
            },
        },
    }
</script>
