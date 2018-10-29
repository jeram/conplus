<template>
    <section>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <form class="form-horizontal">
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="search_string" @input="doSearch" placeholder="Search...">
                            <span class="input-group-addon">
                                <i v-if="loading" class="fa fa-circle-o-notch fa-spin"></i>
                                <i v-if="!loading" class="fa fa-search"></i>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <a @click="showForm('add_record')" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">                
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0 && !loading" v-bind:key="index" v-for="(record, index) in records">
                            <td>{{record.name}}</td>
                            <td>{{record.email}}</td>
                            <td>{{record.roles[0].name}}</td>
                            <td>
                                <a class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a class="btn btn-xs btn-danger" @click="deleteRecord(record)" v-if="user.id !== record.id">Delete</a>
                            </td>
                        </tr>
                        <tr v-if="records.length <= 0 && !loading">
                            <td colspan="6"><em>No Record Found</em></td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="6"><i class="fa fa-circle-o-notch fa-spin"></i></td>
                        </tr>
                    </tbody>
                </table>
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New User</span>
            <span slot="header" v-else>Edit: User</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Name</label>
                            <input type="text" name="name" v-validate="'required'" v-model="current_record.name" class="form-control">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Email</label>
                            <input type="email" name="email" v-validate="'required|email'" v-model="current_record.email" class="form-control">
                            <span class="text-danger">{{ errors.first('email') }}</span>
                        </div>
                        <div class="form-group" v-if="user.id != current_record.id">
                            <label for="label">Role</label>
                            <select name="role" v-validate="'required'" v-on:change="onRoleChange" v-model="current_record.role" class="form-control">
                                <option value="">-- Select Role --</option>
                                <option value="User">Regular User</option>
                                <option value="Admin">Admin</option>
                            </select>
                            <span class="text-danger">{{ errors.first('role') }}</span>
                        </div>
                        <div class="form-group" v-if="show_permissions">
                            <label for="label">Permissions</label>
                            <div class="row">
                                <div class="col-md-5" v-bind:key="permission" v-for="permission in permissions">
                                    <label :for="permission.id">
                                      <input :id="permission.id" type="checkbox" v-model="current_record.permissions" :value="permission.id"> {{permission.name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="hideForm">Close</button>
                        <input type="submit" class="btn btn-primary pull-right" :disabled="loading_btn" value="Submit">
                    </div> 
                </form>
            </div>
        </modal>
    </section>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    
    export default {
        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        props: ['project_phase_id', 'active_tab'],

        data() {
            return {
                user: window.user,
                loading: false,
                loading_btn: false,
                show_form: false,
                search_string: '',
                pagination: {
                    'current_page': 1
                },
                records: [],
                current_record: null,
                show_permissions: false,
                permissions: [],
            }
        },

        mounted() {
            
        },

        methods: {
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/user', {
                        params: {
                            export_type: 'data-table',
                            q: this.search_string,
                            page: this.pagination.current_page,
                            project_phase_id: this.project_phase_id,
                        }
                    })
                    .then(res => {
                        this.loading = false
                        this.records = res.data.data.data
                        this.pagination = res.data.pagination
                    })
                    .catch(err => {
                        this.loading = false
                        this.$root.handleErrors(err.response)
                    })
            },

            getPermissions() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/permission')
                    .then(res => {
                        this.permissions = res.data
                    })
                    .catch(err => {
                        this.$root.handleErrors(err.response)
                    })
            },

            showForm(object) {
                if (object == 'add_record') {
                    this.resetCurrentRecord()
                } else {
                    object.permissions = object.permissions.map(a => a.id)

                    try {
                        object.role = object.roles[0].name

                        if (object.role == 'User') {
                            this.show_permissions = true
                        } else {
                            this.show_permissions = false
                        }
                    } catch(err) {
                        console.log('Role name not fetched')
                    }

                    this.current_record = object
                    
                    /*try {
                        console.log(object.roles[0].name)
                        this.current_record.role = object.roles[0].name
                    } catch(err) {
                        console.log('Role name not fetched')
                    }*/
                }
                this.show_form = true
            },

            hideForm() {
                this.show_form = false
                this.getRecords()
            },

            handleSubmit() {

                this.$validator.validate().then(result => {
                    if (!result) {

                        this.loading_btn = false
                        
                    } else {

                        this.loading_btn = true

                        if (this.current_record.id > 0) { // edit
                            return axios.put('/api/company/' + this.current_company.id + '/user/' + this.current_record.id, this.current_record)
                            .then(res => {
                                this.flash('User has been successfully updated', 'success')
                                this.loading_btn = false
                                this.getRecords()

                                this.hideForm()

                                this.resetCurrentRecord()
                            })
                            .catch(err => {
                                this.loading_btn = false
                                this.$root.handleErrors(err.response)
                            })
                        } else { // add
                            return axios.post('/api/company/' + this.current_company.id + '/user', this.current_record)
                            .then(res => {
                                this.flash('User has been successfully added', 'success')
                                this.loading_btn = false
                                this.getRecords()

                                if (this.current_record.id !== '0') {
                                    this.hideForm()
                                }

                                this.resetCurrentRecord()
                            })
                            .catch(err => {
                                this.loading_btn = false
                                this.$root.handleErrors(err.response)
                            })
                        }
                        
                    }
                });
                
            },

            deleteRecord(object) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    return false
                }

                return axios.delete('/api/company/' + this.current_company.id + '/user/' + object.id)
                    .then(res => {
                        this.flash('User has been successfully deleted', 'success')
                        this.getRecords()
                        this.resetCurrentRecord()
                    })
                    .catch(err => {
                        this.$root.handleErrors(err.response)
                    })
            },

            resetCurrentRecord() {
                this.current_record = {
                    id: 0,
                    name: '',
                    email: '',
                    role: '',
                    permissions: this.permissions.map(a => a.id),
                }
                this.show_permissions = false
            },

            doSearch() {
                this.pagination.current_page = 1
                this.getRecords()
            },

            onSearch(search) {
                this.search(search, this)
            },

            onRoleChange(event) {
                if (event.target.value == 'User') {
                    this.show_permissions = true
                } else {
                    this.show_permissions = false
                }
            }
        },

        watch: {
            'active_tab':  function (val) {
                if (this.active_tab == "users") {
                    this.getPermissions()
                    this.getRecords()
                    this.resetCurrentRecord()
                }
            },
        }
    }
</script>