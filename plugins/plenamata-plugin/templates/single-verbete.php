<?php
    $active_section = get_post_meta( get_the_ID(), 'section', true );
    $archive_link = get_post_type_archive_link( 'verbete' );
    $featured_video = get_post_meta( get_the_ID(), 'featured_video', true );
    $sections = get_terms( [ 'taxonomy' => 'secao', 'hide_empty' => false ] );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'glossary glossary-entry' ) ?>>
    <header class="glossary__header">
        <h1><?php echo __( 'Glossário', 'plenamata' ) ?></h1>
    </header>
    <div class="glossary__body">
        <nav>
            <h2>
                <?php echo __( 'Seções', 'plenamata' ) ?>
            </h2>
            <ul>
                <?php foreach ( $sections as $section ): ?>
                    <li class="<?php echo ($section->term_id == $active_section['term_id'] ?  'active' : '') ?>">
                        <a href="<?php echo $archive_link ?>#<?php echo $section->slug ?>">
                            <?php echo $section->name ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <main>
            <h2><?php the_title() ?></h2>
            <div class="glossary-entry__excerpt">
                <p><?php the_excerpt() ?></p>
                <?php if ( !empty( $featured_video ) ): ?>
                    <video autoplay muted loop playsinline src="<?php echo $featured_video['guid'] ?>"></video>
                <?php else: ?>
                    <?php the_post_thumbnail( 'large' ) ?>
                <?php endif; ?>
            </div>
            <div class="glossary-entry__content">
                <?php the_content() ?>
            </div>
        </main>
    </div>
</body>
</html>
