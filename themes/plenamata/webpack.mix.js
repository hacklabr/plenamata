let mix = require('laravel-mix');
let fs = require('fs');

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

const getDirFiles = function (dir) {
    // get all 'files' in this directory
    // filter directories
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

const root_dir = './';
const assets_dir = root_dir + '/assets';
const dist_dir = root_dir + '/dist';

// mix.js(assets_dir + '/javascript/app.js', dist_dir);

// Generate critical CSS
mix.sass(assets_dir + '/scss/critical-app.scss', dist_dir + '/critical.css');

// Compile all page files into individual CSSs
const pagesPath = assets_dir + '/scss/6-pages/';

getDirFiles(pagesPath).forEach((filepath) => {
    mix.sass(pagesPath + filepath , dist_dir + '/pages/');
})


mix.sass(assets_dir + '/scss/app.scss', dist_dir);

