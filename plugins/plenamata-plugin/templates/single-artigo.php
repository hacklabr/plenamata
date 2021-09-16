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
	$is_opinion = (get_the_category()[0]->slug == "_newspack_opinion" || get_the_category()[0]->slug == 'opiniao' || get_the_category()[0]->slug == 'opinion') ? true : false;
	if($is_opinion): ?>
			<div class="opinion-header">
				<div class="breadcrumb">
					<a href="<?= site_url(); ?>">Home</a> /
					<a href="<?= get_post_type_archive_link('post'); ?>"><?=  __("Articles", "plenamata") ?></a> /
					<a href="<?= get_category_link(get_the_category()[0]->cat_ID); ?>"><?= get_the_category()[0]->name ?></a> /
				</div>
				<div class="container">
					<h1 class="entry-title">
						<?php echo wp_kses_post(get_the_title()); ?>
					</h1>	
				</div>
			</div>
	<?php endif; ?>

	<section id="primary" class="content-area <?php echo esc_attr(newspack_get_category_tag_classes(get_the_ID())) . ' ' . newspack_featured_image_position(); ?>">
		
		<?php if(!$is_opinion): ?>
			<div class="breadcrumb">
				<a href="<?= site_url(); ?>">Home</a> /
				<a href="<?= get_post_type_archive_link('post'); ?>"><?=  __("Articles", "plenamata") ?></a> /
				<a href="<?= get_category_link(get_the_category()[0]->cat_ID); ?>"><?= get_the_category()[0]->name ?></a> /
			</div>
		<?php endif; ?>
		
		<main id="main" class="site-main">
			<?php
			
			// Template part for large featured images.
			
			?>

				<?php if(!$is_opinion): ?>
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
					if (!$is_opinion){
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
