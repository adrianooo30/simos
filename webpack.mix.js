const mix = require('laravel-mix');

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

 // mix.js('resources/js/notifications', 'public/js');

// styling
// mix.js('resources/js/app.js', 'public/js')
// 	.sass('resources/sass/app.scss', 'public/css');


// // plugins
mix
	// .js('resources/plugins/chart.js', 'public/plugins')
	.js('resources/js/datatables.js', 'public/plugins/js')
	.sass('resources/sass/datatables.scss', 'public/plugins/css')
// 	.js('resources/plugins/sweetalert.js', 'public/plugins')
// 	.js('resources/plugins/daterangepicker.js', 'public/plugins')
;


// // custom styling
// mix.styles([
		
// 		'resources/sass/style.css',
// 		'resources/sass/customs/custom-fw-style.css',
// 		'resources/sass/customs/custom-style.css',

// 		'resources/sass/modals.css',

// 	], 'public/css/mainStyle.css');


// // custom scripts
// mix.scripts([

// 		'resources/js/scripts.js'

// 	], 'public/js/mainScript.js');

// CUSTOM SCRIPTS

// custom classes
mix.js('resources/js/custom-class/all.js', 'public/customs');
