<template>
    <div class="dashboard">
        <header class="dashboard__header">
            <div class="container">
                <div class="dashboard__filter-toggle" :class="{ '-on': showFilters }">
                    <em><strong>{{ __('Forestry Dashboard', 'plenamata') }}</strong> <span>-</span> {{ __('Legal Amazon',
                        'plenamata') }}</em>
                    <button type="button" class="clean green" @click="openFilters" @keypress.enter="openFilters"><span>{{
                        _x('Filter', 'verb', 'plenamata') }}</span></button>
                </div>
                <form :class="{ '-hidden': !showFilters }">
                    <header>
                        <em>{{ __('Filtrar por', 'plenamata') }}:</em>
                        <button type="button" class="close" title="Fechar" @click="closeFilters"
                            @keypress.enter="closeFilters"></button>
                    </header>
                    <span class="fields">
                        <Dropdown id="select-estados" icon="map-states" keyId="uf" keyLabel="name"
                            :activeField.sync="activeField" :options="states" :placeholder="__('All states', 'plenamata')"
                            :title="__('All states', 'plenamata')" v-model="filters.estado" />
                        <Dropdown id="select-municipios" icon="map-cities" keyId="mun_geo_cod" keyLabel="municipio"
                            :activeField.sync="activeField" :options="data.municipalities || []"
                            :placeholder="municipalitiesPlaceholder"
                            :title="__('All municipalities', 'plenamata')" v-model="filters.municipio"
                            :onEmptyClick="openEstados" />
                        <Dropdown id="select-land" icon="lands" keyId="code" keyLabel="ti" :activeField.sync="activeField"
                            :options="tis" :placeholder="__('All ILs', 'plenamata')"
                            :title="__('Indigenous Land', 'plenamata')" v-model="filters.ti">
                            <template #tooltip>
                                <Tooltip :alt="__('Indigenous Land', 'plenamata')">
                                    <a :href="$dashboard.tiLink" target="_blank">{{ __('Indigenous Land', 'plenamata')
                                    }}</a>
                                </Tooltip>
                            </template>
                        </Dropdown>
                        <Dropdown id="select-unit" icon="units" keyId="code" keyLabel="uc" :activeField.sync="activeField"
                            :options="ucs" :placeholder="__('All CUs', 'plenamata')"
                            :title="__('Conservation Unit', 'plenamata')" v-model="filters.uc">
                            <template #tooltip>
                                <Tooltip :alt="__('Conservation Unit', 'plenamata')">
                                    <a :href="$dashboard.ucLink" target="_blank">{{ __('Conservation Unit', 'plenamata')
                                    }}</a>
                                </Tooltip>
                            </template>
                        </Dropdown>
                        <Dropdown id="select-year" icon="period" keyId="key" keyLabel="label"
                            :activeField.sync="activeField" :options="range" :placeholder="__('All periods', 'plenamata')"
                            :title="__('Select period', 'plenamata')" v-model="filters.year" />
                    </span>
                    <span class="controls">
                        <span></span>
                        <!-- <button type="button" class="apply" :title="__('Aplicar filtro', 'plenamata')">{{ __('Apply filters', 'plenamata') }}</button> -->
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
                        <em><strong>{{ __('Forestry Dashboard', 'plenamata') }}</strong> - {{ __('Legal Amazon',
                            'plenamata') }}</em>
                        <p>{{ sprintf(__('Estimates of trees cut down in %s', 'plenamata'), year) }}</p>
                    </header>

                    <div v-if="displayedYear === actualYear && actualYear === estimateYear" class="squareds-items">
                        <FelledTreesThisYear :filters="filters" :minutes="minutes" :trees="trees" />
                        <TotalDeforestationThisYear :activeField.sync="activeField" :areaKm2="areaKm2" :date="date"
                            :filters="filters" :unit.sync="unit" :updated="updated" :year="year" />
                        <DeforestationSpeedThisYear :activeField.sync="activeField" :areaKm2="areaKm2" :days="days"
                            :minutes="minutes" :trees="trees" :unit.sync="unit" :year="year" />
                        <DeforestedAreaLastWeek :activeField.sync="activeField" :lastWeek="lastWeek" :unit.sync="unit"
                            :updated="updated" />
                    </div>

                    <DeforestationCharts :filteredYear="filters.year" :filters="filters" />
                    <WeeklyDeforestationEvolution :activeField.sync="activeField" :date="date" :filters="filters"
                        :source.sync="source" :unit.sync="unit" :updated="updated" :year.sync="filters.year" />
                    <MonthlyDeforestationEvolution :activeField.sync="activeField" :date="date" :filters="filters"
                        :source.sync="source" :unit.sync="unit" :updated="updated" />
                    <YearlyDeforestationEvolutionDeter :activeField.sync="activeField" :date="date" :filters="filters"
                        :unit.sync="unit" :updated="updated" />
                    <YearlyDeforestationEvolutionProdes :activeField.sync="activeField" :filters="filters" :unit.sync="unit"
                        :year="Number(year)" />
                </div>

                <div class="dashboard__news" v-else-if="view === 'news'">
                    <DashboardNewsCard v-for="post of news" :key="post.id" :post="post" />
                    <p v-if="news.length === 0">{{ __('No news to be shown.', 'plenamata') }}</p>
                    <a href="#" class="dashboard__loadmore" @click="loadMore($event)"
                        v-if="currentFetchNewsPage < wpApiTotalPages">
                        {{ loadMoreText }}
                    </a>
                </div>
            </div>
        </main>

        <div class="dashboard__map" v-once>
            <div ref="map" />
        </div>
    </div>
