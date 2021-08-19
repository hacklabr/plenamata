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