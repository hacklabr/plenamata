<template>
    <div class="dashboard">
        <header class="dashboard__header">
            <div class="container">
                <div class="dashboard__filter-toggle" :class="{ '-on': showFilters }">
                    <em><strong>{{ __( 'Monitor da floresta', 'plenamata' ) }}</strong> <span>-</span> {{ __( 'Amazônia Legal', 'plenamata' ) }}</em>
                    <button type="button" class="clean green" @click="openFilters" @keypress.enter="openFilters"><span>{{ _x('Filtrar', 'plenamata') }}</span></button>
                </div>
                <form :class="{ '-hidden': !showFilters }">
                    <header>
                        <em>{{ __( 'Filtrar por', 'plenamata' ) }}:</em>
                        <button type="button" class="close" title="Fechar" @click="closeFilters" @keypress.enter="closeFilters"></button>
                    </header>
                    <span class="fields">
                        <Dropdown 
                            id="select-estados" 
                            keyId="uf" 
                            keyLabel="name"
                            icon="map-states"
                            :title="__('All states', 'plenamata')"
                            :options="states" 
                            :placeholder="__('All states', 'plenamata' )" 
                            :value="filters.estado" 
                            :value.sync="filters.estado" 
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                        <Dropdown 
                            id="select-municipios" 
                            keyId="mun_geo_cod" 
                            keyLabel="municipio" 
                            icon="map-cities"
                            :title="__('All municipalities', 'plenamata')"
                            :options="data.municipalities" 
                            :placeholder="__('All municipalities', 'plenamata' )" 
                            :value="filters.municipio" 
                            :value.sync="filters.municipio"
                            :locked="filters.estado === ''"
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                        <Dropdown 
                            id="select-land" 
                            keyId="code" 
                            keyLabel="ti" 
                            icon="lands"
                            :title="__('Indigenous Land', 'plenamata')"
                            :options="tis" 
                            :placeholder="__('All ILs', 'plenamata' )" 
                            :value="filters.ti" 
                            :value.sync="filters.ti"
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                        <Dropdown 
                            id="select-unit" 
                            keyId="code" 
                            keyLabel="uc" 
                            icon="units"
                            :options="ucs" 
                            :placeholder="__('All CUs', 'plenamata' )" 
                            :value="filters.uc" 
                            :title="__('Conservation Unit', 'plenamata')"
                            :value.sync="filters.uc"
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                        <Dropdown 
                            id="select-year"
                            keyId="key" 
                            keyLabel="label"
                            icon="period"
                            :title="__('Select period', 'plenamata')"
                            :options="range"
                            :placeholder="__('All periods', 'plenamata' )" 
                            :value="filters.year" 
                            :value.sync="filters.year"
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                    </span>
                    <span class="controls">
                        <span></span>
                        <!-- <button type="button" class="apply" :title="__('Aplicar filtro', 'plenamata')">{{__('Apply filters', 'plenamata')}}</button> -->
                        <a href="javascript:void(0)" class="clear" @click="clearFilters" @keypress.enter="clearFilters">
                            <span>
                                <i></i>
                                <strong>{{ __('Clear filters', 'plenamata') }}</strong>
                            </span>
                        </a>
                   </span>
                </form>
            </div>
        </header>

        <main class="dashboard__main-data">
            
            <div class="container">
            
                <fieldset class="dashboard__tabs">
                    <label class="dashboard__tab" :class="{ active: view === 'data' }" id="dashboard-tab-data">
                        <input type="radio" name="dashboard-tabs" ref="tabDataRadio" value="data" v-model="view">
                        <img :src="`${$plenamata.pluginUrl}assets/build/img/icon-data.svg`" alt="">
                        {{ __('Data', 'plenamata') }}
                    </label>
                    <label class="dashboard__tab" :class="{ active: view === 'news' }" id="dashboard-tab-news">
                        <input type="radio" name="dashboard-tabs" ref="tabNewsRadio" value="news" v-model="view">
                        <img :src="`${$plenamata.pluginUrl}assets/build/img/dashboard-newspaper-icon.svg`" alt="">
                        {{ __('News', 'plenamata') }}
                    </label>
                </fieldset>

                <div class="dashboard__panels" v-if="view === 'data' && lastUpdate">

                    <header>
                        <em><strong>{{ __( 'Forestry Dashboard', 'plenamata' ) }}</strong> - {{ __( 'Legal Amazon', 'plenamata' ) }}</em>
                        <p>{{ sprintf(__( 'Estimates of trees cut down in %s', 'plenamata' ), year )}}</p>
                    </header>
                    
                    <div v-if="this.getYear() === actualYear" class="squareds-items">
                        <FelledTreesThisYear 
                            :lastWeek="lastWeek" 
                            :minutes="minutes" 
                            :trees="trees"
                            :opened="opened"
                            :opened.sync="opened"
                        />
                        <TotalDeforestationThisYear 
                            :areaKm2="areaKm2" 
                            :date="date" 
                            :filters="filters" 
                            :unit.sync="unit" 
                            :updated="updated" 
                            :year="year"
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                        <DeforestationSpeedThisYear 
                            :areaKm2="areaKm2" 
                            :days="days" 
                            :minutes="minutes" 
                            :trees="trees" 
                            :unit.sync="unit" 
                            :year="year"
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                        <DeforestedAreaLastWeek 
                            :lastWeek="lastWeek" 
                            :unit.sync="unit"
                            :updated="updated"
                            :activeField="activeField"
                            :activeField.sync="activeField"
                        />
                    </div>

                    <DeforestationCharts />

                    <WeeklyDeforestationEvolution 
                        :date="date" 
                        :filters="filters" 
                        :source.sync="source" 
                        :unit.sync="unit" 
                        :updated="updated" 
                        :year.sync="this.filters.year"
                        :activeField="activeField"
                        :activeField.sync="activeField"
                    />
                    
                    <MonthlyDeforestationEvolution 
                        :date="date" 
                        :filters="filters" 
                        :source.sync="source" 
                        :unit.sync="unit" 
                        :updated="updated"
                        :activeField="activeField"
                        :activeField.sync="activeField"
                    />
                    <YearlyDeforestationEvolutionDeter 
                        :date="date" 
                        :filters="filters" 
                        :unit.sync="unit" 
                        :updated="updated"
                        :activeField="activeField"
                        :activeField.sync="activeField"
                    />
                    <YearlyDeforestationEvolutionProdes 
                        :filters="filters" 
                        :unit.sync="unit" 
                        :year="year"
                        :activeField="activeField"
                        :activeField.sync="activeField"
                    />
               
                </div>

                <div class="dashboard__news" v-else-if="view === 'news'">
                    
                    <DashboardNewsCard v-for="post of news" :key="post.id" :post="post"/>
                    <p v-if="news.length === 0">{{ __('No news to be shown.', 'plenamata') }}</p>
                    <a href="#" class="dashboard__loadmore" @click="loadMore($event)" v-if="currentFetchNewsPage < wpApiTotalPages">
                        {{ loadMoreText }}
                    </a>
                
                </div>
            
            </div>
        
        </main>

        <div class="dashboard__map" v-once>
            <div ref="map"/>
        </div>
    </div>
