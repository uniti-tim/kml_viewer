
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Slideout = require('slideout');

Window.Page = $("meta[name='page']").attr('content');

require('./custom/slideout');
require('./custom/browserloc');
require('./custom/home');
require('./custom/editor');
