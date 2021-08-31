<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Newspack
 */

global $post;
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
$post_title = wp_kses_post(get_the_title());

$parent_type_category = get_category_by_slug('type');

if($parent_type_category) {
	$parent_type_category = $parent_type_category->cat_ID;
}

$post_categories = get_the_category();
$post_child_category = null;

foreach ($post_categories as $post_cat) {
	if ($parent_type_category == $post_cat->parent) {
		$post_child_category = $post_cat;
		break;
	}
}

$authors = [];
if (function_exists('get_coauthors')) {
	$authors = get_coauthors();
}

$author_count = count($authors);
$twitter_nicknames_text = ', by ';

$i = 1;
if (get_post_meta(get_the_ID(), 'authors-listing', true)) :
	foreach ($authors as $author) {

		$i++;
		if ($author_count === $i) :
			/* translators: separates last two author names; needs a space on either side. */
			$sep = esc_html__(' and ', 'newspack');
		elseif ($author_count > $i) :
			/* translators: separates all but the last two author names; needs a space at the end. */
			$sep = esc_html__(', ', 'newspack');
		else :
			$sep = '';
		endif;

		if (esc_attr(get_the_author_meta('twitter', $author->ID))) {
			$twitter_nicknames_text .= '@' . esc_attr(get_the_author_meta('twitter', $author->ID)) . $sep;
		} else {
			$twitter_nicknames_text .=  get_the_author_meta('display_name', $author->ID) . $sep;
		}
	}
else :
	$twitter_nicknames_text = '';
endif;

$urlTweetShare = urldecode(get_the_title() . ' ' . get_the_permalink() . $twitter_nicknames_text);



	// Header Settings
	$header_simplified     = get_theme_mod('header_simplified', false);
	$header_center_logo    = get_theme_mod('header_center_logo', false);
	$show_slideout_sidebar = get_theme_mod('header_show_slideout', false);
	$header_sub_simplified = get_theme_mod('header_sub_simplified', false);

    require PLENAMATA_PLUGIN_PATH . 'templates/parts/header/mobile-sidebar.php';
	get_template_part('template-parts/header/desktop', 'sidebar');

	if (true === $header_sub_simplified && !is_front_page()) :
		get_template_part('template-parts/header/subpage', 'sidebar');
	endif;
	?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php _e('Skip to content', 'newspack'); ?></a>
		<button id="search-toggle" style="display:none">
			<span></span>
		</button>

		<header id="masthead" class="site-header hide-header-search" [class]="searchVisible ? 'show-header-search site-header ' : 'hide-header-search site-header'">
		
			<div id="header-search" class="tablet-down-search" [aria-expanded]="searchVisible ? 'true' : 'false'" aria-expanded="false">
				<div class="wrapper">
					<div class="content-limiter">
						<span class="search-text"><?= __('What are you looking for?', 'jeo'); ?></span>
						<?php get_search_form(); ?>
					</div>
				</div>
			</div><!-- #header-search -->

				<div class="bottom-header-contain post-header">
					<div class="wrapper">
						<div class="left">
							<div class="subpage-toggle-contain desktop">
								<button class="mobile-menu-toggle" on="tap:subpage-sidebar.toggle">
									<?php echo wp_kses(newspack_get_icon_svg('menu', 20), newspack_sanitize_svgs()); ?>
									<span class="screen-reader-text"><?php esc_html_e('Menu', 'newspack'); ?></span>
								</button>
							</div>
							<div>
								<?php get_template_part('template-parts/header/site', 'branding'); ?>
							</div>
						</div>

						<p class="title"> <?php echo esc_html(wp_kses_post(get_the_title())); ?></p>
						<?php get_template_part( 'template-parts/content/content', 'social-networks' ); ?>
						<div class="subpage-toggle-contain mobile">
								<button class="mobile-menu-toggle" on="tap:subpage-sidebar.toggle">
									<?php echo wp_kses(newspack_get_icon_svg('menu', 20), newspack_sanitize_svgs()); ?>
									<span class="screen-reader-text"><?php esc_html_e('Menu', 'newspack'); ?></span>
								</button>
							</div>
					</div><!-- .wrapper -->
				</div><!-- .bottom-header-contain -->
		</header><!-- #masthead -->

		<?php
		if (function_exists('yoast_breadcrumb')) {
			yoast_breadcrumb('<div class="site-breadcrumb desktop-only"><div class="wrapper">', '</div></div>');
		}
		?>


