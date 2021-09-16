<?php
/**
 * Settings template
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form action="options.php" method="POST">
        <?php
            settings_fields( 'plenamata-plugin' );
            do_settings_sections( 'plenamata-plugin' );
            submit_button();
        ?>
    </form>
</div>
