<?php
/**
 * Create colunista user role
 */
function plenamata_colunista_role() {
    $caps = array(
        'read'                      => true,
        'upload_files'              => true,
        'edit_posts'                => true,
        'edit_published_posts'      => true,
        'edit'                      => true,
        'publish_posts'             => true,
        'delete_posts'              => true,
        'delete_published_posts'    => true,
        'level_0'                   => true,
        'level_1'                   => true,
        'level_2'                   => true,
        'level_3'                   => true,
        'manage_categories'         => true,
        'manage_category'           => true,
        'edit_categories'           => true,
        'delete_categories'         => true,
        'assign_categories'         => true,
        'manage_terms'              => true,
        );

    $saved_caps = get_option( 'plenamata_colunista_role_created', false );
    if ( $caps === $saved_caps ) {
        return;
    }

    // Add role
    add_role( 'colunista', 'Colunista', $caps );
    update_option( 'plenamata_colunista_role_created', $caps, true );

}
add_action( 'init', 'plenamata_colunista_role', 10 );
function plenamata_secao_capabilities($args, $taxonomy, $object_type){

	if($taxonomy !== 'secao' && $taxonomy !== 'category' ) {
        return $args;
    }

	$args['capabilities'] = array(
		'manage_terms' => 'edit_posts',
		'edit_terms' => 'edit_posts',
		'delete_terms' => 'edit_posts',
		'assign_terms' => 'edit_posts'
	);

	return $args;

}
add_filter('register_taxonomy_args', 'plenamata_secao_capabilities', 10, 3);

/**
 * Prints HTML with meta information about theme author.
 */
function plenamata_newspack_posted_by() {
	if (function_exists('coauthors_posts_links')) :

		$authors      = get_coauthors();
		$author_count = count($authors);
		$i            = 1;
        if( ! $author_count || empty( $author_count ) ) {
            return;
        }

		foreach ($authors as $author) {
			if ('guest-author' === get_post_type($author->ID)) {
				if (get_post_thumbnail_id($author->ID)) {
					$author_avatar = coauthors_get_avatar($author, 80);
				} else {
					// If there is no avatar, force it to return the current fallback image.
					$author_avatar = get_avatar(' ');
				}
			} else {
				$author_avatar = coauthors_get_avatar($author, 80);
			}
		}
	?>
	<?php
		$parent_type_category = '';
		if(isset(get_category_by_slug('type')->cat_ID)) {
			$parent_type_category = get_category_by_slug('type')->cat_ID;
		}

		$post_categories = get_the_category();
		$post_child_category = null;
		foreach ($post_categories as $post_cat) {
			if ($parent_type_category == $post_cat->parent) {
				$post_child_category = $post_cat;
				break;
			}
		}
		$isOpinionPost = isset($post_child_category->slug) && in_array ( $post_child_category->slug, ['opinion']);
		$showAuthorAvatar = $author_count === 1 && $isOpinionPost;

	?>
		<span class="<?php echo !$showAuthorAvatar ? 'byline' : 'byline single-author-opinion'; ?>">
			<span><?php echo esc_html__('By', 'plenamata'); ?></span>
			<?php
			foreach ($authors as $author) {
				if ('guest-author' === get_post_type($author->ID)) {
					if (get_post_thumbnail_id($author->ID)) {
						$author_avatar = coauthors_get_avatar($author, 80);
					} else {
						// If there is no avatar, force it to return the current fallback image.
						$author_avatar = get_avatar(' ');
					}
				} else {
					$author_avatar = coauthors_get_avatar($author, 80);
				}

				if ($showAuthorAvatar):
					echo '<span class="author-avatar">' . wp_kses( $author_avatar, newspack_sanitize_avatars() ) . '</span>';
				endif;
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

				printf(
					/* translators: 1: author link. 2: author name. 3. variable seperator (comma, 'and', or empty) */
					'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>%3$s ',
					esc_url(get_author_posts_url($author->ID, $author->user_nicename)),
					esc_html($author->display_name),
					esc_html($sep)
				);
			}
			?>
		</span><!-- .byline -->
	<?php
	else :
		printf(
			/* translators: 1: Author avatar. 2: post author, only visible to screen readers. 3: author link. */
			'<span class="author-avatar">%1$s</span><span class="byline"><span>%2$s</span> <span class="author vcard"><a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar(get_the_author_meta('ID')),
			esc_html__('by', 'plenamata'),
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			esc_html(get_the_author())
		);

	endif;
}
