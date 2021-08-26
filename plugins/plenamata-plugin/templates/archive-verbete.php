<?php
    $sections = get_terms( [ 'taxonomy' => 'secao', 'hide_empty' => false ] );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __( 'Glossary', 'plenamata' ) ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'glossary' ) ?>>
    <header class="glossary__header">
        <h1><?= __( 'Glossary', 'plenamata' ) ?></h1>
    </header>
    <div class="glossary__body">
        <nav>
            <h2>
                <?= __( 'Sections', 'plenamata' ) ?>
            </h2>
            <ul>
                <?php foreach ( $sections as $section ): ?>
                    <li>
                        <a href="#<?= $section->slug ?>">
                            <?= $section->name ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            </nav>

        <main>
        <?php foreach ( $sections as $section ): ?>
        <?php
            $entries = get_posts( [
                'post_type' => 'verbete',
                'meta_query' => [
                    [ 'key' => 'section', 'value' => $section->term_id ],
                ],
            ] );
        ?>
        <?php if ( !empty( $entries ) ): ?>
            <h2 id="<?= $section->slug ?>">
                <?= __( $section->name, 'plenamata' ) ?>
            </h2>

            <div class="glossary__entries">
            <?php foreach ( $entries as $entry ): ?>
                <details class="glossary__entry">
                    <summary>
                        <?= $entry->post_title ?>
                    </summary>
                    <div class="glossary__entry-detail">
                        <?= $entry->post_excerpt ?>
                        <p>
                            <a href="<?= get_permalink( $entry->ID ) ?>">
                                <?= __( 'Acessar verbete completo', 'plenamata' ) ?>
                            </a>
                        </p>
                    </div>
                </details>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php endforeach; ?>
        </main>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
