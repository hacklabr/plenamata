export default {
    data () {
        return {
            scrolled: false,
            scroll: {
                start: 0,
                end: 0,
            },
        }
    },
    watch: {
        filterKey: {
            handler () {
                this.scrolled = this.chartOptions.scales.x.min === 0
            },
            immediate: true,
        },
    },
    methods: {
        onChartPan ({ chart }) {
            const { min, max } = chart.scales.x
            this.scroll = { start: min, end: max }
            if (!this.scrolled) {
                this.scrolled = true
            }
        },
    },
}
