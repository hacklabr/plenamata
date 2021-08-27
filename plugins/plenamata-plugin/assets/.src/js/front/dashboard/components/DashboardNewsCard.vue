<template>
    <article class="dashboard-news">
        <div class="dashboard-news__image">
            <img v-if="post.plenamata_thumbnail" :src="post.plenamata_thumbnail" alt="">
        </div>
        <div>
            <h2><a :href="post.link">{{ post.title.rendered }}</a></h2>
            <div>
                <span class="dashboard-news__date">{{ longDate(post.date) }}</span>
                <span class="dashboard-news__source" v-if="externalSource"><img :src="`${$dashboard.pluginUrl}assets/build/img/external-source-icon.svg`" alt=""> {{ externalSource }}</span>
            </div>
        </div>
    </article>
</template>

<script>
    import { __ } from '@wordpress/i18n'

    import { longDate } from '../../utils/filters'

    export default {
        name: 'DashboardNewsCard',
        props: {
            post: { type: Object, required: true },
        },
        computed: {
            externalSource () {
                const link = this.post.meta?.['external-source-link']
                if (link) {
                    if (link.includes('infoamazonia.org')) {
                        return 'InfoAmazonia'
                    } else if (link.includes('mapbiomas.org')) {
                        return 'MapBiomas'
                    }
                    return __( 'External link', 'plenamata' )
                }
                return false
            },
        },
        methods: {
            longDate,
        },
    }
</script>
