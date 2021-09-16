<?php
/**
 * Template Name: Dashboard
 *
 * @package PlenamataPlugin
 * @since 0.1.0
 */
    $language = apply_filters( 'wpml_current_language', NULL );
    $options = get_option( 'plenamata_options', [] );
    $map_id = $options[ 'plenamata_dashboard_map_' . $language ];
    get_header();
?>
<div class="vue-dashboard-app"></div>
<div class="jeomap map_id_<?= $map_id ?>"></div>
<?php get_footer(); ?>
