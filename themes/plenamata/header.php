<!DOCTYPE html>
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes();?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset');?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1.0">
	<?php wp_head()?>
	<title><?= is_front_page() ? get_bloginfo('name') : wp_title()?></title>
	<link rel="icon" href="<?= get_site_icon_url() ?>" />
</head>
<body <?php body_class();?> >
	<header class="main-header">
        <div class="container">
            <div class="col-md-12 header-content">
                <div class="logo">
                    <a href="<?= home_url() ?>">
                        <?php 
                        $custom_logo_id = get_theme_mod( 'custom_logo' );
                        if ( has_custom_logo() ) {
                            the_custom_logo();
                        } else { ?>
                            <img src="<?= get_template_directory_uri() ?>/assets/images/logo.png" width="200" alt="<?= get_bloginfo( 'name' ) ?>">
                        <?php
                        }
                        ?>
                    </a>
                </div>

                <div class="menus">
                    <?= wp_nav_menu(['theme_location' => 'main-menu', 'container' => 'nav', 'menu_id' => 'main-menu', 'menu_class' => 'menu', 'container_class' => 'primary-menu']) ?>
                    <?= wpml_language_menu() ?>
                </div>
            </div>
        </div>
        
	</header>
	<div id="app">