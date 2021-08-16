<?php
/**
 * Plenamata Bootstrap class
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package Plenamata
 * @author  hacklab/
 */

namespace Plenamata;

use Exception;
use Plenamata\Front\Front;
use Plenamata\Admin\SettingsPage;
use Plenamata\Vendor\Auryn\Injector;
use Plenamata\Vendor\Auryn\InjectionException;

/**
 * Class Plugin
 *
 * @since   0.1.0
 *
 * @package Plenamata
 */
class Plugin {

	/**
	 * Plugin slug
	 *
	 * @since 0.1.0
	 */
	const SLUG = 'plenamata';
	/**
	 * Plugin version
	 *
	 * @since 0.1.0
	 */
	const VERSION = '0.1.0';
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
		$this->injector->make( SettingsPage::class )->hooks();
	}

	/**
	 * Run frontend part
	 *
	 * @since 0.1.0
	 *
	 * @throws InjectionException If a cyclic gets detected when provisioning.
	 */
	private function run_front(): void {
		$this->injector->make( Front::class )->hooks();
	}

}
