<template>
    <div class="dashboard__scroll-guard" aria-hidden="true" @click="deactivate" @touchstart="deactivate">
        <slot/>
        <div class="dashboard__scroll-guard-tag" v-show="!scrolled && active">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                    <path fill="currentColor" d="m236.5 136.5l-32 32a12.1 12.1 0 0 1-17 0a12 12 0 0 1 0-17L199 140H57l11.5 11.5a12 12 0 0 1 0 17a12.1 12.1 0 0 1-17 0l-32-32a12 12 0 0 1 0-17l32-32a12 12 0 0 1 17 17L57 116h142l-11.5-11.5a12 12 0 0 1 17-17l32 32a12 12 0 0 1 0 17Z"/>
                </svg>
                <span>{{ __('Drag to see more', 'plenamata') }}</span>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ScrollGuard',
        props: {
            scrolled: { type: Boolean, default: false },
        },
        data () {
            return {
                active: true,
            }
        },
        watch: {
            scrolled () {
                this.active = true
            },
        },
        methods: {
            deactivate () {
                if (this.active) {
                    this.active = false
                }
            },
        },
    }
</script>