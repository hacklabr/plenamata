<?php
/**
 * FrontTest
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package Plenamata
 * @author  hacklab/
 */

namespace PlenamataUnitTests\Front;

use Plenamata\Plugin;
use Plenamata\Front\Front;
use PlenamataTests\TestCase;

use function Brain\Monkey\Functions\expect;

/**
 * Class FrontTest
 *
 * @since   0.1.0
 *
 * @package PlenamataUnitTests\Front
 */
class FrontTest extends TestCase {

	/**
	 * Test for adding hooks
	 *
	 * @since 0.1.0
	 */
	public function test_hooks() {
		$front = new Front();

		$front->hooks();

		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', [ $front, 'enqueue_styles' ] ) );
		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', [ $front, 'enqueue_scripts' ] ) );
	}

	/**
	 * Test enqueue styles
	 *
	 * @since 0.1.0
	 *
	 * @throws \Brain\Monkey\Expectation\Exception\ExpectationArgsRequired Invalid arguments.
	 */
	public function test_enqueue_styles() {
		$front = new Front();
		expect( 'wp_enqueue_style' )
			->once()
			->with(
				'plenamata',
				PLENAMATA_URL . 'assets/build/css/main.css',
				[],
				Plugin::VERSION,
				'all'
			);

		$front->enqueue_styles();
	}

	/**
	 * Test enqueue scripts
	 *
	 * @since 0.1.0
	 *
	 * @throws \Brain\Monkey\Expectation\Exception\ExpectationArgsRequired Invalid arguments.
	 */
	public function test_enqueue_scripts() {
		$front = new Front();
		expect( 'wp_enqueue_script' )
			->once()
			->with(
				'plenamata',
				PLENAMATA_URL . 'assets/build/js/main.js',
				[],
				Plugin::VERSION,
				true
			);

		$front->enqueue_scripts();
	}

}
