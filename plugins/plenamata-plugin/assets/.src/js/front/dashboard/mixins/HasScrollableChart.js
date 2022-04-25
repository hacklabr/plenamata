export default {
    data () {
        return {
            scroll: {
                start: 0,
                end: 0,
            },
        }
    },
    methods: {
        onChartPan ({ chart }) {
            const { min, max } = chart.scales.x
            this.scroll = { start: min, end: max }
        },
    },
}
