<?php
/**
 * Template Name: Dashboard
 *
 * @package PlenamataPlugin
 * @since 0.1.0
 */
    $maps = get_posts(['post_type' => 'map', 'post_status' => 'publish']);
    $map_id = empty($maps) ? 0 : $maps[0]->ID;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class() ?>>
    <div class="vue-dashboard-app"></div>
    <div class="jeomap map_id_<?= $map_id ?>"></div>
    <?php wp_footer(); ?>
</body>
</html>
