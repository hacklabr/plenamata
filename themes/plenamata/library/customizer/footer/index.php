<?php 

function footer_custom_options($wp_customize) {
	$wp_customize->add_section(
		'footer_area',
		array(
			'title' => esc_html__('Footer', 'jeo'),
			'section' => 'footer_area',
		)
	);

    $wp_customize->add_setting(
		'footer_copyright_text',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$wp_customize->add_control(
		'footer_copyright_text',
		array(
			'label'       => __( 'Footer copyright text', 'jaci' ),
			'description' => __( 'Leave it empty to hide all copyright info.', 'jaci' ),
			'section'     => 'footer_area',
			'default'     => '',
			'type'        => 'text',
		)
	);
}


add_action('customize_register', 'footer_custom_options', 99);