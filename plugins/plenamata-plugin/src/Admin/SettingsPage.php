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
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
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
			esc_html__( 'Plenamata Plugin Settings', 'plenamata-plugin' ),
			esc_html__( 'Plenamata Plugin', 'plenamata-plugin' ),
			'manage_options',
			Plugin::SLUG,
			[
				$this,
				'page_options',
			]
		);
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