let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

const root_dir = './';
const assets_dir = root_dir + '/assets';
const dist_dir = root_dir + '/dist';

mix.js(assets_dir + '/javascript/app.js', dist_dir);

// Generate critical CSS
mix.sass(assets_dir + '/scss/critical-app.scss', dist_dir + '/critical');

// Create "for loop" to compile all page files into individual CSSs

mix.sass(assets_dir + '/scss/app.scss', dist_dir);

