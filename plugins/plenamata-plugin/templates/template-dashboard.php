<?php
/**
 * Template Name: Dashboard
 *
 * @package PlenamataPlugin
 * @since 0.1.0
 */
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
                    <h1><?php echo __( 'Painel de dados', 'plenamata' ) ?></h1>
                    <form>
                        <div>
                            <label for="estados"><?php echo __( 'Estado', 'plenamata' ) ?></label>
                            <select v-model="state">
                                <option value=""><?php echo __( 'Todos os estados', 'plenamata' ) ?></option>
                                <option v-for="state of states" :key="state.uf" :value="state.uf">{{ state.name }}</option>
                            </select>
                        </div>
                    </form>
                </header>
            </div>
        </plenamata-dashboard>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
