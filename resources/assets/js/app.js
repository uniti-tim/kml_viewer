
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Slideout = require('slideout');

import fontawesome from '@fortawesome/fontawesome'
import regular from '@fortawesome/fontawesome-free-regular'
import solid from '@fortawesome/fontawesome-free-solid'
fontawesome.library.add(regular)
fontawesome.library.add(solid)

Window.Page = $("meta[name='page']").attr('content');
Window.setMap = false;

require('./custom/slideout');
require('./custom/browserloc');
require('./custom/home');
require('./custom/editor');

// Init tooltips from bootstrap
$(function(){
  $('[data-toggle="tooltip"]').tooltip();
})
