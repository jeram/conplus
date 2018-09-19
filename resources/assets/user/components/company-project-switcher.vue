<template>
    <div>
        <div style="width: 100%;position: absolute;bottom:0;left:0;padding:10px; background-color:#222d32;text-align:center">
            <div v-if="companies.length > 1">
                <span style="color: #b8c7ce;">Select Company:</span>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ current_company.name | truncate(50) }}
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li v-for="company in companies"><a href="javascript:void(0)" @click="changeCompany(company)">{{ company.name }}</a></li>
                        <!--<li class="divider"></li>
                        <li><a href="#">Add Company <i class="fa  fa-plus"></i></a></li>-->
                    </ul>
                </div>
            </div>
            <div>
                <span style="color: #b8c7ce;">Select Project:</span>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ current_project.name }}
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li v-for="project in projects"><a href="javascript:void(0)" @click="changeProject(project)">{{ project.name }}</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0)" @click="changeProject('add_project')">Add Project <i class="fa  fa-plus"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <modal v-if="showAddProjectModal" @close="showAddProjectModal = false">
            <span slot="header">New Project</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" v-validate="'required'" name="name" v-model="project.name" class="form-control">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cost">Cost</label>
                            <input type="text" id="cost" name="cost" v-model="project.cost" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="showAddProjectModal = false">Close</button>
                        <input type="submit" class="btn btn-primary pull-right" :disabled="loading_btn" value="Submit">
                    </div> 
                </form>
            </div>
        </modal>
    </div>
</template>

<script>
    import auth from '../auth'
    import { mapState, mapActions } from 'vuex'

    export default {
        computed: {
            ...mapState({
                companies: state => state.companies,
                projects: state => state.projects,
                current_company: state => state.current_company,
                current_project: state => state.current_project
            })
        },
        
        data() {
            return {
                auth: auth,
                showAddProjectModal: false,
                project: {
                    name: null,
                    cost: 0,
                },
                loading_btn: false
            }
        },
        
        created() {

        },

        methods: {
            getCompanies: function () {
                let self = this
                this.loading = true

                return axios.get('/api/company')
                            .then(function (res) {
                                self.loading = false

                                return Promise.resolve(res)
                            })
                            .catch(function (err) {
                                console.log(err)
                                self.loading = false
                                return Promise.reject(err)
                            })
            },

            gotoCompany() {
                this.$router.push({ name: 'Company', params: { company_id: this.current_company.id }})
            },

            gotoProject() {
                this.$router.push({ name: 'Project', params: { project_id: this.current_project.id }})
            },

            changeCompany(company) {
                if (company == 'add_company') {
                    this.dialogVisible = true
                } else {
                    this.setCurrentCompany(company)
                    this.setProjects(company.projects)
                    //this.resetVuex()
                    
                    console.log(company.projects)
                    localStorage.setItem('company_id', company.id)
                    //window.location.href = '/'
                }
            },

            changeProject(project) {
                if (project == 'add_project') {
                    this.showAddProjectModal = true
                } else {
                    
                    this.setCurrentProject(project)

                    localStorage.setItem('project_id', project.id)
                    this.showAddProjectModal = false
                    this.gotoProject()
                }
            },

            createCompany() {
                this.loading_btn = true
            },

            handleSubmit() {
                this.$validator.validate().then(result => {
                    if (!result) {
                        this.loading_btn = false
                        
                    } else {
                        this.loading_btn = true
                        return axios.post('/api/company/' + this.current_company.id + '/project', this.project)
                             .then(res => {
                                this.loading_btn = false
                                
                                // set current newly created project to current project
                                localStorage.setItem('project_id', res.data.id)
                                
                                this.setCurrentProject(res.data)
                                this.newProject(res.data)
                                //window.location.href = '/'
                                this.showAddProjectModal = false
                                this.gotoProject()
                             })
                             .catch(err => {
                                console.log(err)
                                this.loading_btn = false
                             })
                    }
                });
                
            },

            ...mapActions(['setCompanies', 'newCompany', 'setCurrentCompany', 'setCurrentProject', 'setProjects', 'newProject'])
        }
    }
</script>