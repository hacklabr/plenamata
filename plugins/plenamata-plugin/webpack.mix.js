const mix = require( 'laravel-mix' );
const path = require( 'path' );
const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config' );

require( 'laravel-mix-copy-watched' );

const rootDir = './assets';
const assetsDir = rootDir + '/.src';
const distDir = rootDir + '/build';

mix
	.setPublicPath(distDir);

mix
	.sass( assetsDir + '/scss/admin/settings.scss', 'css/admin' )
	.sass( assetsDir + '/scss/admin/blocks.scss', 'css/admin' )
	.sass( assetsDir + '/scss/main.scss', 'css' );

mix
	.js( assetsDir + '/js/admin/settings/app.js', 'js/admin/settings.js' )
	.js( assetsDir + '/js/front/main/app.js', 'js/main.js' )
    .js( assetsDir + '/js/front/main/about-page.js', 'js/about-page.js' )
    .js( assetsDir + '/js/front/dashboard/dashboard.js', 'js/dashboard.js' )
    .js( assetsDir + '/js/front/blocks/deforestation-charts.js', 'js/deforestation-charts.js' )
    .js( assetsDir + '/js/front/blocks/estimatives-area.js', 'js/estimatives-area.js' )

mix
    .react( assetsDir + '/js/admin/blocks/index.js', 'js/admin/blocks.js' )
    .react( assetsDir + '/js/admin/formats/index.js', 'js/admin/formats.js'  );

mix
	.copyWatched( assetsDir + '/img/**/*.{jpg,jpeg,png,gif,svg}', distDir + '/img', { base: assetsDir + '/img' } );

mix.webpackConfig(webpack => ({
	...defaultConfig,
	entry: { },
    module: {},
    output: {
        chunkFilename: distDir + '/[name].js',
        filename: '[name].js',
        path: path.resolve( __dirname, distDir + '/' ),
        publicPath: distDir,
    },
    plugins: [
        ...defaultConfig.plugins,
        new webpack.EnvironmentPlugin({
            NODE_ENV: 'production',
            VUE_ENV: 'browser',
        }),
    ],
    resolve: {
        alias: { 'vue$': 'vue/dist/vue.runtime.esm.js' },
    },
}));
