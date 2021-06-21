<?php 

function footer_custom_options($wp_customize) {
    $prefix = 'footer';
    $section = 'footer_area';


	$wp_customize->add_section(
		$section,
		array(
			'title' => esc_html__('Footer', 'jaci'),
			'section' => $section,
		)
	);

    $wp_customize->add_setting(
		$prefix . '_copyright_text',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$wp_customize->add_control(
		$prefix . '_copyright_text',
		array(
			'label'       => __( 'Footer copyright text', 'jaci' ),
			'description' => __( 'Leave it empty to hide all copyright info.', 'jaci' ),
			'section'     => $section,
			'default'     => '',
			'type'        => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

    $wp_customize->add_setting(
		$prefix . '_show_year_and_name',
		array(
			'default'  => false,
			'sanitize_callback' => 'sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		$prefix . '_show_year_and_name',
		array(
			'type' => 'checkbox',
			'section' => $section,
			'label' => __('Display year and site name aside copyright info?', 'jaci'),
		)
	);
}


add_action('customize_register', 'footer_custom_options', 99);