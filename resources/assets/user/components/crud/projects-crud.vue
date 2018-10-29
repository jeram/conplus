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
                <a v-if="hasPermissionTo('Can Add Projects')" @click="showForm('add_record')" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">                
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0 && !loading" v-for="(record, index) in records">
                            <td>{{record.name}}</td>
                            <td>{{record.cost | toCurrency}}</td>                            
                            <td>{{record.created_at | toHumanDate}}</td>
                            <td>
                                <span class="label label-success" v-if="record.is_active">
                                    Active
                                </span>
                                <span class="label label-default" v-else>
                                    Inactive
                                </span>
                            </td>
                            <td>
                                <a v-if="hasPermissionTo('Can Edit Projects')" class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a v-if="hasPermissionTo('Can Delete Projects')" class="btn btn-xs btn-danger" @click="deleteRecord(record)">Delete</a>
                            </td>
                        </tr>
                        <tr v-if="records.length <= 0 && !loading">
                            <td colspan="5"><em>No Record Found</em></td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="5"><i class="fa fa-circle-o-notch fa-spin"></i></td>
                        </tr>
                    </tbody>
                </table>
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Project</span>
            <span slot="header" v-else>Edit: Project</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Name</label>
                            <input type="text" name="name" v-validate="'required'" v-model="current_record.name" class="form-control">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Cost</label>
                            <input type="number" name="cost" v-validate="'required'" v-model="current_record.cost" class="form-control" min="0" step="any">
                            <span class="text-danger">{{ errors.first('cost') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cost">Description</label>
                            <textarea name="description" v-model="current_record.details.description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cost">Type</label>
                            <select name="project_type_id" v-model="current_record.details.project_type_id" class="form-control">
                                <option value="">-- Select Type --</option>
                                <option v-for="type in project_types" :value="type.id">{{type.label}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cost">Status</label>
                            <select name="project_status_id" v-model="current_record.details.project_status_id" class="form-control">
                                <option value="">-- Select Status --</option>
                                <option v-for="status in project_statuses" :value="status.id">{{status.label}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="label">Is Active</label>
                            <select v-model="current_record.is_active" class="form-control">
                                <option v-for="option in is_active_options" :value="option.id">{{option.label}}</option>
                            </select>
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
    import { user_mixin } from '../../mixins'
    
    export default {
        mixins: [user_mixin],

        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company,
                projects: state => state.projects,
            })
        },

        props: ['project_phase_id'],

        data() {
            return {
                loading: false,
                loading_btn: false,
                show_form: false,
                search_string: '',
                pagination: {
                    'current_page': 1
                },
                records: [],
                is_active_options: [
                            {
                                'id': 1,
                                'label': 'Active',
                            },
                            {
                                'id': 0,
                                'label': 'Inactive',
                            },
                        ],
                current_record: {
                    name: null,
                    cost: null,
                    is_active: 1,
                    details: {
                        description: null,
                        address: null,
                        contract_date: null,
                        proposal_date: null,
                        project_type_id: "",
                        project_status_id: "",
                    }
                }
            }
        },

        mounted() {
            this.getProjectTypes()
            this.getProjectStatuses()
            this.getRecords()
            this.resetCurrentRecord()
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
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project', {
                        params: {
                            export_type: 'data-table',
                            q: this.search_string,
                            page: this.pagination.current_page,
                        }
                    })
                    .then(res => {
                        this.loading = false
                        this.records = res.data.data.data
                        this.pagination = res.data.pagination
                        this.total_amount = res.data.total_amount
                    })
                    .catch(err => {
                        this.loading = false
                        this.$root.handleErrors(err.response)
                    })
            },

            showForm(object) {
                if (object == 'add_record') {
                    this.resetCurrentRecord()
                } else {

                    this.current_record = object
                    if (this.current_record.details == null) {
                        this.current_record.details = {
                            description: null,
                            address: null,
                            contract_date: null,
                            proposal_date: null,
                            project_type_id: "",
                            project_status_id: "",
                        }
                    }
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

                            return axios.put('/api/company/' + this.current_company.id + '/project/' + this.current_record.id, this.current_record)
                            .then(res => {
                                this.flash('Record has been successfully updated', 'success')
                                this.loading_btn = false
                                this.getRecords()

                                this.hideForm()

                                if (!this.current_record.is_active) {
                                    this.deleteProject(this.current_record)
                                } else {
                                    let project = null

                                    // check if project is already in the list of active projects
                                    project = this.projects.find(o => o.id == this.current_record.id)

                                    if (!project) {
                                        this.newProject(this.current_record)
                                    }
                                }

                                this.updateProject(this.current_record)

                                this.resetCurrentRecord()
                            })
                            .catch(err => {
                                this.loading_btn = false
                                this.$root.handleErrors(err.response)
                            })
                        } else { // add
                            return axios.post('/api/company/' + this.current_company.id + '/project', this.current_record)
                            .then(res => {
                                this.flash('Record has been successfully added', 'success')
                                this.loading_btn = false
                                this.getRecords()

                                this.hideForm()

                                if (this.current_record.is_active) {
                                    this.newProject(this.current_record)
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

                return axios.delete('/api/company/' + this.current_company.id + '/project/' + object.id)
                    .then(res => {
                        this.flash('Record has been successfully deleted', 'success')
                        this.getRecords()
                        this.deleteProject(object)
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
                    cost: '',
                    is_active: 1,
                    details: {
                        description: null,
                        address: null,
                        contract_date: null,
                        proposal_date: null,
                        project_type_id: "",
                        project_status_id: "",
                    }
                }
            },

            doSearch() {
                this.pagination.current_page = 1
                this.getRecords()
            },

            onSearch(search) {
                this.search(search, this)
            },

            ...mapActions(['setCurrentProject', 'setProjects', 'newProject', 'updateProject', 'deleteProject'])
        },

        watch: {
        }
    }
</script>