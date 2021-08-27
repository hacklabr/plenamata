export function vModel (prop, event = `update:${prop}`) {
    return {
        get () {
            return this[prop]
        },
        set (value) {
            this.$emit(event, value)
        }
    }
}
