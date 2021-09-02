<?php
/**
 * Template Name: Dashboard
 *
 * @package PlenamataPlugin
 * @since 0.1.0
 */
    $maps = get_posts(['post_type' => 'map', 'post_status' => 'publish']);
    $map_id = empty($maps) ? 0 : $maps[0]->ID;
    get_header();
?>
<div class="vue-dashboard-app"></div>
<div class="jeomap map_id_<?= $map_id ?>"></div>
<?php get_footer(); ?>
