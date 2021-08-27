<?php

get_header(); 
?>

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
                    <li class="<?= ($section->term_id == $active_section['term_id'] ?  'active' : '') ?>">
                        <a href="<?= $archive_link ?>#<?= $section->slug ?>">
                            <?= $section->name ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <main>
            <h2><?php the_title() ?></h2>
            <div class="glossary-entry__excerpt">
                <?php if ( !empty( $featured_video ) ): ?>
                    <video autoplay muted loop playsinline src="<?= $featured_video['guid'] ?>"></video>
                <?php else: ?>
                    <?php the_post_thumbnail( 'large' ) ?>
                <?php endif; ?>
                <p><?php the_excerpt() ?></p>
            </div>
            <div class="glossary-entry__content">
                
                <?php the_content() ?>

                <?php if ( !empty( $tags ) ): ?>
                    <div class="glossary-entry__subsection">
                        <h3> <?php echo __( 'Tags:', 'plenamata' ) ?> </h3>
                    </div>

                    <ul class="glossary-entry__tags">
                        <?php foreach ( $tags as $tag ): ?>
                            <li class="glossary-entry__tag">
                                <a href="<?= get_term_link( $tag ) ?>"><?= $tag->name ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                <?php endif; ?>
            </div>
        </main
    </div>
    <?php get_footer(); ?>
</body>
</html>