</template>

<script>
import { DateTime, Interval } from 'luxon'
import scrollIntoView from 'scroll-into-view'

import DashboardNewsCard from './DashboardNewsCard.vue'
import DeforestationCharts from '../../blocks/components/DeforestationCharts.vue'
import DeforestationSpeedThisYear from './DeforestationSpeedThisYear.vue'
import DeforestedAreaLastWeek from './DeforestedAreaLastWeek.vue'
import Dropdown from './Dropdown.vue'
import FelledTreesThisYear from './FelledTreesThisYear.vue'
import MonthlyDeforestationEvolution from './MonthlyDeforestationEvolution.vue'
import Tooltip from './Tooltip.vue'
import TotalDeforestationThisYear from './TotalDeforestationThisYear.vue'
import WeeklyDeforestationEvolution from './WeeklyDeforestationEvolution.vue'
import YearlyDeforestationEvolutionDeter from './YearlyDeforestationEvolutionDeter.vue'
import YearlyDeforestationEvolutionProdes from './YearlyDeforestationEvolutionProdes.vue'
import { formatCUName, getAreaKm2, getTrees, localeSortBy, wait } from '../../utils'
import { fetchConservationUnits, fetchDeterData, fetchIndigenousLands, fetchLastDate, fetchMunicipalities, fetchNews, fetchUniqueNews } from '../../utils/api'
import { firstValue, shortDate, stateCodeByName } from '../../utils/filters'
import { getEstimateYear } from '../../utils/estimates'
import { clearSelectedNews } from '../../utils/mapInteractions'
import { sprintf, __ } from '../../dashboard/plugins/i18n'

