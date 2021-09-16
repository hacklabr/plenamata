<?php
/**
 * PlenamataPlugin Translations
 *
 * @since   0.2.7
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

namespace PlenamataPlugin\Common;

/**
 * Class Translations
 *
 * @since   0.1.5
 *
 * @package PlenamataPlugin\Common
 */
class Translations {
    /**
	 * Init hooks
	 *
	 * @since 0.1.0
	 */
	public function hooks(): void {
		add_action( 'after_setup_theme', [ $this, 'load_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'script_translations' ], 999 );
	}

	public function load_textdomain() {
		load_plugin_textdomain(
			'plenamata',
			false,
			'plenamata-plugin/languages'
		);
    }

	public function script_translations() {
		wp_set_script_translations(
			'plenamata-plugin-blocks',
			'plenamata',
			PLENAMATA_PLUGIN_PATH . 'languages'
		);

		wp_set_script_translations(
			'plenamata-plugin',
			'plenamata',
			PLENAMATA_PLUGIN_PATH . 'languages'
		);

		wp_set_script_translations(
			'plenamata-dashboard',
			'plenamata',
			PLENAMATA_PLUGIN_PATH . 'languages'
		);
	}
}