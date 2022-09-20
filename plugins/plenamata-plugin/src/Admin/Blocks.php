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
        /**
         * Override the block core/latest-posts
         */
        remove_action( 'init', 'register_block_core_latest_posts', 10 );
        require_once PLENAMATA_PLUGIN_PATH . 'blocks/latest-posts.php';

		add_action( 'init', [ $this, 'register_blocks' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'register_text_formats' ] );
	}

    public function filters(): void {
        add_filter( 'theme_page_templates', [ $this, 'custom_page_templates' ], 10, 1 );
    }

    public function custom_page_templates( array $templates ): array {
        $templates[ 'template-dashboard.php' ] = __( 'Dashboard', 'plenamata' );
        $templates[ 'template-headings.php' ] = __( 'Page with headings index', 'plenamata' );
        $templates[ 'template-scoreboard.php' ] = __( 'Real-time Scoreboard', 'plenamata' );

        return $templates;
    }

    /**
	 * Register the JavaScript for the admin blocks.
     *
     * @return void
	 */
    public function register_blocks(): void {
        wp_register_script(
            'plenamata-plugin-blocks',
			PLENAMATA_PLUGIN_URL . 'assets/build/js/admin/blocks.js',
            [ 'wp-blocks', 'wp-i18n' ],
			Plugin::VERSION,
			false
		);

        wp_localize_script(
            'plenamata-plugin-blocks',
            'PlenamataBlocks',
            [
                'i18n' => [
                    '__' => [
                        'Contact information' => __( 'Contact information', 'plenamata' ),
                        'from November 1st to 12th in Glasgow' => __( 'from November 1st to 12th in Glasgow', 'plenamata' ),
                        'How to get involved' => __( 'How to get involved', 'plenamata' ),
                        'Name' => __( 'Name', 'plenamata' ),
                        'Project website' => __( 'Project website', 'plenamata' ),
                        'Social media' => __( 'Social media', 'plenamata' ),
                        'The Initiative' => __( 'The Initiative', 'plenamata' ),
                        'Upload image' => __( 'Upload image', 'plenamata' ),
                        'What is it' => __( 'What is it', 'plenamata' ),
                        'Where is it' => __( 'Where is it', 'plenamata' ),
                        "Who's involved" => __( "Who's involved", 'plenamata' ),
                    ],
                ],
            ]
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
            'script' => 'estimatives-area-front-end',
            'render_callback' => [ $this, 'estimatives_area_render_callback' ],
            'attributes' => [
                // Strings
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
                "warnings" => [
                    "type" => "string"
                ],
            ],
        ] );

        register_block_type( 'plenamata/cop26-banner', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
            'attributes' => [
                'buttonLink' => [
                    'type' => 'string'
                ],
                'buttonText' => [
                    'type' => 'string'
                ],
                'title' => [
                    'type' => 'string'
                ],
            ],
        ]);

        register_block_type( 'plenamata/deforestation-info', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
            'script' => 'estimatives-area-front-end',
            'attributes' => [
                'boxTitle' => [
                    'type' => 'string'
                ],
            ],
        ] );

        register_block_type( 'plenamata/deforestation-charts', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
            'script' => 'deforestation-charts-front-end',
            'attributes' => [
                'boxTitle' => [
                    'type' => 'string'
                ],
                'parenthical' => [
                    'type' => 'string'
                ],
            ],
        ] );

        register_block_type( 'plenamata/get-involved', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
            'attributes' => [
                'contactInfo' => [
                    'type' => 'string',
                ],
                'content' => [
                    'type' => 'string',
                ],
                'socialNetworks' => [
                    'type' => 'string',
                ],
                'website' => [
                    'type' => 'string',
                ],
            ],
        ] );

        register_block_type( 'plenamata/initiative', [
            'api_version' => 2,
            'editor_script' => 'plenamata-plugin-blocks',
            'editor_style' => 'plenamata-plugin-blocks',
            'attributes' => [
                'name' => [
                    'type' => 'string'
                ],
                'what' => [
                    'type' => 'string'
                ],
                'where' => [
                    'type' => 'string',
                ],
                'whereImage' => [
                    'type' => 'string',
                ],
                'who' => [
                    'type' => 'string'
                ],
            ]
        ]);
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
                        <span data-deter="treesEstimative"><?= __('Loading...', 'plenamata') ?></span>
                        <span><?= __("real-time estimate", "plenamata") ?><span class="vue-estimate-tooltip"></span></span>
                    </div>
                </div>

                <div class="base-data">
                    <div>
                        <div class="data">
                            <h4><?= $averageTitle ?? '' ?></h4>
                            <div class="area">
                                <img src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/tree-icon.svg" alt="">
                                <span data-deter="treesPerDay"></span>
                                <span><?= __("trees", "plenamata") ?></span>
                            </div>

                            <div class="area">
                                <img src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/area-icon.svg" alt="">
                                <span data-deter="hectaresPerDay"></span>
                                <span><?= __("hectares", "plenamata") ?></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="data">
                            <h4><?= $deforestedTitle ?? '' ?></h4>

                            <div class="area">
                                <img src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/alert-icon.svg" alt="">
                                <span data-mask="true"><?= $warnings ?? '' ?></span>
                                <span><?= __("alerts", "plenamata") ?></span>
                            </div>

                            <div class="area">
                                <img src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/area-icon.svg" alt="">
                                <span data-deter="hectaresThisYear"></span>
                                <span><?= __("hectares", "plenamata") ?></span>
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

    /**
	 * Register the JavaScript for the admin text formats.
     *
     * @return void
	 */
    public function register_text_formats(): void {
        wp_register_script(
            'plenamata-plugin-formats',
			PLENAMATA_PLUGIN_URL . 'assets/build/js/admin/formats.js',
            [ 'wp-blocks', 'wp-i18n', 'wp-rich-text' ],
			Plugin::VERSION,
			false
		);

        wp_enqueue_script( 'plenamata-plugin-formats' );
    }
}