export default {
    name: 'Dashboard',
    components: {
        DashboardNewsCard,
        DeforestationCharts,
        DeforestationSpeedThisYear,
        DeforestedAreaLastWeek,
        Dropdown,
        FelledTreesThisYear,
        MonthlyDeforestationEvolution,
        Tooltip,
        TotalDeforestationThisYear,
        WeeklyDeforestationEvolution,
        YearlyDeforestationEvolutionDeter,
        YearlyDeforestationEvolutionProdes,
    },
    data() {
        return {
            actualYear: DateTime.now().year,
            activeField: '',
            currentFetchNewsPage: 1,
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
            lastUpdate: null,
            lastWeek: null,
            loadMoreText: __('Load more', 'plenamata'),
            news: [],
            showFilters: false,
            source: 'deter',
            thisYear: null,
            unit: 'ha',
            view: 'data',
            wpApiTotalPages: 999999,
            year: DateTime.now().year,
            municipalitiesPlaceholder: __('Select a state', 'plenamata')
        }
    },
    computed: {
        areaKm2() {
            if (!this.thisYear) {
                return 0
            }
            return getAreaKm2(this.thisYear)
        },
        date() {
            if (!this.lastUpdate) {
                return null
            }
            return DateTime.fromISO(this.lastUpdate.deter_last_date, { zone: 'utc' })
        },
        days() {
            return this.daysThisYear.count('days')
        },
        daysThisYear() {
            return Interval.fromDateTimes(this.date.startOf('year'), this.date)
        },
        displayedYear() {
            return (this.filters.year === '') ? this.actualYear : this.filters.year
        },
        estimateYear() {
            if (!this.lastUpdate) {
                return null
            }
            return getEstimateYear(this.year, this.lastUpdate.deter_last_date, { DateTime })
        },
        minutes() {
            return this.daysThisYear.count('minutes')
        },
        startOfYear() {
            if (!this.date) {
                return null
            }
            return this.date.startOf('year')
        },
        states() {
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
            const maxYear = new Date().getFullYear()
            const years = {}
            for (let year = 2016; year <= maxYear; year++) {
                years[year] = { key: year, label: year }
            }
            return years
        },
        tis() {
            return this.data.tis.slice(0).sort(localeSortBy(ti => ti.ti))
        },
        trees() {
            if (!this.thisYear) {
                return 0
            }
            return getTrees(this.thisYear)
        },
        ucs() {
            return this.data.ucs.map(({ uc: name, ...uc }) => ({ uc: formatCUName(name), ...uc })).sort(localeSortBy(uc => uc.uc))
        },
        updated() {
            return {
                deter: shortDate(this.lastUpdate?.deter_last_date).replaceAll('/', '.'),
                sync: shortDate(this.lastUpdate?.last_sync).replaceAll('/', '.'),
            }
        },
    },
    watch: {
        filters: {
            async handler() {
                this.closeFilters()


                await this.fetchData()

                const queryParams = new URLSearchParams(window.location.search);
                queryParams.set('filter_estado', this.filters.estado);
                queryParams.set('filter_municipio', this.filters.municipio);
                queryParams.set('filter_ti', this.filters.ti);
                queryParams.set('filter_uc', this.filters.uc);
                queryParams.set('filter_year', this.filters.year);
                window.history.replaceState({}, '', `${window.location.pathname}?${queryParams.toString()}`);

                if (!this.filters.estado && !this.filters.municipio && !this.filters.ti && !this.filters.uc) {
                    wait(() => this.jeomap.map?.isStyleLoaded(), () => {
                        this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'none')
                        this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                        this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'none')
                    })
                }

            },
            deep: true,
        },
        async 'filters.estado'() {
            this.filters.municipio = ''
            if (this.filters.estado) {
                this.municipalitiesPlaceholder = __('All municipalities', 'plenamata');
                this.data.municipalities = await fetchMunicipalities(this.filters.estado)
                this.filters.ti = ''
                this.filters.uc = ''
                this.jeomap?.map.setFilter('uf-brasil', ['==', ['get', 'UF_05'], this.filters.estado])
                this.jeomap?.map.setLayoutProperty('uf-brasil', 'visibility', 'visible')
                const { lat, long, zoom } = this.states[this.filters.estado]
                this.flyTo({ lat, long, zoom: zoom || JeoMap.getArg('initial_zoom') })
            } else {
                this.municipalitiesPlaceholder = __('Select a state', 'plenamata');
                this.data.municipalities = []
                this.jeomap?.map.setFilter('uf-brasil', null)
                this.jeomap?.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
            }

            await this.fetchNews(this.filters.estado)
        },
        'filters.municipio'() {
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
        'filters.ti'() {
            if (this.filters.ti) {
                this.filters.estado = ''
                this.filters.municipio = ''
                this.filters.uc = ''
                wait(() => this.jeomap.map?.isStyleLoaded(), () => {
                    this.jeomap.map.setFilter('tis-brasil', ['==', ['get', 'terrai_cod'], +this.filters.ti])
                    this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'visible')
                    const { lat, long } = this.data.tis.find(item => item.code == this.filters.ti)
                    this.flyTo({ lat, long })
                })
            } else {
                this.jeomap.map.setFilter('tis-brasil', null)
                this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'none')
            }
        },
        'filters.uc'() {
            if (this.filters.uc) {
                this.filters.estado = ''
                this.filters.municipio = ''
                this.filters.ti = ''
                wait(() => this.jeomap.map?.isStyleLoaded(), () => {
                    this.jeomap.map.setFilter('ucs-brasil', ['==', ['get', 'id'], +this.filters.uc])
                    this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'visible')
                    const { lat, long } = this.data.ucs.find(item => item.code == this.filters.uc)
                    this.flyTo({ lat, long })
                })

            } else {
                this.jeomap.map.setFilter('ucs-brasil', null)
                this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'none')
            }
        }
    },
    async created() {
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
        const queryParams = new URLSearchParams(window.location.search);

        let _filters = {};
        _filters.estado = queryParams.get('filter_estado') || '';
        _filters.municipio = queryParams.get('filter_municipio') || '';
        _filters.ti = queryParams.get('filter_ti') || '';
        _filters.uc = queryParams.get('filter_uc') || '';
        _filters.year = queryParams.get('filter_year') || '';
        _filters.lat = queryParams.get('lat') || '';
        _filters.long = queryParams.get('lng') || '';
        _filters.zoom = queryParams.get('zoom') || '';

        if (_filters.municipio != '' || _filters.estado != '') {
            this.filters.estado = _filters.estado;
            wait(() => this.data.municipalities, () => {
                this.filters.municipio = _filters.municipio;
            });
        }
        if (_filters.ti != '') {
            wait(() => this.data.tis, () => {
                this.filters.ti = _filters.ti;
            });
        }
        if (_filters.uc != '') {
            wait(() => this.data.ucs, () => {
                this.filters.uc = _filters.uc;
            });
        }

        if (_filters.year != '') {
            wait(() => this.data, () => {
                this.filters.year = _filters.year;
                this.year = _filters.year;
            });
        }

        if (_filters.long != '' && _filters.lat != '' && _filters.zoom != '') {
            wait(() => this.jeomap?.map?.isStyleLoaded(), () => {
                this.flyTo(_filters);
            });
        } else {
            wait(() => this.jeomap?.map?.isStyleLoaded(), () => {
                this.flyTo({ lat: this.jeomap.getArg('center_lat'), long: this.jeomap.getArg('center_lon'), zoom: this.jeomap.getArg('initial_zoom') })
            });
        }

        wait(() => this.jeomap?.map?.isStyleLoaded(), () => {
            const legend = document.querySelectorAll('.legend-container .legends-wrapper > div span.legend-single-title');
            if (legend) {
                legend.forEach((element) => {
                    const currentHTML = element.innerHTML;
                    element.innerHTML = currentHTML.replace(/INPE/g, '<a href="https://www.gov.br/inpe/pt-br" target="_blank">INPE</a>')
                        .replace(/PRODES/g, '<a href="http://www.obt.inpe.br/OBT/assuntos/programas/amazonia/prodes" target="_blank">PRODES</a>')
                        .replace(/DETER/g, '<a href="http://www.obt.inpe.br/OBT/assuntos/programas/amazonia/deter/deter" target="_blank">DETER</a>');
                });

            }

        });

    },
    mounted() {
        const mapEl = document.querySelector('.jeomap')
        this.$refs.map.appendChild(mapEl)
        this.setMapObject()
        this.setMapEvents()

    },
    beforeUpdate() {
        this.setMapObject()
    },
    methods: {
        addLayerDates() {
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
        clearFilters() {
            this.closeFilters()
            this.activeField = ''
            this.filters = {
                estado: '',
                municipio: '',
                ti: '',
                uc: '',
                year: this.actualYear,
            }
        },
        clearSelectedNews,
        closeFilters() {
            this.showFilters = false
        },
        async fetchData() {
            if (!this.date) {
                return;
            }

            const weekAgo = this.date.minus({ days: 6 })

            const [thisYear, lastWeek] = await Promise.all([
                fetchDeterData({ ...this.filters, data1: this.startOfYear.toISODate(), data2: this.date.toISODate() }),
                fetchDeterData({ ...this.filters, data1: weekAgo.toISODate(), data2: this.date.toISODate() }),
            ])
            this.thisYear = firstValue(thisYear)
            this.lastWeek = firstValue(lastWeek)
            this.setMapObject()
        },
        async fetchNews(state = '') {
            const news = await fetchNews(state)
            this.news = news
            this.currentFetchNewsPage = 1
            this.updateWPTotalPages()
        },
        async fetchNewsByPage(state = '', pageNum = 1) {
            this.loadMoreText = __('Loading...', 'plenamata')
            const news = await fetchNews(state, pageNum)
            this.news = this.news = [...this.news, ...news]
            this.currentFetchNewsPage = pageNum
            this.updateWPTotalPages()
            this.loadMoreText = __('Load more', 'plenamata')
        },
        async fetchUniqueNews(postId, callback) {
            let news = await fetchUniqueNews(postId)
            let found = this.news.find(element => element.id === postId)
            if (found) {
                return
            }
            this.news.unshift(news)
            const newsState = stateCodeByName(news.meta._related_point[0]._geocode_region_level_2)

            if (newsState) {
                this.jeomap.map.setFilter('uf-brasil', ['==', ['get', 'UF_05'], newsState])
                this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'visible')
            } else {
                this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
            }
            if (typeof callback === 'function') {
                callback(news)
            }
        },
        flyTo({ lat, long, zoom = 7 }) {
            wait(() => this.jeomap?.map, () => {
                this.jeomap.map.flyTo({ center: [+long, +lat], zoom: +zoom })
            })
        },
        loadMore(e) {
            e.preventDefault()
            const nextPage = this.currentFetchNewsPage + 1
            this.fetchNewsByPage(this.filters.estado, nextPage)
        },
        openFilters() {
            this.showFilters = true
        },
        openNews(postId) {
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
        setMapObject() {
            const mapEl = document.querySelector('.jeomap')
            const uuid = mapEl.dataset['uui_id']

            if (window.jeomaps !== undefined) {
                this.jeomap = window.jeomaps[uuid]
                window.dashboardJeoMap = this.jeomap

                if (window.innerWidth >= 900) {
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
            }
        },
        setMapEvents() {
            document.body.addEventListener('jeo-open-spiderifier-pin', (e) => {
                this.openNews(e.detail.id)
            })

            wait(() => this.jeomap?.map, () => {

                this.jeomap.map.on('click', 'unclustered-points', (e) => {
                    this.openNews(e.features[0].properties.id)
                })

                this.jeomap.map.on('moveend', () => {
                    const { lng, lat } = this.jeomap.map.getCenter();
                    const zoom = this.jeomap.map.getZoom();

                    const queryParams = new URLSearchParams(window.location.search);
                    queryParams.set('lat', lat);
                    queryParams.set('lng', lng);
                    queryParams.set('zoom', zoom);

                    window.history.replaceState({}, '', `${window.location.pathname}?${queryParams.toString()}`);

                });

                wait(() => this.jeomap?.map?.isStyleLoaded(), () => {
                    this.jeomap.map.setLayoutProperty('ucs-brasil', 'visibility', 'none')
                    this.jeomap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                    this.jeomap.map.setLayoutProperty('tis-brasil', 'visibility', 'none')

                })

            })
        },

        toggleFilters() {
            this.showFilters = !this.showFilters
        },
        updateWPTotalPages() {
            if (window.lastGetRequestHeader && typeof window.lastGetRequestHeader.get == 'function') {
                if (window.lastGetRequestHeader.get('X-WP-TotalPages')) {
                    this.wpApiTotalPages = parseInt(window.lastGetRequestHeader.get('X-WP-TotalPages'))
                }
            }
        },
        openEstados() {

        },
    },
}
</script>
