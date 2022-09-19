<?php
/**
 * PlenamataPlugin Blocks
 *
 * @since   0.1.0
 * @link    hacklab.com.br
 * @license GPLv2 or later
 * @package PlenamataPlugin
 * @author  hacklab/
 */

namespace PlenamataPlugin\Common;

use WP_REST_Request;

/**
 * Class RestApi
 *
 * @since   0.1.5
 *
 * @package PlenamataPlugin\Common
 */
class RestApi {
    /**
	 * Init hooks
	 *
	 * @since 0.1.0
	 */
	public function hooks(): void {
        add_action( 'rest_api_init', [ $this, 'add_fields_to_response' ], 50 );
        add_action( 'rest_api_init', [ $this, 'register_post_metas' ], 50 );
	}

    public function filters(): void {
        add_filter( 'rest_post_query', [ $this, 'filter_posts_by_state' ], 10, 2 );
    }

    /**
     * Add custom fields to REST API response.
     */
    public function add_fields_to_response(): void {
        register_rest_field( 'post', 'plenamata_thumbnail', [
            'get_callback' => [ $this, 'add_thumbnail_to_response' ],
            'update_callback' => null,
            'schema' => null,
        ] );
        register_rest_field( 'verbete', 'plenamata_thumbnail', [
            'get_callback' => [ $this, 'add_thumbnail_to_response' ],
            'update_callback' => null,
            'schema' => null,
        ] );
    }

    /**
     * Add thumbnail to REST API response.
     *
     * @param array $object The pre-serialized post object
     * @return string The thumbnail URL
     */
    public function add_thumbnail_to_response( array $object ): string {
        
        $image_sizes = wp_get_additional_image_sizes();

        if ( !empty( $image_sizes[ 'newspack-article-block-landscape-small' ] ) ) {
            return get_the_post_thumbnail_url( $object[ 'id' ], 'newspack-article-block-landscape-small' );
        } else {
            return get_the_post_thumbnail_url( $object[ 'id' ], 'thumbnail' );
        }
    
    }

    /**
     * Support filter posts API requests by state.
     *
     * It's used, for instance, in dashboard.
     */
    public function filter_posts_by_state( array $query, WP_REST_Request $request ): array {
        $state = $request->get_param( 'state' );

        if ( !$request->has_param( 'type' ) && !empty( $state ) ) {
            $states = [
                'AC' => 'Acre',
                'AM' => 'Amazonas',
                'AP' => 'Amapá',
                'MA' => 'Maranhão',
                'MT' => 'Mato Grosso',
                'PA' => 'Pará',
                'RO' => 'Rondônia',
                'RR' => 'Roraima',
                'TO' => 'Tocantins',
            ];

            $query[ 'meta_query' ] ??= [];
            $query[ 'meta_query' ][] = [
                'key' => '_geocode_region_level_2_s',
                'value' => array_key_exists( $state, $states ) ? $states[ $state ] : $state,
            ];
        }

        return $query;
    }

    /**
     * Register post_meta for use in REST API.
     *
     * @param string $post_type
     * @param string $field
     * @param string $type
     * @param bool $single
     *
     * @return void
     */
    private function register_post_meta( string $post_type, string $field, string $type, bool $single = true ):void {
        register_post_meta( $post_type, $field, [
            'type' => $type,
            'single' => $single,
            'show_in_rest' => true,
        ] );
    }

    /**
     * Register post_metas for use in REST API.
     */
    public function register_post_metas():void {
        $this->register_post_meta( 'post', 'external-source-link', 'string' );
    }
}
