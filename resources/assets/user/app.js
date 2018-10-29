/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
//require('../../../public/AdminLTE-2.4.5/bower_components/jquery/dist/jquery.min.js')
require('../../../public/AdminLTE-2.4.5/bower_components/bootstrap/dist/js/bootstrap.min.js')
require('../../../public/AdminLTE-2.4.5/dist/js/adminlte.min.js')
require('../../../public/AdminLTE-2.4.5/bower_components/bootstrap-daterangepicker/daterangepicker.js')
require('../../../public/AdminLTE-2.4.5/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')
require('../../../public/AdminLTE-2.4.5/plugins/bootstrap-slider/bootstrap-slider.js')
require('../../../public/AdminLTE-2.4.5/bower_components/datatables.net/js/jquery.dataTables.min.js')
require('../jQuery-JSON-TagEditor/jquery.json-tag-editor.min.js')
require('../jQuery-JSON-TagEditor/jquery.caret.min.js')
require('../bootstrap-ajax-typeahead/js/bootstrap-typeahead.min.js')
require('../pace/pace.min.js')
require('./custom.js')

import router from './router'
import auth from './auth'
import store from './store'
import {
    mapActions,
    mapState
} from 'vuex'

import vSelect from 'vue-select'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

import VueFlashMessage from 'vue-flash-message'
Vue.use(VueFlashMessage, {
    messageOptions: {
        timeout: 2000,
        // important: true,
        // autoEmit: false,
        pauseOnInteract: true
    }
})
import 'vue-flash-message/dist/vue-flash-message.min.css'

window.Vue = require('vue')

import VueRouter from 'vue-router'
window.Vue.use(VueRouter)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('company-project-switcher', require('./components/company-project-switcher.vue'))
Vue.component('sidebar-menu', require('./components/sidebar-menu.vue'))
Vue.component('login', require('./components/login.vue'))
Vue.component('company-form', require('./components/company-form.vue'))
Vue.component('status-and-types', require('./components/status-and-types.vue'))
Vue.component('materials-management', require('./components/materials-management.vue'))
Vue.component('pagination', require('./components/pagination.vue'))
Vue.component('users-management', require('./components/users-management.vue'))
Vue.component('project-form', require('./components/project-form.vue'))


/**
 * CRUD
 */
Vue.component('materials-crud', require('./components/crud/materials-crud.vue'))
Vue.component('material-categories-crud', require('./components/crud/material-categories-crud.vue'))
Vue.component('project-materials-crud', require('./components/crud/project-materials-crud.vue'))
Vue.component('payments-crud', require('./components/crud/payments-crud.vue'))
Vue.component('deposits-crud', require('./components/crud/deposits-crud.vue'))
Vue.component('users-crud', require('./components/crud/users-crud.vue'))
Vue.component('projects-crud', require('./components/crud/projects-crud.vue'))
Vue.component('equipments-crud', require('./components/crud/equipments-crud.vue'))
Vue.component('equipment-history-crud', require('./components/crud/equipment-history-crud.vue'))
Vue.component('clients-crud', require('./components/crud/clients-crud.vue'))
Vue.component('client-trades-crud', require('./components/crud/client-trades-crud.vue'))


/**
 * Form Elements
 */
Vue.component('v-select', vSelect)
Vue.component('modal', require('./components/form-elements/modal.vue'))
Vue.component('json-tag-editor', require('./components/form-elements/json-tag-editor.vue'))
Vue.component('daterangepicker', require('./components/form-elements/daterangepicker.vue'))
Vue.component('datepicker', require('./components/form-elements/datepicker.vue'))
Vue.component('typeahead', require('./components/form-elements/typeahead.vue'))
Vue.component('slider', require('./components/form-elements/slider.vue'))

/**
 * Graphs
 */
Vue.component('total-deposit-vs-total-payments', require('./components/graphs/total-deposit-vs-total-payments.vue'))

Vue.filter('toCurrency', function (value) {
    if (!value) {
        return value
    }
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

Vue.filter('daysRemaining', function (date) {
    var eventdate = moment(date)
    var todaysdate = moment().format("YYYY-MM-DD")
    var daysremaining = eventdate.diff(todaysdate, 'days')
    return daysremaining > 1 ? daysremaining + ' days' : daysremaining + ' day'
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

        handleErrors(response) {
            if (response && response.status) {
                let message = ''
                switch (response.status) {
                    case 401:
                        message = 'You are not authorized to make this request.'
                        break
                    case 403:
                        message = 'You do not have enough permissions to make this request.'
                        if (response.data && response.data.error) {
                            message = response.data.error
                        }
                        break                    
                    case 404:
                        message = 'Requested resource not found.'
                        break
                    case 405:
                        message = 'Method Not Allowed.'
                        if (response.data && response.data.error) {
                            message = response.data.error
                        }
                        break
                    case 422:
                        message = "<ul class='list m-0'>"
                        for (let index in response.data.errors) {
                            message += "<li class='list-item pl-0 pr-0'><div class='list-body'>" + response.data.errors[index] + "</div></li>"
                        }
                        message += "</ul>"
                        break
                    case 500:
                        message = 'Oops! We are having some problems right now, please try again later.'
                        break
                }
                this.flash(message, 'error');
            }
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