<?php
/**
 * The template for displaying author pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newspack
 */

get_header();
?>
    <header class="page-header author">
        <div class="breadcrumb">
            <a href="<?= site_url(); ?>"><?= __( 'Home', 'plenamata' ) ?></a> /
            <a href="<?= get_category_link( get_category_by_slug( 'opinion' ) ) ?>"><?= __( 'Columnists', 'plenamata' ) ?></a> /
        </div>
        <div class="author-main-content">
            <?php
                $queried       = get_queried_object();
                $author_avatar = '';

                if ( function_exists( 'coauthors_posts_links' ) ) {
                    // Check if this is a guest author post type.
                    if ( 'guest-author' === get_post_type( $queried->{ 'ID' } ) ) {
                        // If yes, make sure the author actually has an avatar set; otherwise, coauthors_get_avatar returns a featured image.
                        if ( get_post_thumbnail_id( $queried->{ 'ID' } ) ) {
                            $author_avatar = coauthors_get_avatar( $queried, 120 );
                        } else {
                            // If there is no avatar, force it to return the current fallback image.
                            $author_avatar = get_avatar( ' ' );
                        }
                    } else {
                        $author_avatar = coauthors_get_avatar( $queried, 120 );
                    }
                } else {
                    $author_id     = get_query_var( 'author' );
                    $author_avatar = get_avatar( $author_id, 194);
                }

                if ( $author_avatar ) {
                    echo wp_kses( $author_avatar, newspack_sanitize_avatars() );
                }
            ?>
            <div class="author-content">
                <h1><?php echo get_the_author_meta('first_name'); ?> <?php echo get_the_author_meta('last_name'); ?></h1>
                <?php newspack_author_social_links( get_the_author_meta( 'ID' ), 28 ); ?>
            </div>
        </div>

    </header><!-- .page-header -->
    <section id="primary" class="content-area">


        <?php do_action( 'before_archive_posts' ); ?>
        <div class="about-the-author-section top-author">
            <?php if ( strlen(trim(get_the_author_meta('description'))) > 0 ) : ?>
                <p><?php echo get_the_author_meta('description'); ?></p>
            <?php endif; ?>
        </div>
        <main id="main" class="site-main">
            <?php
                if ( have_posts() ) :
                    // Start the Loop.
                    while ( have_posts() ) :
                        the_post();

                        get_template_part( 'template-parts/content/content', 'author-post' );

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
        <aside class="author-page-sidebar">
            <div>
                <?php if ( strlen(trim(get_the_author_meta('description'))) > 0 ) : ?>
                    <div class="about-the-author-section">
                        <h4><?php _e('ABOUT THE AUTHOR', 'plenamata') ?></h4>
                        <p><?php echo get_the_author_meta('description'); ?></p>
                    </div>
                <?php endif; ?>
                <?php dynamic_sidebar('author_page_sidebar') ?>
            </div>
        </aside>
    </section><!-- #primary -->
<?php
get_footer();
