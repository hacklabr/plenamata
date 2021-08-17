<?php
    $sections = get_terms( [ 'taxonomy' => 'secao', 'hide_empty' => false ] );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __( 'Glossário', 'plenamata' ) ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'glossary' ) ?>>
    <h1><?php __( 'Glossário', 'plenamata' ) ?></h1>
    <div>
        <nav class="sections">
            <h2>
                <?php echo __( 'Seções', 'plenamata' ) ?>
            </h2>
            <ul>
                <?php foreach ( $sections as $section ): ?>
                    <li>
                        <a href="#<?php echo $section->slug ?>">
                            <?php echo $section->name ?>
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
            <h2 id="<?php echo $section->slug ?>">
                <?php echo __( $section->name, 'plenamata' ) ?>
            </h2>

            <div class="entries">
            <?php foreach ( $entries as $entry ): ?>
                <details>
                    <summary>
                        <?php echo $entry->post_title ?>
                    </summary>
                    <div>
                        <?php echo $entry->post_excerpt ?>
                        <p>
                            <a href="<? echo get_permalink( $entry->ID ) ?>">
                                <?php echo __( 'Acessar verbete completo', 'plenamata' ) ?>
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
</body>
</html>
