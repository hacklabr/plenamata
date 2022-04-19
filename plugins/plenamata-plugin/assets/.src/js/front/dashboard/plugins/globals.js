export default {
    install (Vue) {
        Vue.prototype.$plenamata = window.PlenamataPlugin
        Vue.prototype.$dashboard = window.PlenamataDashboard
    }
}
