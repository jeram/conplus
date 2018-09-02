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
                            <th>Amount</th>
                            <th>Check Number</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0 && !loading" v-for="(record, index) in records">
                            <td>{{record.amount}}</td>
                            <td>{{record.check_number}}</td>
                            <td>{{record.payment_date}}</td>
                            <td>{{record.notes}}</td>
                            <td>
                                <h4>
                                    <span class="label label-info">
                                        To Purchase: {{record.company_payment_type_id}}
                                    </span>
                                </h4>
                            </td>
                            <td>
                                <a class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a class="btn btn-xs btn-danger" @click="deleteRecord(record)">Delete</a>
                            </td>
                        </tr>
                        <tr v-if="records.length <= 0 && !loading">
                            <td colspan="3"><em>No Record Found</em></td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="3"><i class="fa fa-circle-o-notch fa-spin"></i></td>
                        </tr>
                    </tbody>
                </table>
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Payment</span>
            <span slot="header" v-else>Edit: Payment Info}</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Amount</label>
                            <input type="number" v-validate="'required'" v-model="current_record.amount" class="form-control" min="0" step="any">
                            <span class="text-danger">{{ errors.first('amount') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Check Number</label>
                            <input type="text" v-validate="''" v-model="current_record.check_number" class="form-control">
                            <span class="text-danger">{{ errors.first('check_number') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Payment Date</label>
                            <input type="text" v-model="current_record.payment_date" class="form-control">  
                        </div>
                        <div class="form-group">
                            <label for="label">Notes</label>
                            <textarea v-model="current_record.notes" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="label">Status</label>
                            <select v-model="current_record.company_payment_type_id" class="form-control">
                                <option v-for="type in payment_types" value="{type.id}">{type.label}</option>
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
                current_company: state => state.current_company
            })
        },

        props: ['project_phase_id'],

        data() {
            return {
                loading: false,
                loading_btn: false,
                show_form: false,
                search_string: '',
                material_options: [],
                pagination: {
                    'current_page': 1
                },
                records: [],
                payment_types: [],
                current_record: null,
            }
        },

        mounted() {
            this.getPaymentTypes()
            this.getRecords()
            this.resetCurrentRecord()
        },

        methods: {
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/payment', {
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
                    })
            },

            getPaymentTypes() {
                return axios.get('/api/company/' + this.current_company.id + '/payment_type')
                            .then(res => {
                                this.payment_types = res.data
                            })
                            .catch(function (err) {

                            })
            },

            showForm(object) {
                if (object == 'add_record') {
                    this.resetCurrentRecord()
                } else {
                    object.material = {
                        value: this.current_record.material_id,
                        label: this.current_record.label,
                    }

                    this.current_record = object

                    this.current_record.material = {
                        value: this.current_record.material_id,
                        label: this.current_record.label,
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

                            this.current_record.material_id = this.current_record.material.value
                            this.current_record.label = this.current_record.material.label

                            return axios.put('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/payment/' + this.current_record.id, this.current_record)
                            .then(res => {
                                this.loading_btn = false
                                this.getRecords()

                                this.hideForm()

                                this.resetCurrentRecord()
                            })
                            .catch(err => {
                                console.log(err)
                                this.loading_btn = false
                            })
                        } else { // add
                            return axios.post('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/payment', this.current_record)
                            .then(res => {
                                this.loading_btn = false
                                this.getRecords()

                                if (this.current_record.id !== '0') {
                                    this.hideForm()
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

                return axios.delete('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/payment/' + object.id)
                    .then(res => {
                        this.getRecords()
                        this.resetCurrentRecord()
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },

            resetCurrentRecord() {
                this.current_record = {
                    id: 0,
                    amount: 0,
                    check_number: 0,
                    payment_date: 0,
                    company_payment_type_id: 0,
                    notes: '',
                }
            },

            doSearch() {
                this.pagination.current_page = 1
                this.getRecords()
            },

            onSearch(search) {
                this.search(search, this)
            },
        },

        watch: {
        }
    }
</script>