</template>

<script>

    //v-on:setOpened="setOpened"

    import { DateTime, Interval } from 'luxon'
    import scrollIntoView from 'scroll-into-view'

    import Dropdown from './Dropdown.vue'
    import DeforestationCharts from '../../blocks/components/DeforestationCharts.vue'
    import DashboardNewsCard from './DashboardNewsCard.vue'
    import DeforestationSpeedThisYear from './DeforestationSpeedThisYear.vue'
    import DeforestedAreaLastWeek from './DeforestedAreaLastWeek.vue'
    import FelledTreesThisYear from './FelledTreesThisYear.vue'
    import MonthlyDeforestationEvolution from './MonthlyDeforestationEvolution.vue'
    import TotalDeforestationThisYear from './TotalDeforestationThisYear.vue'
    import WeeklyDeforestationEvolution from './WeeklyDeforestationEvolution.vue'
    import YearlyDeforestationEvolutionDeter from './YearlyDeforestationEvolutionDeter.vue'
    import YearlyDeforestationEvolutionProdes from './YearlyDeforestationEvolutionProdes.vue'
    import { capitalize, getAreaKm2, getTrees, localeSortBy, wait } from '../../utils'
    import { fetchConservationUnits, fetchDeterData, fetchIndigenousLands, fetchLastDate, fetchMunicipalities, fetchNews, fetchUniqueNews } from '../../utils/api'
    import { firstValue, shortDate, stateCodeByName } from '../../utils/filters'
    import { clearSelectedNews } from '../../utils/mapInteractions'
    import { sprintf, __ } from '../../dashboard/plugins/i18n'

    export default {
        name: 'Dashboard',
        components: {
            Dropdown,
            DeforestationCharts,
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
            return {
                data: {
                    municipalities: [],
                    ucs: [],
                    tis: [],
                },
                filters: {
                    estado: '',
                    municipio: '',
                    ti: '',
                    uc: '',
                    year: '',
                },
                activeField : '',
                opened : '',
                lastUpdate: null,
                lastWeek: null,
                news: [],
                showFilters: false,
                source: 'deter',
                thisYear: null,
                unit: 'ha',
                view: 'data',
                year: DateTime.now().year,
                actualYear: DateTime.now().year,
                currentFetchNewsPage: 1,
                wpApiTotalPages: 999999,
                loadMoreText: __('Load more', 'plenamata')
            }
        },
        computed: {
            areaKm2 () {
                if (!this.thisYear) {
                    return 0
                }
                return getAreaKm2(this.thisYear)
            },
            date () {
                if (!this.lastUpdate) {
                    return null
                }
                return DateTime.fromISO(this.lastUpdate.deter_last_date, { zone: 'utc' })
            },
            days () {
                return this.daysThisYear.count('days')
            },
            daysThisYear () {
                return Interval.fromDateTimes(this.date.startOf('year'), this.date)
            },
            minutes () {
                return this.daysThisYear.count('minutes')
            },
            startOfYear () {
                if (!this.date) {
                    return null
                }
                return this.date.startOf('year')
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
            range() {
                let maxYear = new Date().getFullYear();
                let years = {}
                for( let year = 2016; year <= maxYear; year++ ){
                    years[ year ] = { key: year, label: year };
                }
                return years
            },
            tis () {
                return this.data.tis.slice(0).sort(localeSortBy(ti => ti.ti))
            },
            trees () {
                if (!this.thisYear) {
                    return 0
                }
                return getTrees(this.thisYear)
            },
            ucs () {
                return this.data.ucs.slice(0).sort(localeSortBy(uc => uc.uc))
            },
            updated () {
                return {
                    deter: shortDate(this.lastUpdate?.deter_last_date).replaceAll('/', '.'),
                    sync: shortDate(this.lastUpdate?.last_sync).replaceAll('/', '.'),
                }
            },
        },
        watch: {
            filters: {
                handler () {

                    this.closeFilters();

                    this.fetchData()

                    if (!this.filters.estado && !this.filters.municipio && !this.filters.ti && !this.filters.uc) {
                        wait(() => this.jeomap.map?.isStyleLoaded(), () => {
                            this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'none')
                            this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                            this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'none')
                            this.flyTo({ lat: this.jeomap.getArg('center_lat'), long: this.jeomap.getArg('center_lon'), zoom: this.jeomap.getArg('initial_zoom') })
                        })
                    }
                },
                deep: true,
            },
            async 'filters.estado' () {
                this.filters.municipio = ''

                if (this.filters.estado) {
                    this.data.municipalities = await fetchMunicipalities(this.filters.estado)
                    this.filters.ti = ''
                    this.filters.uc = ''
                    this.jeomap?.map.setFilter('uf-brasil', ['==', ['get', 'UF_05'], this.filters.estado])
                    this.jeomap?.map.setLayoutProperty('uf-brasil', 'visibility', 'visible')
                    const { lat, long, zoom } = this.states[this.filters.estado]
                    this.flyTo({ lat, long, zoom: zoom || JeoMap.getArg('initial_zoom') })
                } else {
                    this.data.municipalities = []
                    this.jeomap?.map.setFilter('uf-brasil', null)
                    this.jeomap?.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                }

                await this.fetchNews(this.filters.estado)
            },
            'filters.municipio' () {
                if (this.filters.municipio) {
                    this.filters.ti = ''
                    this.filters.uc = ''
                    const { lat, long } = this.data.municipalities.find(municipality => municipality.mun_geo_cod === this.filters.municipio)
                    this.flyTo({ lat, long })
                } else if (this.filters.estado) {
                    const { lat, long, zoom } = this.states[this.filters.estado]
                    this.flyTo({ lat, long, zoom: zoom || JeoMap.getArg('initial_zoom') })
                }
            },
            'filters.ti' () {
                if (this.filters.ti) {
                    this.filters.estado = ''
                    this.filters.municipio = ''
                    this.filters.uc = ''
                    this.jeomap.map.setFilter('tis-brasil', ['==', ['get', 'terrai_cod'], +this.filters.ti])
                    this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'visible')
                    const { lat, long } = this.data.tis.find(item => item.code == this.filters.ti)
                    this.flyTo({ lat, long })
                } else {
                    this.jeomap.map.setFilter('tis-brasil', null)
                    this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'none')
                }
            },
            'filters.uc' () {
                if (this.filters.uc) {
                    this.filters.estado = ''
                    this.filters.municipio = ''
                    this.filters.ti = ''
                    this.jeomap.map.setFilter('ucs-brasil', ['==', ['get', 'id'], +this.filters.uc])
                    this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'visible')
                    const { lat, long } = this.data.ucs.find(item => item.code == this.filters.uc)
                    this.flyTo({ lat, long })
                } else {
                    this.jeomap.map.setFilter('ucs-brasil', null)
                    this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'none')
                }
            }
        },
        async created () {
            const [lastUpdate, tis, ucs] = await Promise.all([
                fetchLastDate(),
                fetchIndigenousLands(),
                fetchConservationUnits(),
            ])
            this.data = { municipalities: [], tis, ucs }
            this.lastUpdate = lastUpdate
            this.year = Number(lastUpdate.deter_last_date.slice(0, 4))
            await this.fetchData()
            this.fetchNews()
            this.addLayerDates()
        },
        mounted () {
            const mapEl = document.querySelector('.jeomap')
            this.$refs.map.appendChild(mapEl)
            this.setMapObject()
            this.setMapEvents()
        },
        beforeUpdate () {
            this.setMapObject()
        },
        methods: {
            // Open floater field
            setAactiveField( field_id ){
                this.activeField = field_id;
            },
            getYear(){
                const year = this.filters.year === '' ? this.actualYear : this.filters.year;
                return year;
            },
            addLayerDates () {
                const mapEl = this.$refs.map.lastChild
                wait(() => mapEl.querySelector('.map-content-layers-list'), (layersList) => {
                    const anchor = layersList.querySelector('p:last-of-type')
                    const firsrDate = shortDate(this.lastUpdate.deter_first_date)
                    const lastDate = shortDate(this.lastUpdate.deter_last_date)
                    const text = sprintf(__('The data of this layer includes the alerts detected in the period between %s and %s, verified since the last update of PRODES.', 'plenamata'), firsrDate, lastDate)
                    anchor.parentNode.insertBefore(document.createElement('br'), anchor)
                    anchor.parentNode.insertBefore(new Text(text), anchor)
                })
            },
            capitalize,
            clearFilters () {

                this.closeFilters();

                // Close drops
                this.activeField = '';

                this.filters = {
                    estado: '',
                    municipio: '',
                    ti: '',
                    uc: '',
                    year: this.actualYear,
                }
            
            },
            clearSelectedNews,
            async fetchData () {
                const weekAgo = this.date.minus({ days: 6 })

                const [thisYear, lastWeek] = await Promise.all([
                    fetchDeterData({ ...this.filters, data1: this.startOfYear.toISODate(), data2: this.date.toISODate() }),
                    fetchDeterData({ ...this.filters, data1: weekAgo.toISODate(), data2: this.date.toISODate() }),
                ])
                this.thisYear = firstValue(thisYear)
                this.lastWeek = firstValue(lastWeek)
                this.setMapObject()
            },
            async fetchNews (state = '') {
                const news = await fetchNews(state)
                this.news = news
                this.currentFetchNewsPage = 1
                this.updateWPTotalPages()
            },
            async fetchNewsByPage (state = '', pageNum = 1) {
                this.loadMoreText = __('Carregando...', 'plenamata')
                const news = await fetchNews(state, pageNum)
                this.news = this.news = [...this.news, ...news]
                this.currentFetchNewsPage = pageNum
                this.updateWPTotalPages()
                this.loadMoreText = __('Ler mais', 'plenamata')
            },
            async fetchUniqueNews (postId, callback) {
                let news = await fetchUniqueNews(postId)
                let found = this.news.find(element => element.id === postId)
                if (found) {
                    return
                }
                this.news.unshift(news)
                const newsState = stateCodeByName(news.meta._related_point[0]._geocode_region_level_2)

                if (newsState) {
                    this.jeomap.map.setFilter('uf-brasil', ['==', ['get', 'UF_05'], newsState ])
                    this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'visible')
                } else {
                    this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                }
                if (typeof callback === 'function') {
                    callback(news)
                }
            },
            flyTo ({ lat, long, zoom = 7 }) {
                wait(() => this.jeomap?.map, () => {
                    this.jeomap.map.flyTo({ center: [+long, +lat], zoom: +zoom })
                })
            },
            updateWPTotalPages () {
                if (window.lastGetRequestHeader && typeof window.lastGetRequestHeader.get == 'function') {
                    if (window.lastGetRequestHeader.get('X-WP-TotalPages')) {
                        this.wpApiTotalPages = parseInt(window.lastGetRequestHeader.get('X-WP-TotalPages'))
                    }
                }
            },
            openNews (postId) {

                this.clearSelectedNews()
                this.view = 'news'
                
                let newsElem = document.querySelector(`[data-id="${postId}"]`)
                if (newsElem == null) {
                    // if no element exists, load it!
                    this.fetchUniqueNews(postId, () => {
                        this.$nextTick(() => {
                            let newsElem = document.querySelector(`[data-id="${postId}"]`)
                            scrollIntoView(newsElem)
                            newsElem.classList.add('selected')
                        })
                    })
                    return
                }
                this.$nextTick(() => {
                    scrollIntoView(newsElem)
                    newsElem.classList.add('selected')
                    const selectedNews = this.news.find(post => post.id == postId)
                    const newsState = stateCodeByName(selectedNews.meta._related_point[0]._geocode_region_level_2)

                    if (newsState) {
                        this.jeomap.map.setFilter('uf-brasil', ['==', ['get', 'UF_05'], newsState])
                        this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'visible')
                    } else {
                        this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                    }

                })
            },
            setMapObject () {
                
                const mapEl = document.querySelector('.jeomap')
                const uuid = mapEl.dataset['uui_id']
                
                if( window.jeomaps !== undefined ){

                    this.jeomap = window.jeomaps[uuid]
                    window.dashboardJeoMap = this.jeomap

                    if( window.innerWidth >= 900 ){
                        this.jeomap.map.scrollZoom.enable()
                        this.jeomap.map.dragPan.enable()
                        this.jeomap.map.touchZoomRotate.enable()
                        this.jeomap.map.dragRotate.enable()
                    } 
                    else {
                        this.jeomap.map.scrollZoom.disable()
                        this.jeomap.map.dragPan.disable()
                        this.jeomap.map.touchZoomRotate.disable()
                        this.jeomap.map.dragRotate.disable()
                    }
                
                }

            },
            setMapEvents () {

                if( this.jeomap !== undefined ){

                    this.jeomap.map.on('click', 'unclustered-points', (e) => {
                        this.openNews(e.features[0].properties.id)
                    })
                    this.jeomap.map.on('load', (map) => {
                        // hide all states
                        this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                        this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'none')
                        this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'none')
                    });

                }
                
                document.body.addEventListener('jeo-open-spiderifier-pin', (e) => {
                    this.openNews(e.detail.id)
                });

            },
            loadMore (e) {
                e.preventDefault()
                const nextPage = this.currentFetchNewsPage + 1
                this.fetchNewsByPage(this.filters.estado, nextPage)
            },
            toggleFilters () {
                this.showFilters = !this.showFilters
            },
            closeFilters(){
                this.showFilters = false;
            },
            openFilters(){
                this.showFilters = true;
            }
        },
    }
</script>
