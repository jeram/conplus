
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
//require('../../../public/AdminLTE-2.4.5/bower_components/jquery/dist/jquery.min.js')
require('../../../public/AdminLTE-2.4.5/bower_components/bootstrap/dist/js/bootstrap.min.js')
require('../../../public/AdminLTE-2.4.5/dist/js/adminlte.min.js')
require('../../../public/AdminLTE-2.4.5/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')
require('../../../public/AdminLTE-2.4.5/plugins/bootstrap-slider/bootstrap-slider.js')
require('../../../public/AdminLTE-2.4.5/bower_components/datatables.net/js/jquery.dataTables.min.js')
require('../jQuery-JSON-TagEditor/jquery.json-tag-editor.min.js')
require('../jQuery-JSON-TagEditor/jquery.caret.min.js')
require('../pace/pace.min.js')
require('./custom.js')

import router from './router'
import auth from './auth'
import store from './store'
import {mapActions, mapState} from 'vuex'
import vSelect from 'vue-select'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

window.Vue = require('vue')

import VueRouter from 'vue-router'
window.Vue.use(VueRouter)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('v-select', vSelect)
Vue.component('notification', require('./components/notification.vue'));
Vue.component('company-project-switcher', require('./components/company-project-switcher.vue'));
Vue.component('sidebar-menu', require('./components/sidebar-menu.vue'));
Vue.component('modal', require('./components/modal.vue'));
Vue.component('login', require('./components/login.vue'));
Vue.component('company-form', require('./components/company-form.vue'));
Vue.component('status-and-types', require('./components/status-and-types.vue'));
Vue.component('json-tag-editor', require('./components/json-tag-editor.vue'));
Vue.component('datepicker', require('./components/datepicker.vue'));
Vue.component('slider', require('./components/slider.vue'));
Vue.component('materials-management', require('./components/materials-management.vue'));
Vue.component('materials-crud', require('./components/materials-crud.vue'));
Vue.component('material-categories-crud', require('./components/material-categories-crud.vue'));
Vue.component('pagination', require('./components/pagination.vue'));
Vue.component('project-materials-crud', require('./components/project-materials-crud.vue'));
Vue.component('payments-crud', require('./components/payments-crud.vue'));
Vue.component('deposits-crud', require('./components/deposits-crud.vue'));
Vue.component('users-management', require('./components/users-management.vue'));
Vue.component('users-crud', require('./components/users-crud.vue'));
Vue.component('projects-crud', require('./components/projects-crud.vue'));
Vue.component('equipments-crud', require('./components/equipments-crud.vue'));
Vue.component('equipment-history-crud', require('./components/equipment-history-crud.vue'));
Vue.component('project-form', require('./components/project-form.vue'));

Vue.filter('toCurrency', function (value) {
    return parseFloat(value).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
});

Vue.filter('toHumanDate', function (value) {
    return moment(value).format('MMM D, YYYY')
});

Vue.filter('truncate', function (value, limit) {
    if (value.length > limit) {
        return value.substring(0, limit) + '...'
    }
    return value
})

const app = new Vue({
    
	data: {
        user: auth.user
    },
	computed: {
        ...mapState(['current_company'])
    },
    methods: {
        getProjects: function () {
            let self = this
            self.loading = true
            axios.get('/api/company/' + this.current_company.id + '/project', {
                mode: 'no-cors',
            }).then(function (res) {
                // console.log(JSON.stringify(res.data))
                self.setProjects(res.data)
                self.loading = false
            }).catch(function (err) {
                console.log(err)
                self.loading = false
            })
        },
        
        ...mapActions(['setCampaigns'])
    },
	el: '#app',
	router,
	store,
    watch: {
        'current_company': function (newVal, oldVal) {
            //this.getProjects();
        }
    }
});

