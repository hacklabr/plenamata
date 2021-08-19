<?php
/**
 * PlenamataPlugin Blocks
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
class Blocks {
    /**
	 * Init hooks
	 *
	 * @since 0.1.0
	 */
	public function hooks(): void {
		add_action( 'init', [ $this, 'register_blocks' ] );
	}

    /**
	 * Register the JavaScript for the admin blocks.
	 *
	 * @since 0.1.0
	 *
	 * @param string $hook_suffix The current admin page.
	 */
    public function register_blocks(): void {
        wp_register_script(
			'plenamata-plugin-blocks',
			PLENAMATA_PLUGIN_URL . 'assets/build/js/admin/blocks.js',
			[ 'wp-blocks', 'wp-i18n' ],
			Plugin::VERSION,
			false
		);

        register_block_type( 'plenamata/verbete-subsection', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
        ] );
    }
}