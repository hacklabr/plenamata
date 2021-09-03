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
		wp_enqueue_script(
			'plenamata-plugin',
			PLENAMATA_PLUGIN_URL . 'assets/build/js/main.js',
			[],
			Plugin::VERSION,
			true
		);

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
                'language' => \ICL_LANGUAGE_CODE,
                'pluginUrl' => PLENAMATA_PLUGIN_URL,
                'restUrl' => get_rest_url(),
                'i18n' => [
                    '__' => [
                        'News' => __( 'News', 'plenamata' ),
                        'Forestry Dashboard' => __( 'Forestry Dashboard', 'plenamata' ),
                        'States' => __( 'States', 'plenamata' ),
                        'All states' => __( 'All states', 'plenamata' ),
                        'Data' => __( 'Data', 'plenamata' ),
                        'No news to be shown.' => __( 'No news to be shown', 'plenamata' ),
                        'External link' => __( 'External link', 'plenamata' ),
                        'estimated average of %s trees per minute' => __( 'estimated average of %s trees per minute', 'plenamata'),
                        'Source: MapBiomas based on DETER/INPE data.' => __( 'Source: MapBiomas based on DETER/INPE data.', 'plenamata' ),
                        'Unit' => __( 'Unit', 'plenamata' ),
                        'hectares per day' => __( 'hectares per day', 'plenamata' ),
                        'km² per day' => __( 'km² per day', 'plenamata' ),
                        'Area deforested last week' => __( 'Area deforested last week', 'plenamata' ),
                        'hectares' => __( 'hectares', 'plenamata' ),
                        'km²' => __( 'km²', 'plenamata' ),
                        'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.' => __( 'Source: DETER/INPE • Latest Update: %s with alerts detected until %s.', 'plenamata' ),
                        'Estimated number of trees cut down in %s' => __( 'Estimated number of trees cut down in %s', 'plenamata' ),
                        'trees' => __( 'trees', 'plenamata' ),
                        'Source: MapBiomas based on average daily deforestation detected by DETER in %s.' => __( 'Source: MapBiomas based on average daily deforestation detected by DETER in %s.', 'plenamata' ),
                        'Monthly deforestation rate' => __( 'Monthly deforestation rate', 'plenamata' ),
                        'Timeframe' => __( 'Timeframe', 'plenamata' ),
                        'during DETER year' => __( 'during DETER year', 'plenamata' ),
                        'during PRODES year' => __( 'during PRODES year', 'plenamata' ),
                        'Total deforestation in %s in the selected territory' => __( 'Total deforestation in %s in the selected territory', 'plenamata' ),
                        'Total deforested area in %s (until last week)' => __( 'Total deforested area in %s (until last week)', 'plenamata' ),
                        '%s%% increase compared to last year' => __( '%s%% increase compared to last year', 'plenamata' ),
                        '%s%% decrease compared to last year' => __( '%s%% decrease compared to last year', 'plenamata' ),
                        'Weekly deforestation rate' => __( 'Weekly deforestation rate', 'plenamata'),
                        'Period' => __( 'Period', 'plenamata' ),
                        'during DETER year' => __( 'during DETER year', 'plenamata' ),
                        'during PRODES year' => __( 'during PRODES year', 'plenamata' ),
                        'Yearly deforestation alerts (DETER)' => __( 'Yearly deforestation alerts (DETER)', 'plenamata' ),
                        'The figures represent deforestation for each year up to %s.' => __( 'The figures represent deforestation for each year up to %s.', 'plenamata' ),
                        'Yearly consolidated deforestation rate (PRODES)' => __( 'Yearly consolidated deforestation rate (PRODES)', 'plenamata' )
                    ],
                    '_x' => [
                        'months' => [
                            'January' => _x('January', 'months', 'plenamata'),
                            'February' => _x('February', 'months', 'plenamata'),
                            'March' => _x('March', 'months', 'plenamata'),
                            'April' => _x('April', 'months', 'plenamata'),
                            'May' => _x('May', 'months', 'plenamata'),
                            'June' => _x('June', 'months', 'plenamata'),
                            'July' => _x('July', 'months', 'plenamata'),
                            'August' => _x('August', 'months', 'plenamata'),
                            'September' => _x('September', 'months', 'plenamata'),
                            'October' => _x('October', 'months', 'plenamata'),
                            'November' => _x('November', 'months', 'plenamata'),
                            'December' => _x('December', 'months', 'plenamata'),
                        ],
                    ]
                ]
            ] );
        }

        wp_enqueue_script(
            'estimatives-area-front-end',
            PLENAMATA_PLUGIN_URL . 'assets/build/js/estimatives-area.js',
            [],
            false,
            true
        );

        wp_localize_script('estimatives-area-front-end', 'estimativesArea', [
            'utc' => time(),
            'getLangCode' => apply_filters( "wpml_current_language", NULL ),
        ]);
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
            ]
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
        if ( get_page_template_slug() === 'template-dashboard.php' ) {
            $template = PLENAMATA_PLUGIN_PATH . 'templates/template-dashboard.php';
        }

        return $template;
    }

    public function single_templates( string $template ): string {
        global $post;

		if ( $post->post_type === 'verbete' ) {
			$template = PLENAMATA_PLUGIN_PATH . 'templates/single-verbete.php';
        }else if( $post->post_type === 'post'){
			$template = PLENAMATA_PLUGIN_PATH . 'templates/single-artigo.php';
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
