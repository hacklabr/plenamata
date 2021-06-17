<?php

namespace jaci;

function enqueue_assets() {
    wp_enqueue_style('app', get_template_directory_uri() . '/dist/css/critical.css', [], filemtime(get_template_directory() . '/dist/css/critical.css'));
}

add_action('wp_enqueue_scripts', 'jaci\\enqueue_assets');

class Assets {
    private static $instances = [];
    protected $js_files;
    protected $css_files;

    protected function __construct() {
        $this->initialize();
    }

    public static function getInstance(){
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    /**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'buddyx_enqueue_admin_style' ] );
		add_action( 'wp_head', [ $this, 'action_preload_styles' ] );
		add_action( 'after_setup_theme', [ $this, 'action_add_editor_styles' ] );
		add_filter( 'wp_resource_hints', [ $this, 'filter_resource_hints' ], 10, 2 );
	}

    /**
	 * Registers or enqueues stylesheets.
	 *
	 * Stylesheets that are global are enqueued. All other stylesheets are only registered, to be enqueued later.
	 */
	public function action_enqueue_styles() {

		// Enqueue Google Fonts.
		$google_fonts_url = $this->get_google_fonts_url();
		if ( ! empty( $google_fonts_url ) ) {
			wp_enqueue_style( 'jaci-fonts', $google_fonts_url, [], null );
		}

		$css_uri = get_theme_file_uri( '/dist/css/' );
		$css_dir = get_theme_file_path( '/dist/css/' );

		$preloading_styles_enabled = true;

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {
            $src  = false;
            $version = false;

            $src = $css_uri . $data['file'];
            $version = (string) filemtime( $css_dir . $data['file'] );

            
            /**
             * Depends
             * 
             * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
             */
            $deps = [];
            if ( isset( $data['deps'] ) && ! empty( $data['deps'] ) ) {
                $deps = $data['deps'];
            }

			/*
			 * Enqueue global stylesheets immediately and register the other ones for later use
			 * (unless preloading stylesheets is disabled, in which case stylesheets should be immediately
			 * enqueued based on whether they are necessary for the page content).
			 */
			if ( $data['global'] || ! $preloading_styles_enabled && is_callable( $data['preload_callback'] ) && call_user_func( $data['preload_callback'] ) ) {
				wp_enqueue_style( $handle, $src, $deps, $version, $data['media'] );
			} else {
				wp_register_style( $handle, $src, $deps, $version, $data['media'] );
			}

			wp_style_add_data( $handle, 'precache', true );
		}
	}

	/**
	 * Register and enqueue a custom stylesheet in the WordPress admin.
	 */
	public function enqueue_admin_style($hook) {
        $css_uri = get_theme_file_uri( '/dist/css/' );
        $css_dir = get_theme_file_path( '/dist/css/' );

        // wp_enqueue_style( 'buddyx-admin', $css_uri . '/admin.min.css' );			
        // wp_enqueue_script(
        //     'buddyx-admin-script',
        //     get_theme_file_uri( '/assets/js/buddyx-admin.min.js' ),
        //     '',
        //     '',
        //     true
        // );
	}

	/**
	 * Preloads in-body stylesheets depending on what templates are being used.
	 *
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content
	 */
	public function action_preload_styles() {
		$wp_styles = wp_styles();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {

			// Skip if stylesheet not registered.
			if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
				continue;
			}

			// Skip if no preload callback provided.
			if ( ! is_callable( $data['preload_callback'] ) ) {
				continue;
			}

			// Skip if preloading is not necessary for this request.
			if ( ! call_user_func( $data['preload_callback'] ) ) {
				continue;
			}

			$preload_uri = $wp_styles->registered[ $handle ]->src . '?ver=' . $wp_styles->registered[ $handle ]->ver;

			echo '<link rel="preload" id="' . esc_attr( $handle ) . '-preload" href="' . esc_url( $preload_uri ) . '" as="style">';
			echo "\n";
		}
	}

	/**
	 * Enqueues WordPress theme styles for the editor.
	 */
	public function action_add_editor_styles() {

		// Enqueue Google Fonts.
		$google_fonts_url = $this->get_google_fonts_url();
		if ( ! empty( $google_fonts_url ) ) {
			add_editor_style( $this->get_google_fonts_url() );
		}

		// Enqueue block editor stylesheet.
		add_editor_style( 'assets/css/editor/editor-styles.min.css' );
	}

	/**
	 * Adds preconnect resource hint for Google Fonts.
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed.
	 * @return array URLs to print for resource hints.
	 */
	public function filter_resource_hints( array $urls, string $relation_type ) : array {
		if ( 'preconnect' === $relation_type && wp_style_is( 'buddyx-fonts', 'queue' ) ) {
			$urls[] = [
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			];
		}

		return $urls;
	}

