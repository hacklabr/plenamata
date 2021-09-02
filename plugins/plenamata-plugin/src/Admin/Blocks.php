<?php
/**
 * PlenamataPlugin Blocks
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

namespace PlenamataPlugin\Admin;

use PlenamataPlugin\Plugin;

/**
 * Class SettingsPage
 *
 * @since   0.1.0
 *
 * @package PlenamataPlugin\Admin
 */
class Blocks {
    /**
	 * Init hooks
	 *
	 * @since 0.1.0
	 */
	public function hooks(): void {
		add_action( 'init', [ $this, 'register_blocks' ] );
	}

    public function filters(): void {
        add_filter( 'theme_page_templates', [ $this, 'custom_page_templates' ], 10, 1 );
    }

    public function custom_page_templates( array $templates ): array {
        $templates['template-dashboard.php'] = __( 'Dashboard', 'plenamata' );

        return $templates;
    }

    /**
	 * Register the JavaScript for the admin blocks.
	 *
	 * @since 0.1.0
	 *
	 * @param string $hook_suffix The current admin page.
	 */
    public function register_blocks(): void {
        wp_register_script(
            'plenamata-plugin-blocks',
			PLENAMATA_PLUGIN_URL . 'assets/build/js/admin/blocks.js',
            [ 'wp-blocks', 'wp-i18n' ],
			Plugin::VERSION,
			false
		);

        wp_register_style(
            'plenamata-plugin-blocks',
            PLENAMATA_PLUGIN_URL . 'assets/build/css/admin/blocks.css',
            [],
            Plugin::VERSION,
        );

        register_block_type( 'plenamata/verbete-subsection', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
        ] );

        register_block_type( 'plenamata/estimatives-area', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
            'render_callback' => [ $this, 'estimatives_area_render_callback' ],
            'attributes' => [
                // Strings
                "boxTitle" => [
                    "type" => "string"
                ],
                "headingTitle" => [
                    "type" => "string"
                ],
                "preNumberTitle" => [
                    "type" => "string"
                ],
                "averageTitle" => [
                    "type" => "string"
                ],
                "deforestedTitle" => [
                    "type" => "string"
                ],
                "finalInformation" => [
                    "type" => "string"
                ],
        
                // Base numbers
                "baseTrees" => [
                    "type" => "string"
                ],
                "tressPerDay" => [
                    "type" => "string"
                ],
                "hecPerDay" => [
                    "type" => "string"
                ],
                "hectares" => [
                    "type" => "string"
                ],
                "warnings" => [
                    "type" => "string"
                ],
                "baseDate" => [
                    "type" => "string"
                ]
            ],
        ] );

        register_block_type( 'plenamata/deforestation-info', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
            'attributes' => [
                'boxTitle' => [
                    'type' => 'string'
                ],
                'count' => [
                    'type' => 'string'
                ],
                'dataSource' => [
                    'type' => 'string'
                ],
            ],
        ] );
    }

    public function estimatives_area_render_callback( $attributes ) {

        extract( $attributes );

        ob_start(); ?>

            <div class="estimatives-area">
                <div class="heading">
                    <h3> <?= $headingTitle ?? '' ?> </h3>
                </div>

                <div class="main-data">
                    <h4><?= $preNumberTitle ?? '' ?></h4>

                    <div class="number">
                        <span id="trees-estimative" data-base-trees="<?= $baseTrees ?? '' ?>" data-trees-per-day="<?= $tressPerDay ?? '' ?>" data-date="<?= $baseDate ?? '' ?>">loading...</span>
                        <span><?= __("real-time estimate", "plenamata") ?></span>
                    </div>
                </div>

                <div class="base-data">
                    <div>
                        <div class="data">
                            <h4><?= $averageTitle ?? '' ?></h4>
                            <div class="area">
                                <span data-mask="true">
                                    <?= $tressPerDay ?? '' ?>
                                </span>
                                <span>
                                    <?= __("trees/ day", "plenamata") ?>
                                </span>
                            </div>

                            <div class="area">
                                <span data-mask="true">
                                    <?= $hecPerDay ?? '' ?>
                                </span>
                                <span>
                                    <?= __("hectares/ day", "plenamata") ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="data">
                            <h4><?= $deforestedTitle ?? '' ?></h4>

                            <div class="area">
                                <span data-mask="true">
                                    <?= $warnings ?? '' ?>
                                </span>
                                <span data-mask="true">
                                    <?= __("alerts", "plenamata") ?>
                                </span>
                            
                            </div>

                            <div class="area">
                                <span data-mask="true">
                                    <?= $hectares ?? '' ?>
                                </span>

                                <span>
                                    <?= __("hectares", "plenamata") ?>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="final-info">
                    <?= $finalInformation ?? '' ?>
                </div>
            </div>

        <?php
        $output = ob_get_clean();

        return $output;

    }
}