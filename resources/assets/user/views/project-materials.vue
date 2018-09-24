<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>{{current_project.name}} <small></small></h1>
        </section>

        <section class="content">
           <div class="padding">
                <div class="nav-tabs-custom" v-if="project_phases.length > 0">
                    <ul class="nav nav-tabs">
                        <li v-for="(phase, index) in project_phases" :class="{ active: active_tab === phase.id }">
                            <a href="javascript:void(0)" @click="active_tab = phase.id">{{phase.label}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <project-materials-crud :project_phase_id="active_tab"></project-materials-crud>
                    </div>
                </div>
                <div v-if="project_phases.length <= 0">
                    This project does not have a phase yet. Create one 
                    <router-link :to="{ name: 'Phases' }">
                        <a>here</a>
                    </router-link>
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
                active_tab: null,
            }
        },
        
        computed: {
            ...mapState({
                project_phases: state => state.project_phases,
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        mounted() {
			 
        },

        created() {
            this.getPhases()
        },

        methods: {
            getPhases() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/phase')
                    .then(res => {                        
                        this.loading = false
                        this.setProjectPhases(res.data)

                        this.active_tab = this.project_phases[0].id
                    })
                    .catch(err => {
                        this.loading = false
                        this.$root.handleErrors(err.response)
                    })
            },

            ...mapActions(['setProjectPhases'])
        },

        beforeRouteEnter (to, from, next) {
            auth.check()
                .then((res) => {
                    next()
                })
                .catch((err) => {
                    next({name: 'login'})
                })
        }
    }
</script>