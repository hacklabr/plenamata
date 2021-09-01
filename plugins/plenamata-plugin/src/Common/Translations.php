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
        add_action( 'init', [ $this, 'load_textdomain' ], 50 );
	}

    public function load_textdomain() {
        load_plugin_textdomain( 'plenamata', false, PLENAMATA_PLUGIN_PATH . '/languages' ); 
    }
}