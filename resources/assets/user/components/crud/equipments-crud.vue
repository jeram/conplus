<template>
    <section>
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
                <a @click="showForm('add_record')" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0 && !loading" v-for="(record, index) in records">
                            <td>{{record.name}}</td>               
                            <td>{{record.notes}}</td>
                            <td>
                                <span v-if="record.status.label=='Operational'" class="label label-success">{{record.status.label}}</span>
                                <span v-else-if="record.status.label=='Non Operational'" class="label label-danger">{{record.status.label}}</span>
                                <span v-else class="label label-info">{{record.status.label}}</span>
                            </td>
                            <td>
                                <a class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a class="btn btn-xs btn-danger" @click="deleteRecord(record)">Delete</a>
                                <a class="btn btn-xs btn-warning" v-bind:class="{ disabled: selected_record.id == record.id }" @click="showEquipmentHistory(record)">View History ></a>
                            </td>
                        </tr>
                        <tr v-if="records.length <= 0 && !loading">
                            <td colspan="4"><em>No Record Found</em></td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="4"><i class="fa fa-circle-o-notch fa-spin"></i></td>
                        </tr>
                    </tbody>
                </table>
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Equipment</span>
            <span slot="header" v-else>Edit: Equipment</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Name</label>
                            <input type="text" name="name" v-validate="'required'" v-model="current_record.name" class="form-control">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Notes</label>
                            <textarea v-model="current_record.notes" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="label">Status</label>
                            <select v-model="current_record.company_equipment_status_id" class="form-control">
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
            })
        },

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
                statuses: [],
                current_record: null,
                selected_record: {
                    id: null
                },
            }
        },

        mounted() {
            this.getStatuses()
            this.getRecords()
            this.resetCurrentRecord()
        },

        methods: {
            getStatuses() {
                return axios.get('/api/company/' + this.current_company.id + '/equipment_status')
                            .then(res => {
                                this.statuses = res.data
                            })
                            .catch(function (err) {
                                this.$root.handleErrors(err.response)
                            })
            },
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/equipment', {
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

                            return axios.put('/api/company/' + this.current_company.id + '/equipment/' + this.current_record.id, this.current_record)
                            .then(res => {
                                this.flash('Record has been successfully updated', 'success')
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
                            return axios.post('/api/company/' + this.current_company.id + '/equipment', this.current_record)
                            .then(res => {
                                this.flash('Record has been successfully added', 'success')
                                this.loading_btn = false
                                this.getRecords()
                                this.hideForm()
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

                return axios.delete('/api/company/' + this.current_company.id + '/equipment/' + object.id)
                    .then(res => {
                        this.flash('Record has been successfully deleted', 'success')
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

            showEquipmentHistory(equipment) {
                this.selected_record = equipment
                this.$emit('load_history', equipment)
            }
        },

        watch: {
        }
    }
</script>