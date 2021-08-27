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
        add_action( 'rest_api_init', [ $this, 'register_post_metas' ], 50 );
	}

    public function filters(): void {
        add_filter( 'rest_post_query', [ $this, 'filter_posts_by_state' ], 10, 2 );
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
