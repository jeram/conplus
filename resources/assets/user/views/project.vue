<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>{{current_project.name}} <small></small></h1>
        </section>

        <section class="content">
           <div class="padding">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li :class="{ active: active_tab === 'current_project' }">
                            <a href="#current_project" data-toggle="tab" @click="active_tab = 'current_project'">Current Project</a>
                        </li>
                        <li :class="{ active: active_tab === 'projects_management' }">
                            <a href="#projects_management" data-toggle="tab" @click="active_tab = 'projects_management'">Projects Management</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" :class="{ active: active_tab === 'current_project' }" id="current_project">
                            <project-form></project-form>
                        </div>
                        <div class="tab-pane" :class="{ active: active_tab === 'projects_management' }" id="projects_management">
                            <projects-crud></projects-crud>
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

    export default {

        data() {
            return {
                auth: auth,
                loading: false,
                loading_btn: false,
                active_tab: 'current_project',                
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
                    // next({name: 'login'})
                })
        }
    }
</script>