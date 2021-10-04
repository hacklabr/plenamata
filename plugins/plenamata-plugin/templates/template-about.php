<?php

/**
 * The "About" page
 */

get_header( 'single' );
the_post();

$contact = get_page_by_path('contact');
$contact = apply_filters('wpml_object_id', $contact->ID, 'page', 'FALSE');
$contact = get_post($contact);
?>

<header class="about__header">
    <h1><?= get_the_title() ?></h1>
</header>
<div class="about__body">
    <nav>
        <h2>
            <?= __('Sections', 'plenamata') ?>
        </h2>
        <ul>
            <li>
                <a href="#">
                    <?= __('The initiative', 'plenamata') ?>
                </a>
            </li>
            <li class="about__nav-anchor">
                <a href="<?= get_permalink($contact) ?>">
                    <?= $contact->post_title ?>
                </a>
            </li>
        </ul>
    </nav>

    <main>
        <?php the_content() ?>
    </main>
</div>

<?php
get_footer();
?>
