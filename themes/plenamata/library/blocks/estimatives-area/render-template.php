<?php 
$params = get_query_var('block_params', false);
extract($params['attributes']);
var_dump($params['attributes']);
?>

<div className="estimatives-area">
    <div className="heading">
        <h4><?= $boxTitle ?></h4>
        <h3> <?= $headingTitle ?> </h3>
    </div>

    <div className="main-data">
        <h4><?= $preNumberTitle ?></h4>

        <div className="number">
            <span id="trees-estimative" data-base-trees="<?= $baseTrees ?>" data-trees-per-day="<?= $tressPerDay ?>" data-date="<?= $baseDate ?>">...</span>
        </div>
    </div>


    <div className="base-data">
        <div>
            <div className="data">
                <div className="area">
                    <span>
                        <?= $tressPerDay ?>
                    </span>
                </div>

                <div className="area">
                    <span>
                        <?= $hecPerDay ?>
                    </span>
                </div>
            </div>
        </div>
        <div>
            <div className="data">
                <div className="area">
                    <span>
                        <?= $warnings ?>
                    </span>

                    <span>
                        <?= __("Alertas", "jaci") ?>
                    </span>
                
                </div>

                <div className="area">
                    <span>
                        <?= $hectares ?>
                    </span>

                    <span>
                        <?= __("hectares", "jaci") ?>
                    </span>
                </div>
            </div>

        </div>
    </div>

    <div className="final-info">
        <?= $finalInformation ?>
    </div>
</div>