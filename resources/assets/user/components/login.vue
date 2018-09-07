<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form :model="user" aria-label="Login">

                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">Email Address</label>

                                <div class="col-md-6">
                                    <input type="email" v-model="user.email" class="form-control" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input type="password" v-model="user.password" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" v-model="user.remember_me"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" :class="{'disabled': loading}" @click.prevent="login">
                                        Login
                                    </button>
                                    <!--
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth'
    import { mapState, mapActions } from 'vuex'

    export default {
        computed: {
            ...mapState({
                current_company: state => state.current_company
            })
        },

        data() {
            return {
                user: {
                    email: null,
                    password: null,
                    remember_me: null
                },
                loading: false,
            }
        },

        methods: {
            login() {
                this.loading = true
                auth.login(this.user.email, this.user.password, this.user.remember_me)
                    .then(res => {
                        showNotification(res.data.data.msg, 'alert-success')
                        
                        axios.get('/api/company')
                            .then(res => {
                                let current_company_id = localStorage.getItem('company_id')
                                let current_project_id = localStorage.getItem('project_id')
                                let companies = res.data
                                let company = null
                                let project = null
                                if (current_company_id) {
                                    if(companies.length > 1) {
                                        company = companies.find(company => company.id == current_company_id)
                                        if (!company) {
                                            company = companies[0]
                                        }
                                    } else {
                                        company = companies[0]
                                    }
                                } else {
                                    company = companies[0]
                                }

                                let projects = company.active_projects

                                if (current_project_id != 0) {
                                    if(projects.length > 1) {
                                        project = projects.find(project => project.id == current_project_id)
                                        if (!project) {
                                            project = projects[0]
                                        }
                                    } else {
                                        project = projects[0]
                                    }
                                } else {
                                    if (projects.length > 0) {
                                        project = projects[0]
                                    } else {
                                        project = {
                                            id : 0,
                                            name : 'No Project',
                                        }
                                    }
                                    
                                }
                                
                                this.setCurrentCompany(company)
                                this.setCurrentProject(project)
                                this.setProjects(company.active_projects)
                                //this.resetVuex()
                                localStorage.setItem('company_id', company.id)
                                localStorage.setItem('project_id', project.id)
                                
                                window.location.href = '/'
                            })
                            .catch(err => {
                                console.log(err)
                                showNotification(err.message, 'alert-danger')
                            })
                    })
                    .catch(err => {
                        //this.loading = false
                        showNotification(err.message, 'alert-danger')
                    })
            },

            ...mapActions(['setCurrentCompany', 'setCurrentProject', 'setProjects', 'resetVuex'])
        }
    }
</script>
