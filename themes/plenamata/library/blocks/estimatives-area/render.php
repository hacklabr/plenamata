<?php 

function estimatives_area_render_callback($block_attributes, $content) {
    ob_start();
    set_query_var( 'block_params', ['attributes' => $block_attributes]);
	get_template_part( 'library/blocks/estimatives-area/', 'render');
	$output = ob_get_clean();

    return $output ;
}