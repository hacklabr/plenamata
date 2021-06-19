<?php 
/**
 * Estimatives area
 */
function estimatives_area_block() {

	// automatically load dependencies and version
	$asset_file = include(get_stylesheet_directory() . '/dist/js/blocks/estimatives-area.asset.php');

	wp_register_script(
		'estimatives-area',
		get_stylesheet_directory_uri() . '/dist/js/blocks/estimatives-area.js',
		$asset_file['dependencies'],
		$asset_file['version']
		//filemtime(get_stylesheet_directory() . '/dist/imageBlock.js')
	);

	register_block_type('jaci/estimatives-area', array(
		'editor_script' => 'estimatives-area',
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
                "type" => "number"
            ],
            "hecPerDay" => [
                "type" => "number"
            ],
            "hectares" => [
                "type" => "number"
            ],
            "warnings" => [
                "type" => "number"
            ],
            "baseDate" => [
                "type" => "string"
            ]
        ],
		// 'editor_style'  => 'estimatives-area',
	));
}

add_action('init', 'estimatives_area_block');