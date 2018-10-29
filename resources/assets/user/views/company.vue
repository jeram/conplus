<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>{{current_company.name}} <small></small></h1>
        </section>

        <section class="content">
            <div class="padding">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li :class="{ active: active_tab === 'company' }">
                            <a href="#company" data-toggle="tab" @click="active_tab = 'company'">Company</a>
                        </li>
                        <li v-if="hasPermissionTo('Can Manage Statuses')" :class="{ active: active_tab === 'status_and_types' }">
                            <a href="#status_and_types" data-toggle="tab" @click="active_tab = 'status_and_types'">Statuses & Types</a>
                        </li>
                        <li v-if="hasPermissionTo('Can Manage Materials')" :class="{ active: active_tab === 'materials_management' }">
                            <a href="#materials_management" data-toggle="tab" @click="active_tab = 'materials_management'">Materials Management</a>
                        </li>
                        <li v-if="hasPermissionTo('Can Manage Users')" :class="{ active: active_tab === 'users' }">
                            <a href="#users" data-toggle="tab" @click="active_tab = 'users'">Users</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" :class="{ active: active_tab === 'company' }" id="company">
                            <company-form></company-form>
                        </div>
                        <div v-if="hasPermissionTo('Can Manage Statuses')" class="tab-pane" :class="{ active: active_tab === 'status_and_types' }" id="status_and_types">
                            <status-and-types :active_tab="active_tab"></status-and-types>
                        </div>
                        <div v-if="hasPermissionTo('Can Manage Materials')" class="tab-pane" :class="{ active: active_tab === 'materials_management' }" id="materials_management">
                            <materials-management :active_tab="active_tab"></materials-management>
                        </div>
                        <div v-if="hasPermissionTo('Can Manage Users')" class="tab-pane" :class="{ active: active_tab === 'users' }" id="users">
                            <users-management :active_tab="active_tab"></users-management>
                        </div>
                    </div>
                </div>          
            </div>
        </section>
    </div>
</template>

<script>
    import auth from '../auth'
    import { mapState, mapActions } from 'vuex'
    import { user_mixin } from '../mixins'

    export default {
        mixins: [user_mixin],

        data() {
            return {
                auth: auth,
                loading: false,
                loading_btn: false,
                active_tab: 'company',
                project: {
                    name: null,
                    cost: null,
                    details: {
                        description: null,
                        address: null,
                        contract_date: null,
                        proposal_date: null,
                        project_type_id: null,
                        project_status_id: null,
                    }
                },
            }
        },
        
        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        mounted() {
			 
        },

        created() {
            
        },

        methods: {
            
        },

        beforeRouteEnter (to, from, next) {
            auth.check()
                .then((res) => {
                    next()
                })
                .catch((err) => {
                    window.location.href = '/login'
                })
        }
    }
</script>