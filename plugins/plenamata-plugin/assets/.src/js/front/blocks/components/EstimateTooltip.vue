<template>
    <Tooltip :alt="__('Understand the calculus', 'plenamata')">
        <a :href="$dashboard.explainerUrl" target="_blank">
            {{ __('Understand the calculus', 'plenamata') }}
        </a>
    </Tooltip>
</template>

<script>
    import Tooltip from '../../dashboard/components/Tooltip.vue'

    export default {
        name: 'EstimateTooltip',
        components: {
            Tooltip,
        },
    }
</script>
