<template>
    <VTooltip placement="top" theme="dropdown" :triggers="['click', 'focus']">
        <img :src="`${$plenamata.pluginUrl}assets/build/img/info-circle-icon.svg`" :alt="alt">
        <template #popper>
            <slot/>
        </template>
    </VTooltip>
</template>

<script>
    export default {
        name: 'Tooltip',
        props: {
            alt: { type: String, required: true },
        },
    }
</script>
