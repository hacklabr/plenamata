<?php
/**
 * Bootstrap file
 *
 * Plugin Name:         Plenamata
 * Description:         The plugin adds information about the games to the site posts.
 * Version:             0.1.0
 * Requires at least:   4.9
 * Requires PHP:        5.5
 * Author:              hacklab/
 * Author URI:          hacklab.com.br
 * License:             MIT
 * Text Domain:         plenamata
 *
 * @package     Plenamata
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Plenamata\Plugin;
use Plenamata\Vendor\Auryn\Injector;

if ( version_compare( phpversion(), '7.2.5', '<' ) ) {

	/**
	 * Display the notice after deactivation.
	 *
	 * @since 0.1.0
	 */
	function plenamata_php_notice() {
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

	add_action( 'admin_notices', 'plenamata_php_notice' );

	// Don't process the plugin code further.
	return;
}

if ( ! defined( 'PLENAMATA_DEBUG' ) ) {
	/**
	 * Enable plugin debug mod.
	 */
	define( 'PLENAMATA_DEBUG', false );
}
/**
 * Path to the plugin root directory.
 */
define( 'PLENAMATA_PATH', plugin_dir_path( __FILE__ ) );
/**
 * Url to the plugin root directory.
 */
define( 'PLENAMATA_URL', plugin_dir_url( __FILE__ ) );

/**
 * Run plugin function.
 *
 * @since 0.1.0
 *
 * @throws Exception If something went wrong.
 */
function run_plenamata() {
	require_once PLENAMATA_PATH . 'vendor/autoload.php';

	$injector = new Injector();
	( $injector->make( Plugin::class ) )->run();

	/**
	 * You can use the $injector->make( Plenamata\Some\Class::class ) for get any plugin class.
	 * More detail: https://github.com/wppunk/WPPlugin#dependency-injection-container
	 */
	do_action( 'plenamata_init', $injector );
}

add_action( 'plugins_loaded', 'run_plenamata' );
