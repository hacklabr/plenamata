<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newspack
 */

$newspage = get_post( get_option( 'page_for_posts' ) );

get_header();
?>
    <section id="primary" class="content-area custom-archive">
        <header class="page-header">
            <?php if ( is_category() ): ?>
                <?php $category = get_category( get_query_var( 'cat' ) ); ?>
                <div class="breadcrumb">
                    <a href="<?= site_url(); ?>"><?= __( 'Home', 'plenamata' ) ?></a> /
                    <a href="<?= get_post_type_archive_link( 'post' ) ?>"><?= __( 'News', 'plenamata' ) ?></a> /
                    <?php if ( $category->parent > 0 ): ?>
                        <?php $parent = get_category( $category->parent ); ?>
                        <a href="<?= get_category_link( $parent->term_id ) ?>"><?= $parent->name ?></a> /
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <span>
                <?php if ( is_archive() ): ?>
                    <?php the_archive_title( '<h1 class="page-title article-section-title category-header">', '</h1>' ); ?>
                <?php elseif ( is_home() ): ?>
                    <h1 class="page-title article-section-title category-header"><?= $newspage->post_title ?></h1>
                <?php endif; ?>
            </span>

        </header><!-- .page-header -->

        <?php do_action( 'before_archive_posts' ); ?>

        <main id="main" class="site-main">

        <?php if ( is_home() ): ?>
            <div class="taxonomy-description">
                <p><?= $newspage->post_content ?></p>
            </div>
            <ul class="categories-list">
                <?php $categories = get_categories(); ?>
                <?php foreach ( $categories as $cat ): ?>
                    <?php if ( $cat->parent === 0 ): ?>
                        <li>
                            <a href="<?= get_category_link( $cat->term_id ) ?>">
                                <?= $cat->name ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <?php if ( '' !== get_the_archive_description() ) : ?>
                <div class="taxonomy-description">
                    <?php echo wp_kses_post( wpautop( get_the_archive_description() ) ); ?>
                </div>
            <?php endif; ?>

            <?php if ( is_category() ): ?>
                <?php $categories = get_categories( [ 'parent' => $category->term_id ] ); ?>
                <?php if ( count( $categories ) > 0 ): ?>
                    <ul class="categories-list">
                        <?php foreach ( $categories as $cat ): ?>
                            <li>
                                <a href="<?= get_category_link( $cat->term_id ) ?>">
                                    <?= $cat->name ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        if ( have_posts() ) :
            $post_count = 0;
            ?>

            <?php
            // Start the Loop.
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content/content', 'excerpt' );

                // End the loop.
            endwhile;

            // Previous/next page navigation.
            echo (get_theme_mod('pagination_style', 'rectangle') == 'circle'? '<div class="circle">' : '<div class="rectangle">');
            newspack_the_posts_navigation();
            echo '</div>';

            // If no content, include the "No posts found" template.
        else :
            get_template_part( 'template-parts/content/content', 'none' );

        endif;
        ?>
        </main><!-- #main -->
        <aside class="category-page-sidebar">
            <div class="content">
                <?php dynamic_sidebar('category_page_sidebar') ?>
            </div>
        </aside>
    </section><!-- #primary -->
<?php
get_footer();
