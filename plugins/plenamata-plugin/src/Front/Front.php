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
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
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
	}

}
