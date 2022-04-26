<template>
    <div class="dashboard-panel__measure">
        <span class="dashboard-panel__icon" aria-hidden="true" v-if="icon">
            <img :src="`${$plenamata.pluginUrl}assets/build/img/${icon}`">
        </span>
        <span class="dashboard-panel__number">{{ formattedNumber }}</span>
        <span class="dashboard-panel__unit"><slot name="unit"/> <slot name="tooltip"/></span>
    </div>
</template>

<script>
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'DashboardMeasure',
        props: {
            icon: { type: String, default: '' },
            number: { type: [Number, String], required: true },
        },
        computed: {
            formattedNumber () {
                if (typeof this.number === 'number') {
                    return roundNumber(this.number)
                } else {
                    return this.number
                }
            },
        },
    }
</script>
