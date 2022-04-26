<?php

/**
 * Template to display the mobile navigation, either AMP or fallback.
 *
 * @package Newspack
 */
if (newspack_is_amp()) : ?>
	<amp-sidebar id="mobile-sidebar" layout="nodisplay" side="right" class="mobile-sidebar">
		<button class="mobile-menu-toggle" on='tap:mobile-sidebar.toggle'>
			<?php echo wp_kses(newspack_get_icon_svg('close', 20), newspack_sanitize_svgs()); ?>
			<?php esc_html_e('Close', 'newspack'); ?>
		</button>
	<?php else : ?>
		<aside id="mobile-sidebar-fallback" class="mobile-sidebar">
			<button class="mobile-menu-toggle">
				<?php echo wp_kses(newspack_get_icon_svg('close', 20), newspack_sanitize_svgs()); ?>
			</button>
		<?php endif; ?>

		<?php

		newspack_primary_menu();
		
		$button_url = get_theme_mod('discovery_button_link');

		if (!empty($button_url)) : ?>
			<div class="discovery-menu">
				<div class="discovery-title">
					<a href="<?= $button_url ?>" class="discovery-link">
						<?= __('Discovery', 'plenamata') ?>
					</a>
				</div>
			</div>
		<?php endif;

		newspack_tertiary_menu();

		?>

		<div class="more-menu">
			<div class="more-title">
				<?php
				$more_name = esc_html(wp_get_nav_menu_name('more-menu'));
				if (strlen($more_name) <= 0) {
					$more_name = __('MORE', 'plenamata');
				}
				?>
				<span class="more-name"><?= $more_name ?></span>
			</div>

			<div class="more-menu--content">
				<?php
				$nav = wp_nav_menu(
					array(
						'theme_location' => 'more-menu',
						'container'      => false,
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'          => 1,
						'fallback_cb'    => false,
						'echo'           => false,
					)
				);
				?>
				<?php if ($nav) : ?>
					<div class="item">
						<div class="item--title language-title">
							<?= __("Language", "plenamata") ?>
						</div>
						<div class="item--content language-item-content">
							<?php echo $nav; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="item">
					<div class="item--title">
						<?= __("Dark mode", "plenamata") ?>
					</div>

					<div class="item--content padded">
						<button action="dark-mode">
							<i class="far fa-lightbulb"></i>
							<i class="fas fa-toggle-off"></i>
						</button>
					</div>


				</div>


				<div class="item">
					<div class="item--title">
						<?= __("Type size", "plenamata") ?>
					</div>

					<div class="item--content padded">
						<button action="increase-size"><i class="fas fa-font"></i>+</button>
						<button action="decrease-size"><i class="fas fa-font"></i>-</button>
					</div>
				</div>

				<!-- <div class="item">
					<div class="item--title">
					</div>

					<div class="item--content padded">
						<button action="increase-contrast">
							<i class="fas fa-adjust"></i>+
						</button>
						<button action="decrease-contrast">
							<i class="fas fa-adjust"></i>-
						</button>
					</div>
				</div>-->


			</div>
		</div>

		<div class="social-menus">
			<div class="social-menus--title">
				<?= __("Follow us", "plenamata") ?>
			</div>
			<?php
			newspack_social_menu_header();
			?>
		</div>

		<?php if (newspack_is_amp()) : ?>
	</amp-sidebar>
<?php else : ?>
	</aside>
<?php endif; ?>


