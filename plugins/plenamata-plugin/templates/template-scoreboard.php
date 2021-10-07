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
        <h1><?= sprintf(__('Deforestation in the Legal Amazon in %s', 'plenamata'), date('Y')) ?></h1>

        <div class="realtime-scoreboard__primary">
            <div data-deter="treesEstimative">888.888.888</div>
            <div><?= __('trees cut down so far', 'plenamata') ?></div>
        </div>

        <div>
            <div class="realtime-scoreboard__secondary">
                <div>
                    <div data-deter="treesPerDay">8.888.888</div>
                    <div><?= __('trees per day', 'plenamata') ?></div>
                </div>

                <div>
                    <div data-deter="hectaresPerDay">8.888</div>
                    <div><?= __('hectares per day', 'plenamata') ?></div>
                </div>
            </div>
            <div class="realtime-scoreboard__source">
                <p><?= __('Sources: DETER/INPE and MapBiomas', 'plenamata') ?></p>
            </div>
        </div>

        <div><?php the_content() ?></div>

        <footer class="realtime-scoreboard__footer">
            <div>
                <img src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/scoreboard/plenamata.svg" alt="Plenamata">
                <a href="https://plenamata.eco">https://plenamata.eco</a>
            </div>
            <div>
                <h2><?= _x('Creators', 'credits', 'plenamata') ?></h2>
                <div class="realtime-scoreboard__logos">
                    <img class="square" src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/scoreboard/natura.svg" alt="Natura">
                    <img class="rectangle" src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/scoreboard/infoamazonia.svg" alt="InfoAmazonia">
                    <img class="square" src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/scoreboard/mapbiomas.svg" alt="MapBiomas">
                    <img class="rectangle" src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/scoreboard/hacklab.svg" alt="Hacklab">
                    <img class="rectangle" src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/scoreboard/naturaeco.svg" alt="Natura &co">
                </div>
            </div>
        </footer>
    </div>
    <?php wp_footer(); ?>
</body>
</html>