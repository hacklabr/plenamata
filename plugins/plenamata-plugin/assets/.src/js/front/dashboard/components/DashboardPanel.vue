<template>
    <section class="dashboard-panel" >
        <main>
            <h2><slot name="title"/></h2>
            <template v-if="type === 'measure'">
                <slot name="measure"/>
                <div class="dashboard-panel__meaning"><slot name="meaning"/></div>
            </template>
        </main>
        <footer><slot name="footer"/></footer>
    </section>
</template>

<script>
    export default {
        name: 'DashboardPanel',
        props: {
            type: { type: String, default: 'measure' },
        },
    }
</script>
