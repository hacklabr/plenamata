<template>
    <div class="dashboard-panel__measure">
        <span class="dashboard-panel__icon" aria-hidden="true" v-if="icon">
            <img :src="iconUrl">
        </span>
        <span class="dashboard-panel__number">{{ humanNumber(number) }}</span>
        <span class="dashboard-panel__unit"><slot name="unit"/></span>
    </div>
</template>

<script>
    import { humanNumber } from '../../utils/filters'

    export default {
        name: 'DashboardMeasure',
        props: {
            icon: { type: String, default: '' },
            number: { type: Number, required: true },
        },
        computed: {
            iconUrl () {
                if (!this.icon) {
                    return ''
                }
                return `${window.PlenamataDashboard.pluginUrl}assets/build/img/${this.icon}`
            },
        },
        methods: {
            humanNumber,
        }
    }
</script>
