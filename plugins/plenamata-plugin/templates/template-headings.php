<?php

/**
 * A page with headings index
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
            <?= _x('Index', 'sections', 'plenamata') ?>
        </h2>
        <ul>
            <template class="about__nav-anchor"></template>
        </ul>
    </nav>

    <main>
        <div id="section-0"></div>
        <?php the_content() ?>
    </main>
</div>

<?php
get_footer();
?>
