<template>
    <article class="dashboard-news" :data-id="post.id" @click="openPin(post, $event)">
        <div class="dashboard-news__image">
            <img v-if="post.plenamata_thumbnail" :src="post.plenamata_thumbnail" alt="">
        </div>
        <div>
            <h2><a :href="post.link" v-html="post.title.rendered"/></h2>
            <div>
                <span class="dashboard-news__date">{{ longDate(post.date) }}</span>
                <span class="dashboard-news__source" v-if="externalSource"><img :src="`${$dashboard.pluginUrl}assets/build/img/external-source-icon.svg`" alt=""> {{ externalSource }}</span>
            </div>
        </div>
    </article>
</template>

<script>
    import { __ } from '../plugins/i18n'
    import { longDate, shortDate, stateCodeByName } from '../../utils/filters'
    import { clearSelectedNews } from '../../utils/mapInteractions'


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
            shortDate,
            clearSelectedNews,
            openPin( post, e ) {

                let newsElem = document.querySelector( '[data-id="' + post.id + '"]' );
                if  ( ! newsElem.classList.contains( 'selected' ) ) {
                    e.preventDefault();
                    this.clearSelectedNews();

                    newsElem.classList.add( 'selected' );
                }


                if( post.meta && post.meta._related_point && post.meta._related_point[0] ) {

                    window.dashboardJeoMap.map.flyTo({
                        center: [post.meta._related_point[0]._geocode_lon, post.meta._related_point[0]._geocode_lat],
                        zoom: 6
                    });
                    let html = '<article class="popup popup-wmt"><div class="popup__date">' + this.shortDate(post.date) + '</div><h2><a href="' + post.link + '">' + post.title.rendered + '</a></h2></article>'

                    const newsState = stateCodeByName( post.meta._related_point[0]._geocode_region_level_2 );

                    if ( newsState ) {
                        window.dashboardJeoMap.map.setFilter('uf-brasil', ['==', ['get', 'UF_05'], newsState ]);
                        window.dashboardJeoMap.map.setLayoutProperty('uf-brasil', 'visibility', 'visible')
                    } else {
                        window.dashboardJeoMap.map.setLayoutProperty('uf-brasil', 'visibility', 'none')
                    }

                    setTimeout( () => {
                        let mapPopUp = new mapboxgl.Popup()
                        .setLngLat([post.meta._related_point[0]._geocode_lon, post.meta._related_point[0]._geocode_lat])
                        .setHTML( html )
                        .addTo( window.dashboardJeoMap.map );

                        mapPopUp.on( 'close', () =>  {
                            this.clearSelectedNews()
                            document.querySelectorAll('.mapboxgl-popup').forEach((popup) => popup.remove());
                        });

                    }, 600 )
                }
            },

        },
    }
</script>
