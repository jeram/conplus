
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
//require('../../../public/AdminLTE-2.4.5/bower_components/jquery/dist/jquery.min.js')
require('../../../public/AdminLTE-2.4.5/bower_components/bootstrap/dist/js/bootstrap.min.js')
require('../../../public/AdminLTE-2.4.5/dist/js/adminlte.min.js')

import router from './router'
import auth from './auth'

window.Vue = require('vue');

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    
	data: {
        user: auth.user
    },
	computed: {
        
    },
    methods: {

    },
	el: '#app',
	router,
	
});

