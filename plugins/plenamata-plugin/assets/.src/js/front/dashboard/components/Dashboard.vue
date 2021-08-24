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
                    <section class="dashboard-panel" >
                        <main>
                            <h2>
                                {{ sprintf(__('Estimativa de árvores derrubadas em %s', 'plenamata'), 2021) }}
                            </h2>
                            <div class="dashboard-panel__measure">
                                <span class="dashboard-panel__icon" aria-hidden="true">
                                    <img :src="pluginUrl + 'assets/build/img/tree-icon.svg'">
                                </span>
                                <span class="dashboard-panel__number">
                                    {{ trees | humanNumber }}
                                </span>
                                <span class="dashboard-panel__unit">
                                    {{ __( 'árvores', 'plenamata' ) }}
                                </span>
                            </div>
                            <div class="dashboard-panel__meaning">
                                {{ __('estimativa média de', 'plenamata') }} {{ treesPerMinute | roundNumber }} {{ __('árvores por minuto', 'plenamata') }}
                            </div>
                        </main>
                        <footer>
                            Fonte: INPE/DETER • Última atualização: 19.07.2021 com alertas detectados até 09.07.2021
                        </footer>
                    </section>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import { DateTime, Interval } from 'luxon'

    import api from '../../utils/api'
    import { humanNumber, roundNumber } from '../../utils/filters'

    export default {
        name: 'Dashboard',
        filters: {
            humanNumber,
            roundNumber,
        },
        data () {
            const now = DateTime.now()
            const startOfYear = now.startOf('year')

            return {
                data: {
                    deterStates: [],
                },
                date: {
                    now,
                    startOfYear,
                    year: now.year,
                },
                state: '',
                view: 'data',
            }
        },
        computed: {
            pluginUrl () {
                return window.PlenamataDashboard.pluginUrl
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
            minutes () {
                return Interval.fromDateTimes(this.date.startOfYear, this.date.now).length('minutes')
            },
            trees () {
                if (this.state) {
                    const state = this.data.deterStates.find((state) => state.uf === this.state)
                    return state.num_arvores || 0
                } else {
                    return this.data.deterStates.reduce((acc, state) => acc + (state.num_arvores || 0), 0)
                }
            },
            treesPerMinute () {
                return this.trees / this.minutes
            },
        },
        created () {
            const { now, startOfYear } = this.date

            api.get(`deter/estados?data1=${startOfYear.toISODate()}&data2=${now.toISODate()}`).then((data) => {
                this.data.deterStates = data
            })
        },
    }
</script>
