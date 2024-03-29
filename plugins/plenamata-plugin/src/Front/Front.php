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
        add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 50 );
        add_filter( 'jeo_should_load_assets', [$this, 'jeo_should_load_assets'], 10, 1 );
        add_filter( 'jeomap_js_images', [ $this, 'jeo_change_js_image' ], 10, 1 );
        add_filter( 'jeomap_js_cluster', [ $this, 'jeo_change_js_cluster' ], 10, 1 );
    }

    public function jeo_should_load_assets( $should_load ) {
        $template_slug = get_page_template_slug();

        if ( in_array( $template_slug, [ 'discovery.php', 'template-dashboard.php' ] ) ) {
            return true;
        } else if ( is_page() ) {
            return false;
        }

        return $should_load;
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
        $explainer_link = self::get_explainer_link();
        $ti_link = self::get_verbete_link('terra-indigena', 'recortes-territoriais');
        $uc_link = self::get_verbete_link('unidade-de-conservacao', 'recortes-territoriais');
        $plenamata_options = get_option( 'plenamata_options' );

        if ( !empty( $plenamata_options[ 'plenamata_dashboard_api_url' ] ) ) {
            $deter_url = $plenamata_options[ 'plenamata_dashboard_api_url' ];
        } else {
            $deter_url = 'https://api.plenamata.eco/api/';
        }

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

        wp_register_script(
            'estimatives-area-front-end',
            PLENAMATA_PLUGIN_URL . 'assets/build/js/estimatives-area.js',
            [],
			Plugin::VERSION,
            true
        );

        wp_localize_script( 'estimatives-area-front-end', 'PlenamataDashboard', [
            'deterUrl' => $deter_url,
            'explainerUrl' => $explainer_link,
            'i18n' => $dashboard_i18n,
            'language' => $language,
            'opt' => get_option( 'plenamata_options', [] ),
        ] );

        wp_register_script(
            'deforestation-charts-front-end',
            PLENAMATA_PLUGIN_URL . 'assets/build/js/deforestation-charts.js',
            [],
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
            wp_enqueue_script(
                'plenamata-dashboard',
                PLENAMATA_PLUGIN_URL . 'assets/build/js/dashboard.js',
                [ 'jeo-layer', 'jeo-legend', 'jeo-map', 'layer-type-mapbox', 'plenamata-plugin', 'wp-i18n' ],
                Plugin::VERSION,
                true
            );

            wp_localize_script( 'plenamata-dashboard', 'PlenamataDashboard', [
                'deterUrl' => $deter_url,
                'explainerUrl' => $explainer_link,
                'i18n' => $dashboard_i18n,
                'language' => $language,
                'pluginUrl' => PLENAMATA_PLUGIN_URL,
                'restUrl' => get_rest_url(),
                'tiLink' => $ti_link,
                'ucLink' => $uc_link,
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
                '(deforested square kilometers)' => __( '(deforested square kilometers)', 'plenamata' ),
                'All CUs' => __( 'All CUs', 'plenamata' ),
                'All ILs' => __( 'All ILs', 'plenamata' ),
                'All municipalities' => __( 'All municipalities', 'plenamata' ),
                'All states' => __( 'All states', 'plenamata' ),
                'All periods' => __( 'All periods', 'plenamata' ),
                'Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.' => __( 'Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.', 'plenamata' ),
                'Apply filters' => __( 'Apply filters', 'plenamata' ),
                'Area deforested last week in the selected territory' => __( 'Area deforested last week in the selected territory', 'plenamata' ),
                'Area deforested' => __( 'Area deforested', 'plenamata' ),
                'Area of deforestation alerts detected last week' => __( 'Area of deforestation alerts detected last week', 'plenamata' ),
                'By' => __( 'By', 'plenamata' ),
                'By deforestation' => __( 'By deforestation', 'plenamata' ),
                'Clear filters' => __( 'Clear filters', 'plenamata' ),
                'Click and drag to see more' => __( 'Click and drag to see more', 'plenamata' ),
                'Conservation Unit' => __( 'Conservation Unit', 'plenamata' ),
                'Data' => __( 'Data', 'plenamata' ),
                'Deforestation rate' => __( 'Deforestation rate', 'plenamata' ),
                'Deforestation rate in' => __( 'Deforestation rate in', 'plenamata' ),
                'Deforestation rate in %s in the selected territory' => __( 'Deforestation rate in %s in the selected territory', 'plenamata' ),
                'during DETER year' => __( 'during DETER year', 'plenamata' ),
                'during PRODES year' => __( 'during PRODES year', 'plenamata' ),
                'estimated average of %s trees per minute' => __( 'estimated average of %s trees per minute', 'plenamata'),
                'Estimates for the year' => __( 'Estimates for the year', 'plenamata' ),
                'Estimates of trees cut down in %s' => __( 'Estimates of trees cut down in %s', 'plenamata' ),
                'estimates of trees cut down so far' => __( 'estimates of trees cut down so far', 'plenamata' ),
                'Evolution per period' => __( 'Evolution per period', 'plenamata' ),
                'External link' => __( 'External link', 'plenamata' ),
                'Faster than last year' => __( 'Faster than last year', 'plenamata' ),
                'Forestry Dashboard' => __( 'Forestry Dashboard', 'plenamata' ),
                'hectares' => __( 'hectares', 'plenamata' ),
                'hectares/day' => __( 'hectares/day', 'plenamata' ),
                'Indigenous Land' => __( 'Indigenous Land', 'plenamata' ),
                'in the selected territory' => __( 'in the selected territory', 'plenamata' ),
                'km²' => __( 'km²', 'plenamata' ),
                'km²/day' => __( 'km²/day', 'plenamata' ),
                'last week' => __( 'last week', 'plenamata' ),
                'Latest Update' => __( 'Latest Update', 'plenamata' ),
                'Legal Amazon' => __( 'Legal Amazon', 'plenamata' ),
                'Load more' => __( 'Load more', 'plenamata' ),
                'Loading...' => __( 'Loading...', 'plenamata' ),
                'Monthly' => __( 'Monthly', 'plenamata' ),
                'Monthly deforestation rate' => __( 'Monthly deforestation rate', 'plenamata' ),
                'Monthly deforestation rate in the selected territory' => __( 'Monthly deforestation rate in the selected territory', 'plenamata' ),
                'Municipality' => __( 'Municipality', 'plenamata' ),
                'News' => __( 'News', 'plenamata' ),
                'No data available.' => __( 'No data available.', 'plenamata' ),
                'No news to be shown.' => __( 'No news to be shown', 'plenamata' ),
                'per day' => __( 'per day', 'plenamata' ),
                'Period' => __( 'Period', 'plenamata' ),
                'Period: %s' => __( 'Period: %s', 'plenamata' ),
                'Select a state' => __( 'Select a state', 'plenamata' ),
                'See more' => __( 'See more', 'plenamata' ),
                'Select period' => __( 'Select a period', 'plenamata' ),
                'Slower than last year' => __( 'Slower than last year', 'plenamata' ),
                'Source' => __( 'Source', 'plenamata' ),
                'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.' => __( 'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata' ),
                'Sources: INPE and MapBiomas' =>  __( 'Sources: INPE and MapBiomas', 'plenamata' ),
                'Source: MapBiomas based on average daily deforestation detected by INPE in %s.' => __( 'Source: MapBiomas based on average daily deforestation detected by INPE in %s.', 'plenamata' ),
                'Source: MapBiomas based on DETER/INPE data.' => __( 'Source: MapBiomas based on DETER/INPE data.', 'plenamata' ),
                'Source: PRODES/INPE.' => __( 'Source: PRODES/INPE.', 'plenamata' ),
                'Source: PRODES/INPE. Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.' => __( 'Source: PRODES/INPE. Annual deforestation rate calculated for the period from August to July. For example, 2020 rate considers the timeframe from August 2019 to July 2020.', 'plenamata' ),
                'State' => __( 'State', 'plenamata' ),
                'The data of this layer includes the alerts detected in the period between %s and %s, verified since the last update of PRODES.' => __('The data of this layer includes the alerts detected in the period between %s and %s, verified since the last update of PRODES.', 'plenamata'),
                'The figures represent deforestation for each year up to' => __( 'The figures represent deforestation for each year up to', 'plenamata' ),
                'Timeframe' => __( 'Timeframe', 'plenamata' ),
                'Total deforestation' => __( 'Total deforestation', 'plenamata' ),
                'Total deforestation in %s in the selected territory' => __( 'Total deforestation in %s in the selected territory', 'plenamata' ),
                'Total deforested area in %s (until last week)' => __( 'Total deforested area in %s (until last week)', 'plenamata' ),
                'trees' => __( 'trees', 'plenamata' ),
                'Trees cut down in' => __( 'Trees cut down in', 'plenamata' ),
                'trees/minute' => __( 'trees/minute', 'plenamata' ),
                'trees per minute' => __( 'trees per minute', 'plenamata'),
                'trees per day' => __( 'trees per day', 'plenamata' ),
                'Understand the calculus' => __( 'Understand the calculus', 'plenamata' ),
                'Unit' => __( 'Unit', 'plenamata' ),
                'Week %s' => __( 'Week %s', 'plenamata' ),
                'Weekly' => __( 'Weekly', 'plenamata' ),
                'Weekly and monthly data are from %s.' => __( 'Weekly and monthly data are from %s.', 'plenamata' ),
                'Weekly deforestation rate in the selected territory' => __( 'Weekly deforestation rate in the selected territory', 'plenamata'),
                'Weekly deforestation rate' => __( 'Weekly deforestation rate', 'plenamata'),
                'with alerts detected until' => __( 'with alerts detected until', 'plenamata' ),
                'Yearly' => __( 'Yearly', 'plenamata' ),
                'Yearly consolidated deforestation rate' => __( 'Yearly consolidated deforestation rate', 'plenamata' ),
                'Yearly consolidated deforestation rate in the selected territory (PRODES)' => __( 'Yearly consolidated deforestation rate in the selected territory (PRODES)', 'plenamata' ),
                'Yearly deforestation alerts' => __( 'Yearly deforestation alerts', 'plenamata' ),
                'Yearly deforestation alerts in the selected territory (DETER)' => __( 'Yearly deforestation alerts in the selected territory (DETER)', 'plenamata' ),
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
    public static function get_explainer_link() {
        $explainer_post = get_page_by_path('contador-de-arvores-derrubadas', OBJECT, 'verbete');
        return get_permalink($explainer_post->ID);
    }

    /**
     * Retrieve the link for verbete with a specific section selected
     */
    public static function get_verbete_link (string $slug, string $section = '') {
        $verbete_post = get_page_by_path($slug, OBJECT, 'verbete');
        $link = get_permalink($verbete_post->ID);
        if (!empty($section)) {
            $anchor = get_term_by('slug', $section, 'secao')->slug;
            $link .= '?secao=' . $anchor;
        }
        return $link;
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
