<?php
/**
 * Bootstrap file for unit tests that run before all tests.
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

define( 'PLENAMATA_PLUGIN_DEBUG', true );
define( 'PLENAMATA_PLUGIN_PATH', realpath( __DIR__ . '/../../../' ) . '/' );
define( 'ABSPATH', realpath( PLENAMATA_PLUGIN_PATH . '../../' ) . '/' );
define( 'PLENAMATA_PLUGIN_URL', 'https://site.com/wp-content/plugins/plenamata-plugin/' );
