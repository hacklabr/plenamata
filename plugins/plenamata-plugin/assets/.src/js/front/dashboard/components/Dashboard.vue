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
                    <FelledTreesThisYear/>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import FelledTreesThisYear from './FelledTreesThisYear.vue'

    export default {
        name: 'Dashboard',
        components: {
            FelledTreesThisYear,
        },
        data () {
            return {
                state: '',
                view: 'data',
            }
        },
        computed: {
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
        },
    }
</script>
