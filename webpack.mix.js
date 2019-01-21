const mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/service-worker.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig({
        devtool: 'source-map'
    })
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.js')],
    })
    .sourceMaps()
    .version();

if (! mix.inProduction()) {
    //mix.browserSync('foundertag.test')
    // mix.disableNotifications();
}
