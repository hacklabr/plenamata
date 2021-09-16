<template>
    <div class="dashboard-panel__measure">
        <span class="dashboard-panel__icon" aria-hidden="true" v-if="icon">
            <img :src="`${$dashboard.pluginUrl}assets/build/img/${icon}`">
        </span>
        <span class="dashboard-panel__number">{{ roundNumber(number) }}</span>
        <span class="dashboard-panel__unit"><slot name="unit"/></span>
    </div>
</template>

<script>
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'DashboardMeasure',
        props: {
            icon: { type: String, default: '' },
            number: { type: Number, required: true },
        },
        methods: {
            roundNumber,
        }
    }
</script>
