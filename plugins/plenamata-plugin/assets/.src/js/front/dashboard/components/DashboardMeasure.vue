<template>
    <div class="dashboard-panel__measure">
        <span class="dashboard-panel__number">{{ formattedNumber }}</span>
        <span class="dashboard-panel__unit" :class="unitClass"><slot name="unit"/></span>
    </div>
</template>

<script>
    import { roundNumber } from '../../utils/filters'

    export default {
        name: 'DashboardMeasure',
        props: {
            icon: { type: String, default: '' },
            number: { type: [Number, String], required: true },
            unitClass : { type: String, default: '' }
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
