<?php
/**
 * Bootstrap file for unit tests that run before all tests.
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package Plenamata
 * @author  hacklab/
 */

define( 'PLENAMATA_DEBUG', true );
define( 'PLENAMATA_PATH', realpath( __DIR__ . '/../../../' ) . '/' );
define( 'ABSPATH', realpath( PLENAMATA_PATH . '../../' ) . '/' );
define( 'PLENAMATA_URL', 'https://site.com/wp-content/plugins/plenamata/' );
