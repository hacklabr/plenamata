<?php
/**
 * SettingsCest
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package Plenamata
 * @author  hacklab/
 */

/**
 * Class SettingsCest.
 *
 * phpcs:ignoreFile WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
 *
 * @since 0.1.0
 */
class SettingsCest {

	/**
	 * Check a Settings Page
	 *
	 * @since        0.1.0
	 *
	 * @param \AcceptanceTester $I Actor.
	 *
	 * @throws \Exception Something when wrong.
	 */
	public function visitSettingsPage( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnAdminPage( '/admin.php?page=plenamata' );
		$I->see( 'Plenamata Settings' );
	}

}
