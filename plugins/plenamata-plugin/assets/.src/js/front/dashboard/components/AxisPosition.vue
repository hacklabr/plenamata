<template>
    <svg class="dashboard__axis-position" preserveAspectRatio="none" viewBox="0 0 100% 8" xmlns="http://www.w3.org/2000/svg">
        <rect x="0" y="0" rx="4" ry="4" height="8" width="100%"/>
        <rect :x="x" y="0" rx="4" ry="4" height="8" :width="width"/>
    </svg>
</template>

<script>
    export default {
        name: 'AxisPosition',
        props: {
            end: { type: Number, required: true },
            max: { type: Number, required: true },
            min: { type: Number, default: 0 },
            start: { type: Number, required: true },
        },
        computed: {
            step () {
                return 100 / Math.max(this.max - this.min, 1)
            },
            width () {
                return `${100 * ((this.end - this.start) / (this.max - this.min))}%`
            },
            x () {
                return `${this.step * (this.start - this.min)}%`
            },
        },
    }
</script>
