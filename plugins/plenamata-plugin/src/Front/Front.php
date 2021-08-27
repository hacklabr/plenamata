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
use stdClass;

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
	}

    /**
     * Register filters
     */
    public function filters(): void {
        add_filter( 'archive_template', [ $this, 'archive_templates' ], 10, 1 );
        add_filter( 'document_title_parts', [ $this, 'custom_titles' ], 10, 1 );
        add_filter( 'page_template', [ $this, 'page_templates' ], 10, 1 );
        add_filter( 'single_template', [ $this, 'single_templates' ], 10, 1 );

        // add template file for search after mobile menu
        add_filter( 'wp_nav_menu', [ $this, 'search_mobile'], 50, 2 );

    }

    public function custom_titles( array $parts ): array {
        if ( is_post_type_archive( 'verbete' ) ) {
            $parts['title'] = __( 'Glossary', 'plenamata' );
        }

        return $parts;
    }

	/**
	 * Enqueue styles for the front area.
	 *
	 * @since 0.1.0
	 */
	public function enqueue_styles(): void {
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
                'pluginUrl' => PLENAMATA_PLUGIN_URL,
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
    public function register_jeo_assets(): void {
        wp_enqueue_style( 'mapboxgl', 'https://api.mapbox.com/mapbox-gl-js/v1.4.1/mapbox-gl.css', '1.4.1' );
        wp_register_script( 'mapboxgl-loader', JEO_BASEURL . '/js/build/mapboxglLoader.js', JEO_VERSION );

        wp_register_script( 'jeo-map', JEO_BASEURL . '/js/build/jeoMap.js', [ 'mapboxgl-loader', 'jquery', 'wp-element' ], JEO_VERSION, true );
        wp_localize_script( 'jeo-map', 'jeoMapVars', [
            'jsonUrl' => rest_url( 'wp/v2/' ),
            'string_read_more' => __( 'Read more', 'jeo' ),
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
        if ( is_post_type_archive( 'verbete' ) ) {
            $template = PLENAMATA_PLUGIN_PATH . 'templates/archive-verbete.php';
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
}
