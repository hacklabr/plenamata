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

	}

    /**
     * Register filters
     */
    public function filters(): void {

        add_filter( 'page_template', [ $this, 'page_templates' ], 10, 1 );

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
        $cluster[ 'circle_color'] = '#206837';
        return $cluster;
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
        $language = apply_filters( 'wpml_current_language', NULL );
        $explainer_link = $this->get_explainer_link( $language );

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
                        'seeMore' => __( 'See more', 'plenamata' ),
                    ]
                ],
                'pluginUrl' => PLENAMATA_PLUGIN_URL,
                'restUrl' => get_rest_url(),
            ],
        );

        wp_register_script( 'luxon', 'https://unpkg.com/luxon@2/build/global/luxon.min.js', [], false, true );

        wp_register_script(
            'estimatives-area-front-end',
            PLENAMATA_PLUGIN_URL . 'assets/build/js/estimatives-area.js',
            [ 'luxon' ],
			Plugin::VERSION,
            true
        );

        wp_localize_script( 'estimatives-area-front-end', 'PlenamataDashboard', [
            'explainerUrl' => $explainer_link,
            'i18n' => $dashboard_i18n,
            'language' => $language,
            'opt' => get_option( 'plenamata_options', [] ),
        ] );

        wp_register_script(
            'deforestation-charts-front-end',
            PLENAMATA_PLUGIN_URL . 'assets/build/js/deforestation-charts.js',
            [ 'luxon' ],
			Plugin::VERSION,
            true
        );

        $template_slug = get_page_template_slug();

        if ( $template_slug === 'template-headings.php' ) {
            wp_enqueue_script(
                'plenamata-about-page',
                PLENAMATA_PLUGIN_URL . 'assets/build/js/about-page.js',
                [],
                Plugin::VERSION,
                true
            );
        }

        if ( $template_slug === 'template-dashboard.php' ) {
            $this->register_jeo_assets();

            wp_enqueue_script(
                'plenamata-dashboard',
                PLENAMATA_PLUGIN_URL . 'assets/build/js/dashboard.js',
                [ 'jeo-layer', 'jeo-legend', 'jeo-map', 'layer-type-mapbox', 'plenamata-plugin', 'wp-i18n' ],
                Plugin::VERSION,
                true
            );

            wp_localize_script( 'plenamata-dashboard', 'PlenamataDashboard', [
                'explainerUrl' => $explainer_link,
                'i18n' => $dashboard_i18n,
                'language' => $language,
                'pluginUrl' => PLENAMATA_PLUGIN_URL,
                'restUrl' => get_rest_url(),
            ] );
        }

        if ( $template_slug === 'template-scoreboard.php' ) {
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
                'By deforestation' => __( 'By deforestation', 'plenamata' ),
                'Faster than last year' => __( 'Faster than last year', 'plenamata' ),
                'Deforestation rate' => __( 'Deforestation rate', 'plenamata' ),
                'Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.' => __( 'Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.', 'plenamata' ),
                'Area deforested last week in the selected territory' => __( 'Area deforested last week in the selected territory', 'plenamata' ),
                'Area deforested' => __( 'Area deforested', 'plenamata' ),
                'Area of deforestation alerts detected last week' => __( 'Area of deforestation alerts detected last week', 'plenamata' ),
                'Clear filters' => __( 'Clear filters', 'plenamata' ),
                'Conservation Unit' => __( 'Conservation Unit', 'plenamata' ),
                'Data' => __( 'Data', 'plenamata' ),
                'in the selected territory' => __( 'in the selected territory', 'plenamata' ),
                'Deforestation rate in %s in the selected territory' => __( 'Deforestation rate in %s in the selected territory', 'plenamata' ),
                'Deforestation rate in' => __( 'Deforestation rate in', 'plenamata' ),
                'Drag to see more' => __( 'Drag to see more', 'plenamata' ),
                'during DETER year' => __( 'during DETER year', 'plenamata' ),
                'during PRODES year' => __( 'during PRODES year', 'plenamata' ),
                'estimated average of %s trees per minute' => __( 'estimated average of %s trees per minute', 'plenamata'),
                'trees per minute' => __( 'trees per minute', 'plenamata'),
                'External link' => __( 'External link', 'plenamata' ),
                'Forestry Dashboard' => __( 'Forestry Dashboard', 'plenamata' ),
                'hectares' => __( 'hectares', 'plenamata' ),
                'hectares per day' => __( 'hectares per day', 'plenamata' ),
                'Indigenous Land' => __( 'Indigenous Land', 'plenamata' ),
                'km²' => __( 'km²', 'plenamata' ),
                'km² per day' => __( 'km² per day', 'plenamata' ),
                'Legal Amazon' => __( 'Legal Amazon', 'plenamata' ),
                'Load more' => __( 'Load more', 'plenamata' ),
                'Loading...' => __( 'Loading...', 'plenamata' ),
                'Monthly' => __( 'Monthly', 'plenamata' ),
                'Monthly deforestation rate' => __( 'Monthly deforestation rate', 'plenamata' ),
                'Monthly deforestation rate in the selected territory' => __( 'Monthly deforestation rate in the selected territory', 'plenamata' ),
                'Municipality' => __( 'Municipality', 'plenamata' ),
                'News' => __( 'News', 'plenamata' ),
                'No news to be shown.' => __( 'No news to be shown', 'plenamata' ),
                'Period' => __( 'Period', 'plenamata' ),
                'Period: %s' => __( 'Period: %s', 'plenamata' ),
                'Select a state' => __( 'Select a state', 'plenamata' ),
                'See more' => __( 'See more', 'plenamata' ),
                'Latest Update' => __( 'Latest Update', 'plenamata' ),
                'Source' => __( 'Source', 'plenamata' ),
                'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.' => __( 'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata' ),
                'Source: MapBiomas based on average daily deforestation detected by INPE in %s.' => __( 'Source: MapBiomas based on average daily deforestation detected by INPE in %s.', 'plenamata' ),
                'Source: MapBiomas based on DETER/INPE data.' => __( 'Source: MapBiomas based on DETER/INPE data.', 'plenamata' ),
                'Source: PRODES/INPE.' => __( 'Source: PRODES/INPE.', 'plenamata' ),
                'Sources: INPE and MapBiomas' =>  __( 'Sources: INPE and MapBiomas', 'plenamata' ),
                'State' => __( 'State', 'plenamata' ),
                'The data of this layer includes the alerts detected in the period between %s and %s, verified since the last update of PRODES.' => __('The data of this layer includes the alerts detected in the period between %s and %s, verified since the last update of PRODES.', 'plenamata'),
                'Timeframe' => __( 'Timeframe', 'plenamata' ),
                'Total deforestation in %s in the selected territory' => __( 'Total deforestation in %s in the selected territory', 'plenamata' ),
                'Total deforestation' => __( 'Total deforestation', 'plenamata' ),
                'Total deforested area in %s (until last week)' => __( 'Total deforested area in %s (until last week)', 'plenamata' ),
                'per day' => __( 'per day', 'plenamata' ),
                'last week' => __( 'last week', 'plenamata' ),
                'km²/day' => __( 'km²/day', 'plenamata' ),
                'hectares/day' => __( 'hectares/day', 'plenamata' ),
                'trees/minute' => __( 'trees/minute', 'plenamata' ),
                'trees' => __( 'trees', 'plenamata' ),
                'Trees cut down in' => __( 'Trees cut down in', 'plenamata' ),
                'Estimates for the year' => __( 'Estimates for the year', 'plenamata' ),
                'Estimates of trees cut down in %s' => __( 'Estimates of trees cut down in %s', 'plenamata' ),
                'estimates of trees cut down so far' => __( 'estimates of trees cut down so far', 'plenamata' ),
                'with alerts detected until' => __( 'with alerts detected until', 'plenamata' ),
                'in the selected territory' => __( 'in the selected territory', 'plenamata' ),
                'trees per day' => __( 'trees per day', 'plenamata' ),
                'Understand the calculus' => __( 'Understand the calculus', 'plenamata' ),
                'Unit' => __( 'Unit', 'plenamata' ),
                'Week %s' => __( 'Week %s', 'plenamata' ),
                'Weekly' => __( 'Weekly', 'plenamata' ),
                'Weekly and monthly data are from %s.' => __( 'Weekly and monthly data are from %s.', 'plenamata' ),
                'Weekly deforestation rate in the selected territory' => __( 'Weekly deforestation rate in the selected territory', 'plenamata'),
                'Weekly deforestation rate' => __( 'Weekly deforestation rate', 'plenamata'),
                'Yearly' => __( 'Yearly', 'plenamata' ),
                'Yearly consolidated deforestation rate in the selected territory (PRODES)' => __( 'Yearly consolidated deforestation rate in the selected territory (PRODES)', 'plenamata' ),
                'Yearly consolidated deforestation rate' => __( 'Yearly consolidated deforestation rate', 'plenamata' ),
                'Yearly deforestation alerts in the selected territory (DETER)' => __( 'Yearly deforestation alerts in the selected territory (DETER)', 'plenamata' ),
                'Yearly deforestation alerts' => __( 'Yearly deforestation alerts', 'plenamata' ),
                'Select period' => __( 'Select a period', 'plenamata' ),
                'All periods' => __( 'All periods', 'plenamata' ),
                'Apply filters' => __( 'Apply filters', 'plenamata' ),
                'The figures represent deforestation for each year up to' => __( 'The figures represent deforestation for each year up to', 'plenamata' ),
                'Source: PRODES/INPE. Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.' => __( 'Source: PRODES/INPE. Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.', 'plenamata' ),
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
     * Retrieve the link for estimate explainer
     */
    public function get_explainer_link( string $language ){
        $options = get_option( 'plenamata_options', [] );
        $explainer_link = $options[ 'plenamata_estimate_explainer_' . $language ];

        if ( empty( $explainer_link ) ) {
            return null;
        } else {
            return $explainer_link;
        }
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

    public function page_templates ( string $template ): string {
        
        $template_slug = get_page_template_slug();

        // Dashboard page
        if( $template_slug === 'template-dashboard.php' ):
            $template = PLENAMATA_PLUGIN_PATH . 'templates/template-dashboard.php';
        // Scoreboard page
        elseif( $template_slug === 'template-scoreboard.php' ):
            $template = PLENAMATA_PLUGIN_PATH . 'templates/template-scoreboard.php';
        endif;

        return $template;

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
