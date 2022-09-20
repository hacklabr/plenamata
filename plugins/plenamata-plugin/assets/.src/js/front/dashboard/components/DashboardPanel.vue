<template>
    <section class="dashboard-panel" :class="type">
        <main>
            <em v-if="type=='measure'" class="estimativa"><slot name="reference"/></em>
            <header>
                <span class="dashboard-panel__icon" aria-hidden="true" v-if="icon">
                    <img :src="`${$plenamata.pluginUrl}assets/build/img/${icon}`">
                </span>
                <h2><slot name="title"/></h2>
                <slot name="tooltip"/>
            </header>
            <template v-if="type === 'measure'">
                <slot name="measure"/>
            </template>
            <template v-else-if="type === 'chart'">
                <div class="dashboard-panel__filters"><slot name="filters"/></div>
                <slot name="chart"/>
            </template>
        </main>
        <footer>
            <div v-if="type=='measure'" class="dashboard-panel__meaning">
                <img v-if="icon2" :src="`${$plenamata.pluginUrl}assets/build/img/${icon2}`">
                <em><slot name="meaning"/></em>
            </div>
            <span class="source">
                <slot name="source"/>
            </span>
        </footer>
    </section>
</template>

<script>
    export default {
        name: 'DashboardPanel',
        props: {
            icon: { type: [Boolean, String], default: false },
            icon2: { type: [Boolean, String], default: false },
            type: { type: String, default: 'measure' },
        },
    }
</script>
