<?php
/**
 * Template Name: Real-time Scoreboard
 *
 * @package PlenamataPlugin
 * @since 0.1.0
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title() ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="realtime-scoreboard">
        <h1><?= get_the_title() ?></h1>

        <div class="realtime-scoreboard__primary">
            <div data-deter="treesEstimative"><?= __('Loading...', 'plenamata') ?></div>
            <div><?= __('trees cut down so far', 'plenamata') ?></div>
        </div>

        <div>
            <div class="realtime-scoreboard__secondary">
                <div>
                    <div data-deter="treesPerDay"><?= __('Loading...', 'plenamata') ?></div>
                    <div><?= __('trees per day', 'plenamata') ?></div>
                </div>

                <div>
                    <div data-deter="hectaresPerDay"><?= __('Loading...', 'plenamata') ?></div>
                    <div><?= __('hectares per day', 'plenamata') ?></div>
                </div>
            </div>
        </div>

        <footer class="realtime-scoreboard__footer">
            <div class="realtime-scoreboard__source">
                <p><?= __('Sources: DETER/INPE and MapBiomas', 'plenamata') ?></p>
            </div>

            <div class="realtime-scoreboard__logo">
                <img src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/logo-black-text.svg" alt="Plenamata">
            </div>

            <div class="realtime-scoreboard__link">
                <a href="https://plenamata.eco">https://plenamata.eco</a>
            </div>
        </footer>
    </div>
    <?php wp_footer(); ?>
</body>
</html>