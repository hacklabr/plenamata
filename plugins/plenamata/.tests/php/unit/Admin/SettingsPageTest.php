<?php
/**
 * SettingsTest
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package Plenamata
 * @author  hacklab/
 */

namespace PlenamataUnitTests\Admin;

use Plenamata\Plugin;
use PlenamataTests\TestCase;
use Plenamata\Admin\SettingsPage;

use function Brain\Monkey\Functions\when;
use function Brain\Monkey\Functions\expect;

/**
 * Class SettingsPageTest
 *
 * @since   0.1.0
 *
 * @package PlenamataUnitTests\Admin
 */
class SettingsPageTest extends TestCase {

	/**
	 * Test for adding hooks
	 *
	 * @since 0.1.0
	 */
	public function test_hooks() {
		$settings = new SettingsPage();

		$settings->hooks();

		$this->assertSame( 10, has_action( 'admin_enqueue_scripts', [ $settings, 'enqueue_styles' ] ) );
		$this->assertSame( 10, has_action( 'admin_enqueue_scripts', [ $settings, 'enqueue_scripts' ] ) );
		$this->assertSame( 10, has_action( 'admin_menu', [ $settings, 'add_menu' ] ) );
	}

	/**
	 * Test don't enqueue styles
	 *
	 * @since 0.1.0
	 */
	public function test_DONT_enqueue_styles() {
		$settings = new SettingsPage();

		$settings->enqueue_styles( 'hook-suffix' );
	}

	/**
	 * Test enqueue styles
	 *
	 * @since 0.1.0
	 *
	 * @throws \Brain\Monkey\Expectation\Exception\ExpectationArgsRequired Invalid arguments.
	 */
	public function test_enqueue_styles() {
		$settings = new SettingsPage();
		expect( 'wp_enqueue_style' )
			->once()
			->with(
				'plenamata-settings',
				PLENAMATA_URL . 'assets/build/css/admin/settings.css',
				[],
				Plugin::VERSION,
				'all'
			);

		$settings->enqueue_styles( 'plenamata' );
	}

	/**
	 * Test don't enqueue scripts
	 *
	 * @since 0.1.0
	 */
	public function test_DONT_enqueue_scripts() {
		$settings = new SettingsPage();

		$settings->enqueue_scripts( 'hook-suffix' );
	}

	/**
	 * Test enqueue scripts
	 *
	 * @since 0.1.0
	 *
	 * @throws \Brain\Monkey\Expectation\Exception\ExpectationArgsRequired Invalid arguments.
	 */
	public function test_enqueue_scripts() {
		$settings = new SettingsPage();
		expect( 'wp_enqueue_script' )
			->once()
			->with(
				'plenamata-settings',
				PLENAMATA_URL . 'assets/build/js/admin/settings.js',
				[ 'jquery' ],
				Plugin::VERSION,
				true
			);

		$settings->enqueue_scripts( 'plenamata' );
	}

	/**
	 * Test register menu
	 *
	 * @since 0.1.0
	 *
	 * @throws \Brain\Monkey\Expectation\Exception\ExpectationArgsRequired Invalid arguments.
	 */
	public function test_add_menu() {
		$settings = new SettingsPage();
		when( 'esc_html__' )->returnArg();
		expect( 'add_menu_page' )
			->once()
			->with(
				'Plenamata Settings',
				'Plenamata',
				'manage_options',
				Plugin::SLUG,
				[
					$settings,
					'page_options',
				]
			);

		$settings->add_menu();
	}

	/**
	 * Test view for settings page
	 *
	 * @since 0.1.0
	 *
	 * @throws \Brain\Monkey\Expectation\Exception\ExpectationArgsRequired Invalid arguments.
	 */
	public function test_page_option() {
		$page_title = 'Plenamata Settings';
		$settings   = new SettingsPage();
		expect( 'get_admin_page_title' )
			->once()
			->withNoArgs()
			->andReturn( $page_title );
		when( 'esc_html' )->returnArg();

		$settings->page_options();
	}

}
