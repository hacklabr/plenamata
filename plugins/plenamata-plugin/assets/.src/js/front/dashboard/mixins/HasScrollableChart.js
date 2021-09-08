export default {
    data () {
        return {
            scroll: {
                start: 0,
                end: 0,
            },
        }
    },
    computed: {
        scrollPosition () {
            if (this.scroll.start === 0 && this.scroll.end === 0) {
                const { min, max} = this.chartOptions.scales.x
                return { start: min, end: max }
            } else {
                return { ...this.scroll }
            }
        },
    },
    methods: {
        onChartPan ({ chart }) {
            const { min, max } = chart.scales.x
            this.scroll = { start: min, end: max }
        },
    },
}