<div class="mobile-toolbar">
	<div class="wrapper">

		<div class="item">
            <?php
                $link = '#';
                if ( $page = get_page_by_title( 'Dashboard', OBJECT, 'page' ) ) {
                    $link = get_permalink( $page->ID );
                }
            ?>
            <a href="<?php echo esc_url( $link );?>" class="each-link">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 0C3.448 0 3 0.448 3 1C3 1.552 3.448 2 4 2H6C6.552 2 7 1.552 7 1C7 0.448 6.552 0 6 0H4ZM12 0C11.448 0 11 0.448 11 1C11 1.552 11.448 2 12 2H14C14.552 2 15 1.552 15 1C15 0.448 14.552 0 14 0H12ZM3 4C2.448 4 2 4.448 2 5V7C0.895 7 0 7.895 0 9V17C0 17.552 0.448 18 1 18H7C7.552 18 8 17.552 8 17V11H10V17C10 17.552 10.448 18 11 18H17C17.552 18 18 17.552 18 17V9C18 7.895 17.105 7 16 7V5C16 4.448 15.552 4 15 4H11C10.448 4 10 4.448 10 5V7H8V5C8 4.448 7.552 4 7 4H3Z" fill="#FF7373"/>
                </svg>
                <div class="item--title">
                    <?php _e( 'Dashboard', 'plenamata' );?>
                </div>

            </a>
		</div>
		<div class="item">
            <?php
                $link = get_permalink( get_option( 'page_for_posts' ) );				;
            ?>
            <a href="<?php echo esc_url( $link );?>" class="each-link">
				<svg xmlns="http://www.w3.org/2000/svg" width="15" height="18" viewBox="0 0 15 18" fill="none">
					<path d="M8.2548 0H1.8C0.81 0 0 0.81 0 1.8V16.2C0 17.19 0.81 18 1.8 18H12.6C13.59 18 14.4 17.19 14.4 16.2V6.1452C14.4 5.6682 14.2101 5.2101 13.8726 4.8726L9.5274 0.5274C9.1899 0.1899 8.7318 0 8.2548 0ZM9.9 14.4H4.5C4.0032 14.4 3.6 13.9968 3.6 13.5C3.6 13.0032 4.0032 12.6 4.5 12.6H9.9C10.3968 12.6 10.8 13.0032 10.8 13.5C10.8 13.9968 10.3968 14.4 9.9 14.4ZM9.9 10.8H4.5C4.0032 10.8 3.6 10.3968 3.6 9.9C3.6 9.4032 4.0032 9 4.5 9H9.9C10.3968 9 10.8 9.4032 10.8 9.9C10.8 10.3968 10.3968 10.8 9.9 10.8ZM8.1 6.3V1.35L13.05 6.3H8.1Z" fill="#FF7373"/>
				</svg>
				<div class="item--title">
                    <?php _e( 'News', 'plenamata' );?>
                </div>
           	</a>
		</div>

		<div class="item">
            <?php
                $link = '#';
				if($archive_cpt = 'verbete'){
					$link = get_post_type_archive_link( $archive_cpt );
				}
            ?>
            <a href="<?php echo esc_url( $link );?>" class="each-link">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
					<path d="M9 0C4.0293 0 0 4.0293 0 9C0 13.9707 4.0293 18 9 18C13.9707 18 18 13.9707 18 9C18 4.0293 13.9707 0 9 0ZM9 13.5C8.5032 13.5 8.1 13.0968 8.1 12.6V9C8.1 8.5032 8.5032 8.1 9 8.1C9.4968 8.1 9.9 8.5032 9.9 9V12.6C9.9 13.0968 9.4968 13.5 9 13.5ZM9.45 6.3H8.55C8.3016 6.3 8.1 6.0984 8.1 5.85V4.95C8.1 4.7016 8.3016 4.5 8.55 4.5H9.45C9.6984 4.5 9.9 4.7016 9.9 4.95V5.85C9.9 6.0984 9.6984 6.3 9.45 6.3Z" fill="#FF7373"/>
				</svg>
				<div class="item--title">
                    <?php _e( 'Glossary', 'plenamata' );?>
            	</div>
           	</a>
		</div>

		<div class="item">
			<button action="share-navigator">
				<svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none">
					<path d="M13.5 0C12.7839 0 12.0972 0.284464 11.5908 0.790812C11.0845 1.29716 10.8 1.98392 10.8 2.7C10.8006 2.86934 10.817 3.03824 10.8492 3.20449L4.44551 6.93984C3.95776 6.52666 3.33923 6.29993 2.7 6.3C1.98392 6.3 1.29716 6.58446 0.790812 7.09081C0.284464 7.59716 0 8.28392 0 9C0 9.71608 0.284464 10.4028 0.790812 10.9092C1.29716 11.4155 1.98392 11.7 2.7 11.7C3.33825 11.6987 3.95541 11.4714 4.44199 11.0584L10.8492 14.7955C10.817 14.9618 10.8006 15.1307 10.8 15.3C10.8 16.0161 11.0845 16.7028 11.5908 17.2092C12.0972 17.7155 12.7839 18 13.5 18C14.2161 18 14.9028 17.7155 15.4092 17.2092C15.9155 16.7028 16.2 16.0161 16.2 15.3C16.2 14.5839 15.9155 13.8972 15.4092 13.3908C14.9028 12.8845 14.2161 12.6 13.5 12.6C12.8611 12.6008 12.2433 12.8282 11.7563 13.2416L5.35078 9.50449C5.38297 9.33824 5.39945 9.16934 5.4 9C5.39945 8.83066 5.38297 8.66176 5.35078 8.49551L11.7545 4.76016C12.2422 5.17334 12.8608 5.40007 13.5 5.4C14.2161 5.4 14.9028 5.11554 15.4092 4.60919C15.9155 4.10284 16.2 3.41608 16.2 2.7C16.2 1.98392 15.9155 1.29716 15.4092 0.790812C14.9028 0.284464 14.2161 0 13.5 0Z" fill="#FF7373"/>
				</svg>
				<div class="item--title">
					<?= __("Share", "plenamata") ?>
				</div>
			</button>
		</div>

	</div>

</div>