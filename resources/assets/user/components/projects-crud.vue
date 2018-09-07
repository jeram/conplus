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
                                <a class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a class="btn btn-xs btn-danger" @click="deleteRecord(record)">Delete</a>
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
                            <input type="text" v-validate="'required'" v-model="current_record.name" class="form-control">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Cost</label>
                            <input type="number" v-validate="'required'" v-model="current_record.cost" class="form-control" min="0" step="any">
                            <span class="text-danger">{{ errors.first('cost') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Status</label>
                            <select v-model="current_record.is_active" class="form-control">
                                <option v-for="status in statuses" :value="status.id">{{status.label}}</option>
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
    
    export default {
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
                statuses: [
                            {
                                'id': 1,
                                'label': 'Active',
                            },
                            {
                                'id': 0,
                                'label': 'Inactive',
                            },
                        ],
                current_record: null,
            }
        },

        mounted() {
            this.getRecords()
            this.resetCurrentRecord()
        },

        methods: {
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
                    })
            },

            showForm(object) {
                if (object == 'add_record') {
                    this.resetCurrentRecord()
                } else {

                    this.current_record = object
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
                                console.log(err)
                                this.loading_btn = false
                            })
                        } else { // add
                            return axios.post('/api/company/' + this.current_company.id + '/project', this.current_record)
                            .then(res => {
                                this.loading_btn = false
                                this.getRecords()

                                if (this.current_record.is_active) {
                                    this.newProject(this.current_record)
                                }

                                this.resetCurrentRecord()
                            })
                            .catch(err => {
                                console.log(err)
                                this.loading_btn = false
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
                        this.getRecords()
                        this.deleteProject(object)
                        this.resetCurrentRecord()
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },

            resetCurrentRecord() {
                this.current_record = {
                    id: 0,
                    name: '',
                    cost: '',
                    is_active: 1,
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