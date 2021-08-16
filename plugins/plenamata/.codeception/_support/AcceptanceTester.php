<?php
/**
 * Actor for acceptance tests
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package Plenamata
 * @author  hacklab/
 */

use Codeception\Actor;


/**
 * Inherited Methods
 *
 * @since 0.1.0
 *
 * @method void wantToTest( $text )
 * @method void wantTo( $text )
 * @method void execute( $callable )
 * @method void expectTo( $prediction )
 * @method void expect( $prediction )
 * @method void amGoingTo( $argumentation )
 * @method void am( $role )
 * @method void lookForwardTo( $achieveValue )
 * @method void comment( $description )
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends Actor {

	use _generated\AcceptanceTesterActions;
}
