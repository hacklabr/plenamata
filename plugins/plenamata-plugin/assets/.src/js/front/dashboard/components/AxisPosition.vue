<template>
    <svg class="dashboard__axis-position" v-show="shown" preserveAspectRatio="none" viewBox="0 0 100% 8" xmlns="http://www.w3.org/2000/svg">
        <rect ref="scroll" x="0" y="0" rx="5" ry="5" height="10" width="100%"/>
        <rect :x="x" y="0" rx="5" ry="5" height="10" :width="width"/>
    </svg>
</template>

<script>
    import Hammer from 'hammerjs'

    export default {
        name: 'AxisPosition',
        props: {
            chart: { type: Object, default: null, },
            end: { type: Number, required: true },
            max: { type: Number, required: true },
            min: { type: Number, default: 0 },
            start: { type: Number, required: true },
        },
        computed: {
            shown () {
                return ((this.end - this.start) / (this.max - this.min)) < 0.999
            },
            step () {
                return 100 / this.window
            },
            width () {
                return `${100 * ((this.end - this.start) / (this.max - this.min))}%`
            },
            window () {
                return Math.max(this.max - this.min, 1)
            },
            x () {
                return `${this.step * (this.start - this.min)}%`
            },
        },
        mounted () {
            const hammer = new Hammer.Manager(this.$refs.scroll, {
                recognizers: [
                    [Hammer.Pan, { direction: Hammer.DIRECTION_HORIZONTAL }],
                ],
            })

            let deltaX = 0
            hammer.on('pan', (event) => {
                this.chart.chartInstance.pan({ x: deltaX - event.deltaX, y: 0 })
                deltaX = event.deltaX
            })
            hammer.on('panend', () => {
                deltaX = 0
            })
        },
    }
</script>