	/**
	 * Prints stylesheet link tags directly.
	 *
	 * This should be used for stylesheets that aren't global and thus should only be loaded if the HTML markup
	 * they are responsible for is actually present. Template parts should use this method when the related markup
	 * requires a specific stylesheet to be loaded. If preloading stylesheets is disabled, this method will not do
	 * anything.
	 *
	 * If the `<link>` tag for a given stylesheet has already been printed, it will be skipped.
	 *
	 * @param string ...$handles One or more stylesheet handles.
	 */
	public function print_styles( string ...$handles ) {
		$css_files = $this->get_css_files();
		$handles   = array_filter(
			$handles,
			function( $handle ) use ( $css_files ) {
				$is_valid = isset( $css_files[ $handle ] ) && ! $css_files[ $handle ]['global'];
				if ( ! $is_valid ) {
					/* translators: %s: stylesheet handle */
					_doing_it_wrong( __CLASS__ . '::print_styles()', esc_html( sprintf( __( 'Invalid theme stylesheet handle: %s', 'buddyx' ), $handle ) ), 'Buddyx 2.0.0' );
				}
				return $is_valid;
			}
		);

		if ( empty( $handles ) ) {
			return;
		}

		wp_print_styles( $handles );
	}

	/**
	 * Gets all CSS files.
	 *
	 * @return array Associative array of $handle => $data pairs.
	 */
	protected function get_css_files() : array {
		if ( is_array( $this->css_files ) ) {
			return $this->css_files;
		}

		$css_files = [
			'critical'     => [
                'file' => 'critical.css',
				'global' => true,
                'inline' => true,
			],
	
            'home' => [
				'file' => '_p-home.css',
                'preload_callback' => function() {
					return is_home();
				},
			],
            'page' => [
                'file' => '_p-page.css',
                'preload_callback' => function() {
					return !is_home() && is_page();
				},
            ],

            'single' => [
                'file' => '_p-single.css',
                'preload_callback' => function() {
					return is_single();
				},
            ],

            '404' => [
                'file' => '_p-404.css',
                'preload_callback' => function() {
					return is_404();
				},
            ],

            'archive' => [
                'file' => '_p-archive.css',
                'preload_callback' => function() {
					return is_archive();
				},
            ],

            'search' => [
                'file' => '_p-search.css',
                'preload_callback' => function() {
					return is_search();
				},
            ],
		];

		/**
		 * Filters default CSS files.
		 *
		 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
		 * $data must be an array with keys 'file' (file path relative to 'assets/css'
		 * directory), and optionally 'global' (whether the file should immediately be
		 * enqueued instead of just being registered) and 'preload_callback' (callback)
		 * function determining whether the file should be preloaded for the current request).
		 */
		$css_files = apply_filters( 'buddyx_css_files', $css_files );

		$this->css_files = [];
		foreach ( $css_files as $handle => $data ) {
			if ( is_string( $data ) ) {
				$data = [ 'file' => $data ];
			}

			if ( empty( $data['file'] ) ) {
				continue;
			}

			$this->css_files[ $handle ] = array_merge(
				[
					'global'           => false,
					'preload_callback' => null,
					'media'            => 'all',
				],
				$data
			);
		}

		return $this->css_files;
	}

	/**
	 * Returns Google Fonts used in theme.
	 *
	 * @return array Associative array of $font_name => $font_variants pairs.
	 */
	protected function get_google_fonts() : array {
		if ( is_array( $this->google_fonts ) ) {
			return $this->google_fonts;
		}

		$google_fonts = [
            'Frank Ruhl Libre' => [ '300', '400', '500', '700', '900' ],
            'Roboto' => [ '100', '100i', '300', '400', '400i', '500', '500i', '700', '700i', '900', '900i&display=swap' ]
		];

		/**
		 * Filters default Google Fonts.
		 *
		 * @param array $google_fonts Associative array of $font_name => $font_variants pairs.
		 */
		$this->google_fonts = (array) apply_filters( 'buddyx_google_fonts', $google_fonts );

		return $this->google_fonts;
	}

	/**
	 * Returns the Google Fonts URL to use for enqueuing Google Fonts CSS.
	 *
	 * Uses `latin` subset by default. To use other subsets, add a `subset` key to $query_args and the desired value.
	 *
	 * @return string Google Fonts URL, or empty string if no Google Fonts should be used.
	 */
	protected function get_google_fonts_url() : string {
		$google_fonts = $this->get_google_fonts();

		if ( empty( $google_fonts ) ) {
			return '';
		}

		$font_families = [];

		foreach ( $google_fonts as $font_name => $font_variants ) {
			if ( ! empty( $font_variants ) ) {
				if ( ! is_array( $font_variants ) ) {
					$font_variants = explode( ',', str_replace( ' ', '', $font_variants ) );
				}

				$font_families[] = $font_name . ':' . implode( ',', $font_variants );
				continue;
			}

			$font_families[] = $font_name;
		}

		$query_args = [
			'family'  => implode( '|', $font_families ),
			'display' => 'swap',
		];

		return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}
}