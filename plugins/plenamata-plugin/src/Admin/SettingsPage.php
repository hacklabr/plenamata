<?php
/**
 * PlenamataPlugin Settings
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

namespace PlenamataPlugin\Admin;

use PlenamataPlugin\Plugin;

/**
 * Class SettingsPage
 *
 * @since   0.1.0
 *
 * @package PlenamataPlugin\Admin
 */
class SettingsPage {

	/**
	 * Init hooks
	 *
	 * @since 0.1.0
	 */
	public function hooks(): void {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_init', [ $this, 'init_settings_page' ] );
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'admin_menu', [ $this, 'rename_menu_entries' ] );
	}

	/**
	 * Register the styles for the admin area.
	 *
	 * @since 0.1.0
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, Plugin::SLUG ) ) {
			return;
		}

		wp_enqueue_style(
			'plenamata-plugin-settings',
			PLENAMATA_PLUGIN_URL . 'assets/build/css/admin/settings.css',
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 0.1.0
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_scripts( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, Plugin::SLUG ) ) {
			return;
		}

		wp_enqueue_script(
			'plenamata-plugin-settings',
			PLENAMATA_PLUGIN_URL . 'assets/build/js/admin/settings.js',
			[ 'jquery' ],
			Plugin::VERSION,
			true
		);
	}

	/**
	 * Add plugin page in WordPress menu.
	 *
	 * @since 0.1.0
	 */
	public function add_menu(): void {
		add_menu_page(
			esc_html__( 'Plenamata Plugin Settings', 'plenamata' ),
			esc_html__( 'Plenamata Plugin', 'plenamata' ),
			'manage_options',
			Plugin::SLUG,
			[
				$this,
				'page_options',
			]
		);
	}

    /**
     * Register fields to plugin's settings page
     */
    public function init_settings_page(): void {
        register_setting( 'plenamata-plugin', 'plenamata_options' );

        add_settings_section(
            'plenamata_dashboard',
            __( 'Dashboard', 'plenamata' ),
            [ $this, 'settings_section_cb' ],
            'plenamata-plugin'
        );

        $active_languages = apply_filters( 'wpml_active_languages', NULL, [] );

        foreach ($active_languages as $key => $language) {
            $field_id = 'plenamata_dashboard_map_' . $key;

            add_settings_field(
                $field_id,
                sprintf( __( 'Map ID - %s', 'plenamata' ), $language[ 'translated_name' ] ),
                [ $this, 'settings_input_cb' ],
                'plenamata-plugin',
                'plenamata_dashboard',
                [
                    'label_for' => $field_id,
                    'type' => 'text',
                ],
            );
        }

        foreach ($active_languages as $key => $language) {
            $field_id = 'plenamata_estimate_explainer_' . $key;

            add_settings_field(
                $field_id,
                sprintf( __( 'Estimate explainer - %s', 'plenamata' ), $language[ 'translated_name' ] ),
                [ $this, 'settings_input_cb' ],
                'plenamata-plugin',
                'plenamata_dashboard',
                [
                    'label_for' => $field_id,
                    'type' => 'text',
                ],
            );
        }
    }

    public function rename_menu_entries(): void {
        global $menu;
        $menu[ 5 ][ 0 ] = __( 'News', 'plenamata' );
    }

    public function settings_input_cb( array $args ): void {
        $options = get_option( 'plenamata_options' );
        $key = $args[ 'label_for' ];
        ?>
        <input
            id="<?= esc_attr( $key ) ?>"
            name="plenamata_options[<?= esc_attr( $key ) ?>]"
            type="<?= $args[ 'type' ] ?>"
            value="<?= isset( $options[ $key ] ) ? esc_attr( $options[ $key ] ) : '' ?>"
        >
        <?php
    }

    public function settings_section_cb(): void {
    }

	/**
	 * Plugin page callback.
	 *
	 * @since 0.1.0
	 */
	public function page_options(): void {
		require_once PLENAMATA_PLUGIN_PATH . 'templates/admin/settings.php';
	}

}
