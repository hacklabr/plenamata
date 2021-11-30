<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newspack
 */

$frontpage = get_post( get_option( 'page_for_posts' ) );

get_header();
?>
    <section id="primary" class="content-area custom-archive">
        <header class="page-header">
            <span>
                <?php if ( is_archive() ): ?>
                    <?php the_archive_title( '<h1 class="page-title article-section-title category-header">', '</h1>' ); ?>
                <?php elseif ( is_home() ): ?>
                    <h1 class="page-title article-section-title category-header"><?= $frontpage->post_title ?></h1>
                <?php endif; ?>
            </span>

        </header><!-- .page-header -->

        <?php do_action( 'before_archive_posts' ); ?>

        <main id="main" class="site-main">

        <?php if ( '' !== get_the_archive_description() ) : ?>
            <div class="taxonomy-description">
                <?php echo wp_kses_post( wpautop( get_the_archive_description() ) ); ?>
            </div>
        <?php elseif ( is_home() ): ?>
            <div class="taxonomy-description">
                <p><?= __( 'Content on deforestation in the Amazon, with the curatorship of news stories, studies, special reports, opinion articles, and good initiatives, which aim at contributing to the preservation and ending deforestation in the region.', 'plenamata' ) ?></p>
            </div>
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
