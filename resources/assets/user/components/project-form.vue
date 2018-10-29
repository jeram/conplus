<template>
    <section>
        <!--<div class="overlay" v-if="loading">
            <i class="fa fa-circle-o-notch fa-spin"></i>
        </div>-->
        <div class="box-body">
            <form @submit.prevent="handleSubmit" v-if="hasPermissionTo('Can Edit Projects')">
                <div class="row">
                    <div class="col-md-6">
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
                            <label for="cost">Description</label>
                            <textarea name="description" v-model="project.details.description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cost">Type</label>
                            <select name="project_type_id" v-model="project.details.project_type_id" class="form-control">
                                <option value="">-- Select Type --</option>
                                <option v-for="type in project_types" :value="type.id">{{type.label}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cost">Status</label>
                            <select name="project_status_id" v-model="project.details.project_status_id" class="form-control">
                                <option value="">-- Select Status --</option>
                                <option v-for="status in project_statuses" :value="status.id">{{status.label}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary pull-right" :disabled="loading_btn" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
            <div v-else>
                <div class="form-group">
                    <label for="name">Name: </label>
                    {{project.name}}
                </div>
            </div>
            
        </div>
    </section>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { user_mixin } from '../mixins'
    
    export default {
        mixins: [user_mixin],

        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        props: {
            
        },

        data() {
            return {
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
                        project_type_id: "",
                        project_status_id: "",
                    }
                },
                project_types: [],
                project_statuses: [],
            }
        },

        created() {
            this.getProjectTypes()
            this.getProjectStatuses()
            this.getProjectDetails()
        },

        methods: {
            getProjectTypes() {
                return axios.get('/api/company/' + this.current_company.id + '/project_type')
                            .then(res => {                                
                                this.project_types = res.data
                                // this.project_types_loading = false
                            })
                            .catch(err => {
                                this.$root.handleErrors(err.response)
                            })
            },
            getProjectStatuses() {
                return axios.get('/api/company/' + this.current_company.id + '/project_status')
                            .then(res => {
                                this.project_statuses = res.data
                                // this.project_status_loading = false
                            })
                            .catch(err => {
                                this.$root.handleErrors(err.response)
                            })
            },
            getProjectDetails() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id)
                    .then(res => {                        
                        this.loading = false
                        this.project = res.data

                        if (this.project.details == null) {
                            this.project.details = {
                                description: null,
                                address: null,
                                contract_date: null,
                                proposal_date: null,
                                project_type_id: "",
                                project_status_id: "",
                            }
                        }
                    })
                    .catch(err => {
                        this.loading = false
                        this.$root.handleErrors(err.response)
                    })
            },
            handleSubmit() {

                this.$validator.validate().then(result => {
                    if (!result) {

                        this.loading_btn = false
                        
                    } else {

                        this.loading_btn = true

                        return axios.put('/api/company/' + this.current_company.id + '/project/' + this.current_project.id, this.project)
                        .then(res => {
                            this.flash('Project has been successfully updated', 'success')

                            this.loading_btn = false

                            this.updateProject(this.project)

                            this.resetCurrentRecord()
                        })
                        .catch(err => {
                            this.loading_btn = false
                            this.$root.handleErrors(err.response)
                        })
                    
                        
                    }
                });
                
            },
        },

    }
</script>