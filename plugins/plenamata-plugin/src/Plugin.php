<?php
/**
 * PlenamataPlugin Bootstrap class
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

namespace PlenamataPlugin;

use Exception;
use PlenamataPlugin\Common\RestApi;
use PlenamataPlugin\Front\Front;
use PlenamataPlugin\Admin\Blocks;
use PlenamataPlugin\Admin\SettingsPage;
use PlenamataPlugin\Vendor\Auryn\Injector;
use PlenamataPlugin\Vendor\Auryn\InjectionException;

/**
 * Class Plugin
 *
 * @since   0.1.0
 *
 * @package PlenamataPlugin
 */
class Plugin {

	/**
	 * Plugin slug
	 *
	 * @since 0.1.0
	 */
	const SLUG = 'plenamata-plugin';
	/**
	 * Plugin version
	 *
	 * @since 0.1.0
	 */
	const VERSION = '0.2.5';
	/**
	 * Dependency Injection Container.
	 *
	 * @since 0.1.0
	 *
	 * @var Injector
	 */
	private $injector;

	/**
	 * Plugin constructor.
	 *
	 * @param Injector $injector Dependency Injection Container.
	 */
	public function __construct( Injector $injector ) {
		$this->injector = $injector;
	}

	/**
	 * Run plugin
	 *
	 * @since 0.1.0
	 *
	 * @throws Exception Object doesn't exist.
	 */
	public function run(): void {
		is_admin()
			? $this->run_admin()
			: $this->run_front();
	}

	/**
	 * Run admin part
	 *
	 * @since 0.1.0
	 *
	 * @throws InjectionException If a cyclic gets detected when provisioning.
	 */
	private function run_admin(): void {
        $this->injector->make( Blocks::class )->hooks();
        $this->injector->make( Blocks::class )->filters();
		$this->injector->make( SettingsPage::class )->hooks();
        $this->injector->make( RestApi::class )->hooks();
        $this->injector->make( RestApi::class )->filters();
	}

	/**
	 * Run frontend part
	 *
	 * @since 0.1.0
	 *
	 * @throws InjectionException If a cyclic gets detected when provisioning.
	 */
	private function run_front(): void {
		$this->injector->make( Blocks::class )->hooks();
        $this->injector->make( Blocks::class )->filters();
		$this->injector->make( Front::class )->hooks();
        $this->injector->make( Front::class )->filters();
        $this->injector->make( RestApi::class )->hooks();
        $this->injector->make( RestApi::class )->filters();
	}

}
