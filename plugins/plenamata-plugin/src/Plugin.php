<?php
/**
 * PlenamataPlugin Bootstrap class
 *
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

namespace PlenamataPlugin;

use Exception;
use PlenamataPlugin\Common\Translations;
use PlenamataPlugin\Common\RestApi;
use PlenamataPlugin\Front\Front;
use PlenamataPlugin\Admin\Blocks;
use PlenamataPlugin\Admin\SettingsPage;

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
	 */
	const SLUG = 'plenamata-plugin';
	/**
	 * Plugin version
	 */
	const VERSION = '0.12.0';

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
	}

	/**
	 * Run plugin
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
	 */
	private function run_admin(): void {
        $blocks = new Blocks();
        $rest_api = new RestApi();
        $settings_page = new SettingsPage();
        $translations = new Translations();

		$translations->hooks();
        $blocks->hooks();
        $blocks->filters();
		$settings_page->hooks();
        $rest_api->hooks();
        $rest_api->filters();
	}

	/**
	 * Run frontend part
	 */
	private function run_front(): void {
        $blocks = new Blocks();
        $front = new Front();
        $rest_api = new RestApi();
        $translations = new Translations();

		$translations->hooks();
		$blocks->hooks();
        $blocks->filters();
		$front->hooks();
        $front->filters();
        $rest_api->hooks();
        $rest_api->filters();
	}

}
