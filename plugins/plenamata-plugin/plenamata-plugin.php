<?php
/**
 * Bootstrap file
 *
 * Plugin Name:         Plenamata Plugin
 * Description:         The plugin adds information about the games to the site posts.
 * Version:             0.11.6
 * Requires at least:   5.0
 * Requires PHP:        7.4
 * Author:              hacklab/
 * Author URI:          hacklab.com.br
 * License:             MIT
 * Text Domain:         plenamata
 * Domain Path:         /languages
 *
 * @package     PlenamataPlugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use PlenamataPlugin\Plugin;

if ( version_compare( phpversion(), '7.2.5', '<' ) ) {

	/**
	 * Display the notice after deactivation.
	 *
	 * @since 0.1.0
	 */
	function plenamata_plugin_php_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
				echo wp_kses(
					__( 'The minimum version of PHP is <strong>7.2.5</strong>. Please update the PHP on your server and try again.', 'plenamata' ),
					[
						'strong' => [],
					]
				);
				?>
			</p>
		</div>

		<?php
		// In case this is on plugin activation.
		if ( isset( $_GET['activate'] ) ) { //phpcs:ignore
			unset( $_GET['activate'] ); //phpcs:ignore
		}
	}

	add_action( 'admin_notices', 'plenamata_plugin_php_notice' );

	// Don't process the plugin code further.
	return;
}

if ( ! defined( 'PLENAMATA_PLUGIN_DEBUG' ) ) {
	/**
	 * Enable plugin debug mod.
	 */
	define( 'PLENAMATA_PLUGIN_DEBUG', false );
}
/**
 * Path to the plugin root directory.
 */
define( 'PLENAMATA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
/**
 * Url to the plugin root directory.
 */
define( 'PLENAMATA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Run plugin function.
 *
 * @since 0.1.0
 *
 * @throws Exception If something went wrong.
 */
function run_plenamata_plugin() {
	require_once PLENAMATA_PLUGIN_PATH . 'vendor/autoload.php';

	$plugin = new Plugin();
	$plugin->run();
}

add_action( 'plugins_loaded', 'run_plenamata_plugin' );

/**
 * Users functions
 */
require_once PLENAMATA_PLUGIN_PATH . 'src/users-functions.php';
require_once PLENAMATA_PLUGIN_PATH . 'inc/authors_list_widget.php';