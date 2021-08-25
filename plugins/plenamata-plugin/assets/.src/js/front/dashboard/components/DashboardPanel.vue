<template>
    <section class="dashboard-panel" >
        <main>
            <h2><slot name="title"/></h2>
            <slot name="measure"/>
            <div class="dashboard-panel__meaning"><slot name="meaning"/></div>
        </main>
        <footer><slot name="footer"/></footer>
    </section>
</template>

<script>
    export default {
        name: 'DashboardPanel',
    }
</script>
