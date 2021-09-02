<?php
    $sections = get_terms( [ 'taxonomy' => 'secao', 'hide_empty' => false ] );
    get_header();
?>

    <header class="glossary__header">
        <h1><?php echo __( 'Glossary', 'plenamata' ) ?></h1>
        <?php echo '<p>'. __('Termos técnicos, políticas públicas e iniciativas para chegar ao desmatamento zero e conservar a floresta explicados com um simples clique, via consulta rápida ou completa.', 'plenamata').'</p>'?>

        <div class="glossary__search">
            <input id="filtro-glossario" class="search-input" type="text" placeholder="Busque por um termo" name="search" />
            

            <svg class="search-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.7246 17.2913L15.8305 13.3971C15.6547 13.2214 15.4165 13.1237 15.1665 13.1237H14.5298C15.6079 11.7449 16.2484 10.0107 16.2484 8.12421C16.2484 3.63636 12.612 0 8.12421 0C3.63636 0 0 3.63636 0 8.12421C0 12.612 3.63636 16.2484 8.12421 16.2484C10.0107 16.2484 11.7449 15.6079 13.1237 14.5298V15.1665C13.1237 15.4165 13.2214 15.6547 13.3971 15.8305L17.2913 19.7246C17.6584 20.0918 18.2521 20.0918 18.6154 19.7246L19.7207 18.6193C20.0879 18.2521 20.0879 17.6584 19.7246 17.2913ZM8.12421 13.1237C5.36276 13.1237 3.12469 10.8896 3.12469 8.12421C3.12469 5.36276 5.35885 3.12469 8.12421 3.12469C10.8857 3.12469 13.1237 5.35885 13.1237 8.12421C13.1237 10.8857 10.8896 13.1237 8.12421 13.1237Z" fill="#523096"/>
            </svg>
        </div>

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
                'order' => 'ASC',
                'numberposts' => -1,
                'meta_query' => [
                    [ 'key' => 'section', 'value' => $section->term_id ],
                ],
            ] );
        ?>
        <?php if ( !empty( $entries ) ): ?>
            <section id="section_<?= $section->slug ?>">
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
            </section>
        <?php endif; ?>
        <?php endforeach; ?>
        </main>
            </div>

    <?php get_footer();?>