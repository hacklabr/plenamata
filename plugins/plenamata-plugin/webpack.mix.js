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
    .js( assetsDir + '/js/front/dashboard/dashboard.js', 'js/dashboard.js' )

mix
    .react( assetsDir + '/js/admin/blocks/index.js', 'js/admin/blocks.js' );

mix
	.copyWatched( assetsDir + '/img/**/*.{jpg,jpeg,png,gif,svg}', distDir + '/img', { base: assetsDir + '/img' } );

mix.webpackConfig({
	...defaultConfig,
	entry: { },
    output: {
        chunkFilename: distDir + '/[name].js',
        path: path.resolve( __dirname, distDir + '/' ),
        publicPath: distDir,
        filename: '[name].js',
    },
    module: {},
	devtool: "inline-source-map"
});
