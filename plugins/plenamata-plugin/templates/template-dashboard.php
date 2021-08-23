<?php
/**
 * Template Name: Dashboard
 *
 * @package PlenamataPlugin
 * @since 0.1.0
 */

    $year = date('Y');
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
    <div class="vue-app">
        <plenamata-dashboard inline-template>
            <div class="dashboard">
                <header class="dashboard__header">
                    <h1><?= __('Painel de dados', 'plenamata') ?></h1>
                    <form>
                        <div>
                            <label for="estados"><?= __('Estado', 'plenamata') ?></label>
                            <select v-model="state">
                                <option value=""><?= __('Todos os estados', 'plenamata') ?></option>
                                <option v-for="state of states" :key="state.uf" :value="state.uf">{{ state.name }}</option>
                            </select>
                        </div>
                    </form>
                </header>

                <main>
                    <div class="dashboard__tabs">
                        <div class="dashboard__tab" :class="{ active: view === 'data' }" @click="view = 'data'">
                            <?= __('Dados', 'plenamata') ?>
                        </div>
                        <div class="dashboard__tab" :class="{ active: view === 'news' }" @click="view = 'news'">
                            <?= __('Notícias', 'plenamata') ?>
                        </div>
                    </div>

                    <div v-if="view === 'data'">
                        <section class="dashboard-panel" >
                            <main>
                                <h2>
                                    <?= sprintf(__('Estimativa de árvores derrubadas em %s', 'plenamata'), $year) ?>
                                </h2>
                                <div class="dashboard-panel__measure">
                                    <span class="dashboard-panel__icon" aria-hidden="true">
                                        <img src="<?= PLENAMATA_PLUGIN_URL . 'assets/build/img/tree-icon.svg' ?>">
                                    </span>
                                    <span class="dashboard-panel__number">
                                        {{ trees | humanNumber }}
                                    </span>
                                    <span class="dashboard-panel__unit">
                                        <?= __( 'árvores', 'plenamata' ) ?>
                                    </span>
                                </div>
                                <div class="dashboard-panel__meaning">
                                    <?= __('estimativa média de', 'plenamata') ?> {{ treesPerMinute | roundNumber }} <?= __('árvores por minuto', 'plenamata') ?>
                                </div>
                            </main>
                            <footer>
                                Fonte: INPE/DETER • Última atualização: 19.07.2021 com alertas detectados até 09.07.2021
                            </footer>
                        </section>
                    </div>
                </main>
            </div>
        </plenamata-dashboard>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
