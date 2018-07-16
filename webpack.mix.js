let mix = require('laravel-mix');

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

mix.js('resources/assets/app/app.js', 'public/app/js')
	.sass('resources/assets/app/sass/app.scss', 'public/app/css'); 
	
mix.js('resources/assets/user/app.js', 'public/user/js')
	.sass('resources/assets/user/sass/app.scss', 'public/user/css');
