<?php
/**
 * PluginTest
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package Plenamata
 * @author  hacklab/
 */

namespace PlenamataUnitTests;

use Plenamata\Plugin;
use Plenamata\Front\Front;
use PlenamataTests\TestCase;
use Plenamata\Admin\SettingsPage;

use function Brain\Monkey\Functions\expect;

/**
 * Class FrontTest
 *
 * @since   0.1.0
 *
 * @package PlenamataUnitTests\Front
 */
class PluginTest extends TestCase {

	/**
	 * Test for adding hooks
	 *
	 * @since 0.1.0
	 */
	public function test_run_admin() {
		expect( 'is_admin' )
			->once()
			->withNoArgs()
			->andReturn( true );
		$settings = \Mockery::mock( '\Plenamata\Admin\SettingsPage' );
		$settings
			->shouldReceive( 'hooks' )
			->once()
			->withNoArgs();
		$injector = \Mockery::mock( 'Plenamata\Vendor\Auryn\Injector' );
		$injector
			->shouldReceive( 'make' )
			->once()
			->with( SettingsPage::class )
			->andReturn( $settings );
		$plugin = new Plugin( $injector );

		$plugin->run();
	}

	/**
	 * Test for adding hooks
	 *
	 * @since 0.1.0
	 */
	public function test_run_front() {
		expect( 'is_admin' )
			->once()
			->withNoArgs()
			->andReturn( false );
		$front = \Mockery::mock( '\Plenamata\Front\Front' );
		$front
			->shouldReceive( 'hooks' )
			->once()
			->withNoArgs();
		$injector = \Mockery::mock( 'Plenamata\Vendor\Auryn\Injector' );
		$injector
			->shouldReceive( 'make' )
			->once()
			->with( Front::class )
			->andReturn( $front );
		$plugin = new Plugin( $injector );

		$plugin->run();
	}

}
