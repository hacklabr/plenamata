<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Newspack
 */

get_header( 'single' );
the_post(); ?>

	<?php
    $categories = get_the_category();
    $category = $categories[0];
    $subcategories = [];

    foreach ($categories as $cat) {
        if ($cat->parent === 0) {
            $category = $cat;
        } else {
            $subcategories[] = $cat;
        }
    }

    $primary_category = get_post_meta(get_the_ID(), '_yoast_wpseo_primary_category', true);
    $primary_category = empty($primary_category) ? null : get_category($primary_category);

    $subcategory = $subcategories[0];
    if (!empty($primary_category) && $category->cat_ID !== $primary_category->cat_ID) {
        $subcategory = $primary_category;
    }

	$is_opinion = ($category->slug == "_newspack_opinion" || $category->slug == 'opiniao' || $category->slug == 'opinion') ? true : false;
    $is_initiative = ($category->slug === 'boas-iniciativas' || $category->slug === 'good-initiatives') ? true : false;

    if ($is_opinion): ?>
			<div class="opinion-header">
				<div class="breadcrumb">
					<a href="<?= site_url(); ?>">Home</a> /
					<a href="<?= get_post_type_archive_link('post'); ?>"><?=  __('News', 'plenamata') ?></a> /
					<a href="<?= get_category_link($category->cat_ID); ?>"><?= $category->name ?></a> /
				</div>
				<div class="container">
					<h1 class="entry-title">
						<?php echo wp_kses_post(get_the_title()); ?>
					</h1>
				</div>
			</div>
    <?php elseif ($is_initiative): ?>
        <div class="initiative-header">
            <div>
                <div class="initiative-header__category">
                    <img src="<?= PLENAMATA_PLUGIN_URL ?>assets/build/img/macaw-eye.svg" alt="">
                    <a href="<?= get_category_link(get_category_by_slug('good-initiatives')) ?>">
                        <?= __('Good Initiatives', 'plenamata') ?>
                    </a>
                    <?php if (!empty($subcategory)): ?>
                        <span>/</span>
                        <a href="<?= get_category_link($subcategory->cat_ID) ?>">
                            <?= $subcategory->name ?>
                        </a>
                    <?php endif; ?>
                </div>
                <h1 class="initiative-header__title"><?= wp_kses_post(get_the_title()) ?></h1>
            </div>
            <div class="initiative-header__thumbnail credited-image-block">
                <div class="image-wrapper">
                    <?php the_post_thumbnail() ?>
                    <?php if ((class_exists('Newspack_Image_Credits') && !empty(Newspack_Image_Credits::get_media_credit(get_post_thumbnail_id())['credit'])) || !empty(get_post(get_post_thumbnail_id())->post_content)): ?>
                        <div class="image-info-wrapper">
                            <div class="image-meta">
                                <?php if (class_exists('Newspack_Image_Credits')): ?>
                                    <p class="description"><?= get_the_post_thumbnail_caption() ?></p>
                                    <?php $image_meta = Newspack_Image_Credits::get_media_credit(get_post_thumbnail_id()); ?>
                                    <?php if (isset($image_meta['credit_url']) && !empty($image_meta['credit_url'])): ?>
                                        <a href="<?= $image_meta['credit_url'] ?>">
                                    <?php endif; ?>
                                    <span class="credit">
                                        <?= $image_meta['credit'] ?>
                                        <?php if (isset($image_meta['organization']) && !empty($image_meta['organization'])): ?>
                                            / <?= $image_meta['organization'] ?>
                                        <?php endif; ?>
                                    </span>
                                    <?php if (isset($image_meta['credit_url']) && !empty($image_meta['credit_url'])): ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <span class="image-description-toggle">
                                <i class="fas fa-camera"></i>
                                <i class="fas fa-times"></i>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

	<section id="primary" class="content-area <?php echo esc_attr(newspack_get_category_tag_classes(get_the_ID())) . ' ' . newspack_featured_image_position(); ?>">

		<?php if(!$is_opinion && !$is_initiative): ?>
			<div class="breadcrumb">
				<a href="<?= site_url(); ?>">Home</a> /
				<a href="<?= get_post_type_archive_link('post'); ?>"><?=  __('News', 'plenamata') ?></a> /
				<a href="<?= get_category_link($category->cat_ID); ?>"><?= $category->name ?></a> /
			</div>
		<?php endif; ?>

		<main id="main" class="site-main">
			<?php

			// Template part for large featured images.

			?>

				<?php if (!$is_opinion && !$is_initiative): ?>
					<header class="entry-header">
						<div class="wrapper-entry-title">
							<h1 class="entry-title">
								<?php echo wp_kses_post(get_the_title()); ?>
							</h1>
						</div>
					</header>
				<?php endif; ?>


				<div class="main-content">
					<?php // Place smaller featured images inside of 'content' area.
					if (!$is_opinion && !$is_initiative){
						if ('small' === newspack_featured_image_position()) : ?>
							<div class="featured-image-small">
								<div class="featured-image-small__credit-wrapper">
									<?php newspack_post_thumbnail(); ?>

									<?php if(class_exists('Newspack_Image_Credits') && (!empty(Newspack_Image_Credits::get_media_credit(get_post_thumbnail_id())['credit']) || !empty(get_post(get_post_thumbnail_id())->post_content))): ?>
										<div class="image-info">
											<div class="image-info-container">
												<div class="wrapper">
													<div class="image-meta">
														<?php
														if (class_exists('Newspack_Image_Credits')) {
															$image_meta = Newspack_Image_Credits::get_media_credit(get_post_thumbnail_id()); ?>
															<?= (isset($image_meta['credit_url']) && !empty($image_meta['credit_url'])) ? '<a href="' . $image_meta['credit_url'] . '">' : null ?>
															<span class="credit">
																<?= $image_meta['credit'] ?>

																<?= isset($image_meta['organization']) && !empty($image_meta['organization']) ? ' / ' . $image_meta['organization'] : null ?>
															</span>
															<?= (isset($image_meta['credit_url']) && !empty($image_meta['credit_url'])) ? '</a>' : null ?>

														<?php
														}
														?>
													</div>

												</div>
											</div>
											<i class="fas fa-camera"></i>
										</div>
									<?php endif; ?>
								</div>
								<p class="description">
									<?php
									//var_dump(get_post(get_post_thumbnail_id()));
									?>
									<?= get_post(get_post_thumbnail_id())->post_content ?>
								</p>
							</div><!-- .featured-image-small -->
						<?php else:?>
							<div class="featured-image-small">
								<div class="featured-image-small__credit-wrapper">
									<?php newspack_post_thumbnail(); ?>

									<?php if(class_exists('Newspack_Image_Credits') && (!empty(Newspack_Image_Credits::get_media_credit(get_post_thumbnail_id())['credit']) || !empty(get_post(get_post_thumbnail_id())->post_content))): ?>
										<div class="image-info">
											<div class="image-info-container">
												<div class="wrapper">
													<div class="image-meta">
														<?php
														if (class_exists('Newspack_Image_Credits')) {
															$image_meta = Newspack_Image_Credits::get_media_credit(get_post_thumbnail_id()); ?>
															<?= (isset($image_meta['credit_url']) && !empty($image_meta['credit_url'])) ? '<a href="' . $image_meta['credit_url'] . '">' : null ?>
															<span class="credit">
																<?= $image_meta['credit'] ?>

																<?= isset($image_meta['organization']) && !empty($image_meta['organization']) ? ' / ' . $image_meta['organization'] : null ?>
															</span>
															<?= (isset($image_meta['credit_url']) && !empty($image_meta['credit_url'])) ? '</a>' : null ?>

														<?php
														}
														?>
													</div>

												</div>
											</div>
											<i class="fas fa-camera"></i>
										</div>
									<?php endif; ?>
								</div>
						<?php endif;
					}
					?>
					<div class="entry-subhead">
						<div class="entry-meta">
								<div class="author-partner">
										<?php plenamata_newspack_posted_by(); ?>
									<!-- publishers -->
									<?php
										show_publishers($post->ID);
									?>
									<!-- publishers -->
								</div>
							<?php newspack_posted_on(); ?>
						</div><!-- .meta-info -->
						<?php
						// Display Jetpack Share icons, if enabled
						if (function_exists('sharing_display')) {
							sharing_display('', true);
						}
						?>
					</div>

				<?php
				if (is_active_sidebar('article-1')) {
					dynamic_sidebar('article-1');
				}


					//get_template_part('template-parts/content/content', 'single');

					get_template_part(require PLENAMATA_PLUGIN_PATH . 'templates/parts/content-single.php');
				?>




			</div><!-- .main-content -->

		</main><!-- #main -->

		<div class="after-post-content-widget-area">
			<?php if ( is_single() ):
				dynamic_sidebar('after_post_widget_area');
			endif;
			?>
		</div>

		<div class="main-content">
			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if (comments_open() || get_comments_number()) {
				newspack_comments_template();
			}

			?>
		</div>

		<?php
			if(!is_page()) {
				get_template_part('template-parts/content/content', 'republish-post');
				get_template_part('template-parts/content/content', 'related-posts');
			}
		?>
	</section><!-- #primary -->

<?php
get_footer();
