<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>{{current_project.name}} <small></small></h1>
        </section>

        <section class="content">
           <div class="padding">
                <div class="box box-primary">
                    <div class="overlay" v-if="loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <div class="box-body">
                        <form @submit.prevent="handleSubmit">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" v-validate="'required'" name="name" v-model="project.name" class="form-control">
                                <span class="text-danger">{{ errors.first('name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="cost">Cost</label>
                                <input type="text" id="cost" name="cost" v-model="project.cost" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-default pull-left" @click="showAddProjectModal = false">Close</button>
                                <input type="submit" class="btn btn-primary pull-right" :disabled="loading_btn" value="Submit">
                            </div>
                        </form>
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
            this.getProjectDetails()
        },

        methods: {
            getProjectDetails() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id)
                    .then(res => {                        
                        this.loading = false
                        this.project = res.data
                    })
                    .catch(function (err) {                        
                        this.loading = false
                    })
            },
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