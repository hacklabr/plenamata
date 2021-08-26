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
	}

    /**
     * Register filters
     */
    public function filters(): void {
        add_filter( 'archive_template', [ $this, 'archive_templates' ], 10, 1 );
        add_filter( 'page_template', [ $this, 'page_templates' ], 10, 1 );
        add_filter( 'single_template', [ $this, 'single_templates' ], 10, 1 );

        // add template file for search after mobile menu
        add_filter( 'wp_nav_menu', [ $this, 'search_mobile'], 50, 2 );
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
            wp_enqueue_script(
                'plenamata-dashboard',
                PLENAMATA_PLUGIN_URL . 'assets/build/js/dashboard.js',
                [ 'plenamata-plugin', 'wp-i18n' ],
                Plugin::VERSION,
                true
            );

            wp_localize_script( 'plenamata-dashboard', 'PlenamataDashboard', [
                'pluginUrl' => PLENAMATA_PLUGIN_URL,
            ] );
        }
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
}
