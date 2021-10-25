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
                            <option v-for="municipality of data.municipalities" :key="municipality.mun_geo_cod" :value="municipality.mun_geo_cod">{{ municipality.municipio }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="select-municipios">{{ __('Indigenous Lands', 'plenamata') }}</label>
                        <select id="select-municipios" name="select-municipios" v-model="filters.ti">
                            <option value="">{{ __('All ILs', 'plenamata') }}</option>
                            <option v-for="ti of tis" :key="ti.code" :value="String(ti.code)">{{ ti.ti }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="select-municipios">{{ __('Conservation Units', 'plenamata') }}</label>
                        <select id="select-municipios" name="select-municipios" v-model="filters.uc">
                            <option value="">{{ __('All CUs', 'plenamata') }}</option>
                            <option v-for="uc of ucs" :key="uc.code" :value="String(uc.code)">{{ capitalize(uc.uc) }}</option>
                        </select>
                    </div>

                    <a href="javascript:void(0)" @click="clearFilters" @keypress.enter="clearFilters">{{ __('Clear filters', 'plenamata') }}</a>
                </form>
            </div>
        </header>

        <main>
            <div class="container">
                <fieldset class="dashboard__tabs">
                    <label class="dashboard__tab" :class="{ active: view === 'data' }" id="dashboard-tab-data">
                        <input type="radio" name="dashboard-tabs" ref="tabDataRadio" value="data" v-model="view">
                        <img :src="`${$dashboard.pluginUrl}assets/build/img/dashboard-chart-icon.svg`" alt="">
                        {{ __('Data', 'plenamata') }}
                    </label>
                    <label class="dashboard__tab" :class="{ active: view === 'news' }" id="dashboard-tab-news">
                        <input type="radio" name="dashboard-tabs" ref="tabNewsRadio" value="news" v-model="view">
                        <img :src="`${$dashboard.pluginUrl}assets/build/img/dashboard-newspaper-icon.svg`" alt="">
                        {{ __('News', 'plenamata') }}
                    </label>
                </fieldset>

                <div class="dashboard__panels" v-if="view === 'data'">
                    <FelledTreesThisYear :lastUpdate="lastUpdate" :minutes="minutes" :trees="trees" :year="date.year" v-if="lastUpdate"/>
                    <TotalDeforestationThisYear :areaKm2="areaKm2" :filters="filters" :now="date.now" :unit.sync="unit" :updated="updated" :year="date.year"/>
                    <DeforestationSpeedThisYear :areaKm2="areaKm2" :days="days" :minutes="minutes" :trees="trees" :unit.sync="unit" :year="date.year"/>
                    <DeforestedAreaLastWeek :filters="filters" :lastUpdate="lastUpdate" :unit.sync="unit" :updated="updated" v-if="lastUpdate"/>
                    <WeeklyDeforestationEvolution :filters="filters" :lastUpdate="lastUpdate" :now="date.now" :source.sync="source" :unit.sync="unit" :updated="updated" :year.sync="year" v-if="lastUpdate"/>
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
    import scrollIntoView from 'scroll-into-view'

    import DashboardNewsCard from './DashboardNewsCard.vue'
    import DeforestationSpeedThisYear from './DeforestationSpeedThisYear.vue'
    import DeforestedAreaLastWeek from './DeforestedAreaLastWeek.vue'
    import FelledTreesThisYear from './FelledTreesThisYear.vue'
    import MonthlyDeforestationEvolution from './MonthlyDeforestationEvolution.vue'
    import TotalDeforestationThisYear from './TotalDeforestationThisYear.vue'
    import WeeklyDeforestationEvolution from './WeeklyDeforestationEvolution.vue'
    import YearlyDeforestationEvolutionDeter from './YearlyDeforestationEvolutionDeter.vue'
    import YearlyDeforestationEvolutionProdes from './YearlyDeforestationEvolutionProdes.vue'
    import { capitalize, getAreaKm2, getTrees, localeSortBy } from '../../utils'
    import { fetchConservationUnits, fetchDeterData, fetchIndigenousLands, fetchLastDate, fetchMunicipalities, fetchNews } from '../../utils/api'
    import { firstValue, shortDate } from '../../utils/filters'
    import { clearSelectedNews } from '../../utils/mapInteractions'

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
                data: {
                    municipalities: [],
                    ucs: [],
                    tis: [],
                },
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
                return getAreaKm2(this.thisYear)
            },
            days () {
                const lastDay = this.lastUpdate ? DateTime.fromISO(this.lastUpdate.deter_last_date, { zone: 'utc' }) : this.date.now
                return Interval.fromDateTimes(this.date.startOfYear, lastDay).count('days')
            },
            minutes () {
                const lastDay = this.lastUpdate ? DateTime.fromISO(this.lastUpdate.deter_last_date, { zone: 'utc' }) : this.date.now
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
            tis () {
                return this.data.tis
                    .slice(0)
                    .sort(localeSortBy(ti => ti.ti))
            },
            trees () {
                if (!this.thisYear) {
                    return 0
                }
                return getTrees(this.thisYear)
            },
            ucs () {
                return this.data.ucs
                    .slice(0)
                    .sort(localeSortBy(uc => uc.uc))
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

                    if (this.filters.estado) {
                        this.data.municipalities = await fetchMunicipalities(this.filters.estado)
                        this.filters.ti = ''
                        this.filters.uc = ''
                    } else {
                        this.data.municipalities = []
                    }

                    await this.fetchNews(this.filters.estado)
                },
                immediate: true,
            },
            'filters.municipio' () {
                if (this.filters.municipio) {
                    this.filters.ti = ''
                    this.filters.uc = ''
                }
            },
            'filters.ti' () {
                if (this.filters.ti) {
                    this.filters.estado = ''
                    this.filters.municipio = ''
                    this.filters.uc = ''
                }
            },
            'filters.uc' () {
                if (this.filters.uc) {
                    this.filters.estado = ''
                    this.filters.municipio = ''
                    this.filters.ti = ''
                }
            },
        },
        async created () {
            const [lastUpdate, tis, ucs] = await Promise.all([
                fetchLastDate(),
                fetchIndigenousLands(),
                fetchConservationUnits(),
            ])
            this.data = { municipalities: [], tis, ucs }
            this.lastUpdate = lastUpdate
        },
        mounted () {
            const mapEl = document.querySelector('.jeomap')
            this.$refs.map.appendChild(mapEl)
            this.setMapObject()
        },
        methods: {
            capitalize,
            centerMap () {
                const mapEl = this.$refs.map.lastChild
                this.setMapObject()

                const { municipio, estado, ti, uc } = this.filters

                if (mapEl) {
                    if (municipio) {
                        const municipality = this.data.municipalities.find(municipality => municipality.mun_geo_cod === municipio)
                        this.jeomap.map.flyTo({ center: [+municipality.long, +municipality.lat], zoom: 7 })
                    } else if (estado) {
                        const state = this.states[estado]
                        this.jeomap.map.flyTo({ center: [state.long, state.lat], zoom: state.zoom || JeoMap.getArg('initial_zoom') })
                    } else if (ti) {
                        const point = this.data.tis.find(item => item.code == ti)
                        this.jeomap.map.flyTo({ center: [+point.long, +point.lat], zoom: 7 })
                    } else if (uc) {
                        const point = this.data.ucs.find(item => item.code == uc)
                        this.jeomap.map.flyTo({ center: [+point.long, +point.lat], zoom: 7 })
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
            clearSelectedNews,
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
                this.centerMap()
            },
            async fetchNews (state = '') {
                const news = await fetchNews(state)
                this.news = news
            },
            openNews(e) {
                this.clearSelectedNews()
                let postId = e.features[0].properties.id
                this.view = 'news'
                setTimeout(() => {
                    let newsElem = document.querySelector(`[data-id="${postId}"]`)
                    if (newsElem == null) {
                        return
                    }
                    scrollIntoView(newsElem)
                    newsElem.classList.add('selected')
                }, 900)
            },
            setMapObject() {
                let mapEl = document.querySelector('.jeomap')
                let uuid = mapEl.dataset['uui_id']
                this.jeomap = window.jeomaps[uuid]
                window.dashboardJeoMap = this.jeomap

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

                this.jeomap.map.on('click', 'unclustered-points', (e) => {
                    this.openNews(e)
                })
            },
        },
    }
</script>
