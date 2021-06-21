<?php

function theme_supports() {
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'core-block-patterns' );
    add_theme_support( 'custom-line-height' );
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'custom-units' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'editor-color-palette');
    add_theme_support( 'editor-gradient-presets' );
    add_theme_support( 'editor-font-sizes' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'widgets' );
}
add_action( 'after_setup_theme', 'theme_supports' );
add_action( 'after_setup_theme', 'jeo_setup' );

function jeo_setup() {
    // die(get_stylesheet_directory() . '/lang');
	load_theme_textdomain( 'jaci', get_stylesheet_directory() . '/languages' );
}