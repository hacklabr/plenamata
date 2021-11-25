<?php
/**
 * PlenamataPlugin frontend part
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

namespace PlenamataPlugin\Front;

use PlenamataPlugin\Plugin;

/**
 * Class Front
 *
 * @since   0.1.0
 *
 * @package PlenamataPlugin\Front
 */
class Front {

	/**
	 * Init hooks
	 *
	 * @since 0.1.0
	 */
	public function hooks(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 50 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 50 );

        //replace theme footer.php
        add_action( 'get_footer', [ $this, 'get_footer' ], 10, 2 );

        //
        add_action( 'before_header', [ $this, 'replace_header'], 10 );
        add_action( 'after_header', [ $this, 'replace_header_close'], 10 );

	}

    /**
     * Register filters
     */
    public function filters(): void {
        add_filter( 'archive_template', [ $this, 'archive_templates' ], 10, 1 );
        add_filter( 'body_class', [ $this, 'body_class' ], 10, 1);
        add_filter( 'document_title_parts', [ $this, 'custom_titles' ], 10, 1 );
        add_filter( 'page_template', [ $this, 'page_templates' ], 10, 1 );
        add_filter( 'single_template', [ $this, 'single_templates' ], 10, 1 );

        // add template file for search after mobile menu
        add_filter( 'wp_nav_menu', [ $this, 'search_mobile'], 50, 2 );

        // change excer_length
        add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 50 );

        // change map markers style
        add_filter( 'jeomap_js_images', [ $this, 'jeo_change_js_image' ], 10, 1 );

        // change map markers style
        add_filter( 'jeomap_js_cluster', [ $this, 'jeo_change_js_cluster' ], 10, 1 );
    }

    /**
     * Change jeomap(mapbox) image and image style
     *
     * @param array $images
     * @return array
     */
    public function jeo_change_js_image( $images ) {
        $images[ '/js/src/icons/news-marker' ][ 'url'] = PLENAMATA_PLUGIN_URL . 'assets/.src/img/pin.png';
        $images[ '/js/src/icons/news-marker' ][ 'icon_size' ] = 0.3;

        $images[ '/js/src/icons/news' ][ 'url'] = PLENAMATA_PLUGIN_URL . 'assets/.src/img/news-icon.png';
        $images[ '/js/src/icons/news' ][ 'icon_size' ] = 0.25;
        $images[ '/js/src/icons/news' ][ 'text_color' ] = '#FFFFFF';

        return $images;
    }

    /**
     * Change jeomap(mapbox) cluster circle-color
     *
     * @param array $cluster
     * @return array
     */
    public function jeo_change_js_cluster( $cluster ) {
        $cluster[ 'circle_color'] = '#523096';
        return $cluster;
    }

    public function custom_titles( array $parts ): array {
        if ( is_post_type_archive( 'verbete' ) ) {
            $parts['title'] = __( 'Glossary', 'plenamata' );
        }

        return $parts;
    }

    /**
     * Change body classes.
     */
    public function body_class( array $classes ): array {
        global $post;

        if ( is_post_type_archive( 'verbete' ) ) {
            return array_merge( $classes, [ 'glossary' ] );
        } else if ( is_singular( 'verbete' ) ) {
            return array_merge( $classes, [ 'glossary', 'glossary-entry' ] );
        }

        return $classes;
    }

	/**
	 * Enqueue styles for the front area.
	 *
	 * @since 0.1.0
	 */
	public function enqueue_styles(): void {
        wp_enqueue_style('jeo-theme-fontawesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css", array(), '5.12.0', 'all');

		wp_enqueue_style(
			'plenamata-plugin',
			PLENAMATA_PLUGIN_URL . 'assets/build/css/main.css',
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Enqueue scripts for the front area.
	 *
	 * @since 0.1.0
	 */
	public function enqueue_scripts(): void {
        $dashboard_i18n = $this->get_dashboard_i18n();

		wp_enqueue_script(
			'plenamata-plugin',
			PLENAMATA_PLUGIN_URL . 'assets/build/js/main.js',
			[],
			Plugin::VERSION,
			true
		);

        wp_localize_script(
            'plenamata-plugin',
            'PlenamataPlugin',
            [
                'i18n' => [
                    '__' => [
                        'close' => __( 'Close', 'plenamata' ),
                        'seeOnGlossary' => __( 'See term on glossary', 'plenamata' ),
                    ]
                ],
                'restUrl' => get_rest_url(),
            ],
        );

        wp_register_script( 'luxon', 'https://unpkg.com/luxon@2/build/global/luxon.min.js', [], false, true );

        wp_register_script(
            'estimatives-area-front-end',
            PLENAMATA_PLUGIN_URL . 'assets/build/js/estimatives-area.js',
            [ 'luxon' ],
            false,
            true
        );

        wp_localize_script( 'estimatives-area-front-end', 'PlenamataDashboard', [
            'i18n' => $dashboard_i18n,
            'language' => apply_filters( 'wpml_current_language', NULL ),
        ] );

        wp_register_script(
            'deforestation-charts-front-end',
            PLENAMATA_PLUGIN_URL . 'assets/build/js/deforestation-charts.js',
            [ 'luxon' ],
            false,
            true
        );

        if ( get_page_template_slug() === 'template-about.php' ) {
            wp_enqueue_script(
                'plenamata-about-page',
                PLENAMATA_PLUGIN_URL . 'assets/build/js/about-page.js',
                [],
                Plugin::VERSION,
                true
            );
        }

        if ( get_page_template_slug() === 'template-dashboard.php' ) {
            $this->register_jeo_assets();

            wp_enqueue_script(
                'plenamata-dashboard',
                PLENAMATA_PLUGIN_URL . 'assets/build/js/dashboard.js',
                [ 'jeo-layer', 'jeo-legend', 'jeo-map', 'layer-type-mapbox', 'plenamata-plugin', 'wp-i18n' ],
                Plugin::VERSION,
                true
            );

            wp_localize_script( 'plenamata-dashboard', 'PlenamataDashboard', [
                'i18n' => $dashboard_i18n,
                'language' => apply_filters( 'wpml_current_language', NULL ),
                'pluginUrl' => PLENAMATA_PLUGIN_URL,
                'restUrl' => get_rest_url(),
            ] );
        }

        if ( get_page_template_slug() === 'template-scoreboard.php' ) {
            wp_enqueue_script( 'estimatives-area-front-end' );
        }
	}

    /**
     * Return JS-side localization strings for charts.
     *
     * @return array
     */
    private function get_dashboard_i18n(): array {
        return [
            '__' => [
                '%s%% decrease compared to last year' => __( '%s%% decrease compared to last year', 'plenamata' ),
                '%s%% increase compared to last year' => __( '%s%% increase compared to last year', 'plenamata' ),
                '%s ha' => __( '%s ha', 'plenamata' ),
                '%s km²' => __( '%s km²', 'plenamata' ),
                'All CUs' => __( 'All CUs', 'plenamata' ),
                'All ILs' => __( 'All ILs', 'plenamata' ),
                'All municipalities' => __( 'All municipalities', 'plenamata' ),
                'All states' => __( 'All states', 'plenamata' ),
                'Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.' => __( 'Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.', 'plenamata' ),
                'Area deforested last week' => __( 'Area deforested last week', 'plenamata' ),
                'Area of deforestation alerts detected last week' => __( 'Area of deforestation alerts detected last week', 'plenamata' ),
                'Clear filters' => __( 'Clear filters', 'plenamata' ),
                'Conservation Unit' => __( 'Conservation Unit', 'plenamata' ),
                'Data' => __( 'Data', 'plenamata' ),
                'Deforestation rate in %s' => __( 'Deforestation rate in %s', 'plenamata' ),
                'during DETER year' => __( 'during DETER year', 'plenamata' ),
                'during PRODES year' => __( 'during PRODES year', 'plenamata' ),
                'estimated average of %s trees per minute' => __( 'estimated average of %s trees per minute', 'plenamata'),
                'External link' => __( 'External link', 'plenamata' ),
                'Forestry Dashboard' => __( 'Forestry Dashboard', 'plenamata' ),
                'hectares' => __( 'hectares', 'plenamata' ),
                'hectares per day' => __( 'hectares per day', 'plenamata' ),
                'Indigenous Land' => __( 'Indigenous Land', 'plenamata' ),
                'km²' => __( 'km²', 'plenamata' ),
                'km² per day' => __( 'km² per day', 'plenamata' ),
                'Load more' => __( 'Load more', 'plenamata' ),
                'Loading...' => __( 'Loading...', 'plenamata' ),
                'Monthly' => __( 'Monthly', 'plenamata' ),
                'Monthly deforestation rate' => __( 'Monthly deforestation rate', 'plenamata' ),
                'Municipality' => __( 'Municipality', 'plenamata' ),
                'News' => __( 'News', 'plenamata' ),
                'No news to be shown.' => __( 'No news to be shown', 'plenamata' ),
                'Period' => __( 'Period', 'plenamata' ),
                'Period: %s' => __( 'Period: %s', 'plenamata' ),
                'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.' => __( 'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata' ),
                'Source: MapBiomas based on average daily deforestation detected by DETER in %s.' => __( 'Source: MapBiomas based on average daily deforestation detected by DETER in %s.', 'plenamata' ),
                'Source: MapBiomas based on DETER/INPE data.' => __( 'Source: MapBiomas based on DETER/INPE data.', 'plenamata' ),
                'Source: PRODES/INPE.' => __( 'Source: PRODES/INPE.', 'plenamata' ),
                'Sources: DETER/INPE and MapBiomas' =>  __( 'Sources: DETER/INPE and MapBiomas', 'plenamata' ),
                'State' => __( 'State', 'plenamata' ),
                'The figures represent deforestation for each year up to %s.' => __( 'The figures represent deforestation for each year up to %s.', 'plenamata' ),
                'Timeframe' => __( 'Timeframe', 'plenamata' ),
                'Total deforestation in %s in the selected territory' => __( 'Total deforestation in %s in the selected territory', 'plenamata' ),
                'Total deforested area in %s (until last week)' => __( 'Total deforested area in %s (until last week)', 'plenamata' ),
                'trees' => __( 'trees', 'plenamata' ),
                'Trees cut down in %s' => __( 'Trees cut down in %s', 'plenamata' ),
                'trees cut sown so far' => __( 'trees cut down so far', 'plenamata' ),
                'trees per day' => __( 'trees per day', 'plenamata' ),
                'Unit' => __( 'Unit', 'plenamatmarkera' ),
                'Week %s' => __( 'Week %s', 'plenamata' ),
                'Weekly' => __( 'Weekly', 'plenamata' ),
                'Weekly and monthly data are from %s.' => __( 'Weekly and monthly data are from %s.', 'plenamata' ),
                'Weekly deforestation rate' => __( 'Weekly deforestation rate', 'plenamata'),
                'Yearly' => __( 'Yearly', 'plenamata' ),
                'Yearly consolidated deforestation rate (PRODES)' => __( 'Yearly consolidated deforestation rate (PRODES)', 'plenamata' ),
                'Yearly deforestation alerts (DETER)' => __( 'Yearly deforestation alerts (DETER)', 'plenamata' ),
            ],
            '_x' => [
                'months' => [
                    'January' => _x( 'January', 'months', 'plenamata' ),
                    'February' => _x( 'February', 'months', 'plenamata' ),
                    'March' => _x( 'March', 'months', 'plenamata' ),
                    'April' => _x( 'April', 'months', 'plenamata' ),
                    'May' => _x( 'May', 'months', 'plenamata' ),
                    'June' => _x( 'June', 'months', 'plenamata' ),
                    'July' => _x( 'July', 'months', 'plenamata' ),
                    'August' => _x( 'August', 'months', 'plenamata' ),
                    'September' => _x( 'September', 'months', 'plenamata' ),
                    'October' => _x( 'October', 'months', 'plenamata' ),
                    'November' => _x( 'November', 'months', 'plenamata' ),
                    'December' => _x( 'December', 'months', 'plenamata' ),
                ],
                'verb' => [
                    'Filter' => _x( 'Filter', 'verb', 'plenamata' ),
                ],
            ],
        ];
    }


    /**
     * Register JEO scripts.
     */
    private function register_jeo_assets(): void {
        wp_enqueue_style( 'mapboxgl', 'https://api.mapbox.com/mapbox-gl-js/v1.4.1/mapbox-gl.css', '1.4.1' );

        wp_register_script( 'mapboxgl-loader', JEO_BASEURL . '/js/build/mapboxglLoader.js', JEO_VERSION );

        wp_register_script( 'jeo-map', JEO_BASEURL . '/js/build/jeoMap.js', [ 'mapboxgl-loader', 'jquery', 'wp-element' ], JEO_VERSION, true );
        wp_localize_script( 'jeo-map', 'jeoMapVars', [
            'jsonUrl' => rest_url( 'wp/v2/' ),
            'string_read_more' => __( 'Read more', 'plenamata' ),
            'jeoUrl' => JEO_BASEURL,
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'templates' => [
                'moreInfo' => file_get_contents( jeo_get_template( 'map-more-info.ejs' ) ),
                'popup' => file_get_contents( jeo_get_template( 'generic-popup.ejs' ) ),
                'postPopup' => file_get_contents( jeo_get_template( 'post-popup.ejs' ) )
            ],
            'cluster' => apply_filters( 'jeomap_js_cluster', [
                'circle_color' => '#ffffff'
            ] ),
            'images' => apply_filters( 'jeomap_js_images', [
                '/js/src/icons/news-marker' => [
                    'url' => JEO_BASEURL . '/js/src/icons/news-marker.png',
                    'icon_size' => 0.1,
                ],
                '/js/src/icons/news-marker-hover' => [
                    'url' => JEO_BASEURL . '/js/src/icons/news-marker-hover.png',
                    'icon_size' => 0.1,
                ],
                '/js/src/icons/news' => [
                    'url' => JEO_BASEURL . '/js/src/icons/news.png',
                    'icon_size' => 0.13,
                ],
            ] )
        ] );


        wp_register_script( 'jeo-layer', JEO_BASEURL . '/js/build/JeoLayer.js', [ 'mapboxgl-loader' ], JEO_VERSION );
        wp_register_script( 'layer-type-mapbox', JEO_BASEURL . '/includes/layer-types/mapbox.js', [ 'jeo-layer' ], JEO_VERSION );

		wp_register_script( 'jeo-legend', JEO_BASEURL . '/js/build/JeoLegend.js', [ 'mapboxgl-loader' ], JEO_VERSION );
    }

    public function archive_templates( string $template ): string {
        global $wp_query;

        if ( is_post_type_archive( 'verbete' ) ) {
            $template = PLENAMATA_PLUGIN_PATH . 'templates/archive-verbete.php';
        }else if( is_author() ){
            $template = PLENAMATA_PLUGIN_PATH . 'templates/archive.php';
        }

        return $template;
    }

    public function page_templates ( string $template ): string {
        if ( get_page_template_slug() === 'template-about.php' ) {
            $template = PLENAMATA_PLUGIN_PATH . 'templates/template-about.php';
        } elseif ( get_page_template_slug() === 'template-dashboard.php' ) {
            $template = PLENAMATA_PLUGIN_PATH . 'templates/template-dashboard.php';
        } elseif ( get_page_template_slug() === 'template-scoreboard.php' ) {
            $template = PLENAMATA_PLUGIN_PATH . 'templates/template-scoreboard.php';
        }

        return $template;
    }

    public function single_templates( string $template ): string {
        global $post;

		if ( $post->post_type === 'verbete' ) {
			$template = PLENAMATA_PLUGIN_PATH . 'templates/single-verbete.php';
        }else if( $post->post_type === 'post'){
			$template = PLENAMATA_PLUGIN_PATH . 'templates/single-news.php';
		}

        return $template;
    }

    /**
     *
     */
    public function search_mobile( string $nav_menu_html, object $args ): string {
        if ( 'primary-menu' != $args->theme_location ) {
            return $nav_menu_html;
        }
        return $nav_menu_html . '<div class="mobile-search-form mobile-only">' . get_search_form( [ 'echo' => false]  ) . '</div>';
    }

    /**
     * Replace Footer
     */
    public function get_footer( $name ) : void {
        if ( $name && ! empty( $name ) && file_exists( PLENAMATA_PLUGIN_PATH . "templates/footer-{$name}.php") ) {
            load_template( PLENAMATA_PLUGIN_PATH . "templates/footer-{$name}.php", true, array() );
            exit;
        }
        if ( file_exists( PLENAMATA_PLUGIN_PATH . "templates/footer.php" ) ) {
            load_template( PLENAMATA_PLUGIN_PATH . 'templates/footer.php', true, array() );
            exit;
        }
    }

    public function replace_header() : void {
        ob_start();
    }
    public function replace_header_close() : void {
        ob_end_clean();
        if ( is_singular( 'post' ) && ! is_home() && ! is_front_page() ) {
            require PLENAMATA_PLUGIN_PATH . 'templates/header-single.php';
        } else {
            require PLENAMATA_PLUGIN_PATH . 'templates/header.php';
        }

    }

    /**
     * Filter the except length to 20 words.
     *
     *  @param int $length Excerpt length.
     *  @return int (Maybe) modified excerpt length.
     */
    public function excerpt_length( $length ) {
        return 22;
    }
}
