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
