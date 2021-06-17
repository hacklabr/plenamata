<?php 

function widgets_areas() {
    register_sidebar(array(
		'name'          => 'Before Footer logos area',
		'id'            => 'before_footer_logos_area',
		'before_widget' => '<div class="before-footer-logos-area">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));

    register_sidebar(array(
		'name'          => 'Footer logos area',
		'id'            => 'footer_logos_area',
		'before_widget' => '<div class="footer-logos-area">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
}

add_action('widgets_init', 'widgets_areas');
