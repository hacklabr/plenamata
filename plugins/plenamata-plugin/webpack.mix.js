const mix = require( 'laravel-mix' );
const path = require( 'path' );
const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config' );

require( 'laravel-mix-copy-watched' );

const root_dir = './assets';
const assets_dir = root_dir + '/.src';
const dist_dir = root_dir + '/build';

mix
	.setPublicPath(dist_dir);

mix
	.sass( assets_dir + '/scss/admin/settings.scss', 'css/admin' )
	.sass( assets_dir + '/scss/main.scss', 'css' );

mix
	.js( assets_dir + '/js/admin/settings/app.js', 'js/admin/settings.js' )
	.js( assets_dir + '/js/front/main/app.js', 'js' )

mix
    .react( assets_dir + '/js/admin/blocks/index.js', 'js/admin/blocks.js' );

mix
	.copyWatched( assets_dir + '/img/**/*.{jpg,jpeg,png,gif,svg}', dist_dir + '/img', { base: assets_dir + '/img' } );

mix.webpackConfig({
	...defaultConfig,
	entry: { },
    output: {
        chunkFilename: dist_dir + '/[name].js',
        path: path.resolve( __dirname, dist_dir + '/' ),
        publicPath: dist_dir,
        filename: '[name].js',
    },
    module: {},
	devtool: "inline-source-map"
});
