/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

global.$ = global.jQuery = $;

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

require('bootstrap');

require('bootstrap-datepicker');

/*
 * algolia autocomplete plugin
 */
require('autocomplete.js');

/*
 * Custom helpers
 */
require('./admin-filters-dashboard');

require('./algolia-autocomplete');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
