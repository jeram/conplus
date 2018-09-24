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
                            <th>Description</th>
                            <th>Ordered</th>
                            <th>Delivered</th>
                            <th>Capital</th>
                            <th>Paid</th>
                            <th>Payment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0 && !loading" v-for="(record, index) in records">
                            <td>{{record.description}}</td>
                            <td>{{record.ordered}}</td>
                            <td>{{record.delivered}}</td>
                            <td>{{record.capital | toCurrency}}</td>
                            <td>{{record.paid_amount | toCurrency}}</td>
                            <td>{{record.payment_date}}</td>
                            <td>
                                <a class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a class="btn btn-xs btn-danger" @click="deleteRecord(record)">Delete</a>
                            </td>
                        </tr>
                        <tr v-if="records.length <= 0 && !loading">
                            <td colspan="7"><em>No Record Found</em></td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="7"><i class="fa fa-circle-o-notch fa-spin"></i></td>
                        </tr>
                    </tbody>
                </table>
                <div class="row" v-if="total_capital_amount > 0">
                    <div class="col-md-12 text-right">
                        <em>Total Capital: {{total_capital_amount | toCurrency}}</em>
                    </div>
                </div>
                <div class="row" v-if="total_paid_amount > 0">
                    <div class="col-md-12 text-right">
                        <em>Total Paid: {{total_paid_amount | toCurrency}}</em>
                    </div>
                </div>
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Trade for {{current_client.name}}</span>
            <span slot="header" v-else>Edit: Trade for {{current_client.name}}</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Description</label>
                            <textarea v-validate="'required'" name="description" v-model="current_record.description" class="form-control"></textarea>
                            <span class="text-danger">{{ errors.first('description') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="label">Ordered</label>
                                    <input v-model="current_record.ordered" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="label">Delivered</label>
                                    <input v-model="current_record.delivered" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="label">Paid</label>
                                    <input v-model="current_record.paid_amount" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="label">Capital</label>
                                    <input type="number" v-model="current_record.capital" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="label">Payment Date</label>
                                   <datepicker v-model="current_record.payment_date" class="form-control"></datepicker>
                                </div>
                                <div class="col-md-4">
                                    <label for="label">Status</label>
                                    <select v-model="current_record.trade_status_id" class="form-control">
                                        <option v-for="status in statuses" :value="status.id">{{status.label}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="label">Attachment</label>
                            <div v-if="current_record.attachment_filename">
                                <img 
                                    :src="dropzoneOptions.params.destination_path + '/' + current_record.attachment_filename"
                                    style="height:50px">
                                <button 
                                    class="btn btn-xs btn-default"
                                    @click.prevent.self="removeAttachment">Remove Attachment</button>
                            </div>
                            <vue-dropzone 
                                    v-else
                                    ref="myVueDropzone" 
                                    id="dropzone" 
                                    @vdropzone-success="fileUploaded"
                                    :options="dropzoneOptions"></vue-dropzone>
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

<style>
    .dropzone .dz-message {
        margin: 0;
    }
    .dropzone {
        min-height: auto;
        padding: 16px 14px;
    }
</style>

<script>
    import { mapState, mapActions } from 'vuex'
    import vueDropzone from 'vue2-dropzone'
    
    export default {
        props: ['current_client'],

        components: {
            vueDropzone
        },

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
                total_capital_amount: 0,
                total_paid_amount: 0,
                attachments_to_remove: [],
                dropzoneOptions: {
                    url: null,
                    thumbnailHeight: 18,
                    thumbnailWidth: null,
                    params: [],
                    createImageThumbnails: false,
                    maxFiles: 1,
                    acceptedFiles: 'image/*',
                    headers: { "Authorization": 'Bearer ' + localStorage.getItem('api_token') }
                },
            }
        },

        created() {
            this.dropzoneOptions.url = '/api/company/' + this.current_company.id + '/upload'
            this.dropzoneOptions.params = {
                        'destination_path': 'files/company/' + this.current_company.id + '/project/' + this.current_project.id,
                    }
        },

        mounted() {
            this.getStatuses()
            this.getRecords()
            this.resetCurrentRecord()
        },

        methods: {
            getStatuses() {
                return axios.get('/api/company/' + this.current_company.id + '/trade_status')
                            .then(res => {
                                this.statuses = res.data
                            })
                            .catch(err => {
                                this.$root.handleErrors(err.response)
                            })
            },
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/client/' + this.current_client.id + '/trade', {
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
                        this.total_capital_amount = res.data.total_capital_amount
                        this.total_paid_amount = res.data.total_paid_amount
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

                            return axios.put('/api/company/' + this.current_company.id + '/client/' + this.current_client.id + '/trade/' + this.current_record.id, this.current_record)
                            .then(res => {
                                this.flash('Record has been successfully updated', 'success')

                                this.loading_btn = false
                                this.getRecords()
                                this.hideForm()
                                this.resetCurrentRecord()
                            })
                            .catch(err => {
                                this.loading = false
                                this.$root.handleErrors(err.response)
                            })
                        } else { // add
                            return axios.post('/api/company/' + this.current_company.id + '/client/' + this.current_client.id + '/trade', this.current_record)
                            .then(res => {
                                this.flash('Record has been successfully added', 'success')

                                this.loading_btn = false
                                this.getRecords()
                                this.hideForm()
                                this.resetCurrentRecord()
                            })
                            .catch(err => {
                                this.loading = false
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

                return axios.delete('/api/company/' + this.current_company.id + '/client/' + this.current_client.id + '/trade/' + object.id)
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
                    description: '',
                    ordered: '',
                    delivered: '',
                    capital: '',
                    paid_amount: '',
                    // payment_date: moment().format('MMM D, YYYY'),
                    payment_date: '',
                    trade_status_id: 0,
                    attachment_filename: '',
                }
            },

            doSearch() {
                this.pagination.current_page = 1
                this.getRecords()
            },

            onSearch(search) {
                this.search(search, this)
            },

            fileUploaded(file, response) {
                this.current_record.attachment_filename = response.filename

                if (!this.attachments_to_remove.includes(this.current_record.attachment_filename)) {
                    this.attachments_to_remove.push(this.current_record.attachment_filename)
                }
            },

            removeAttachment() {
                if (!this.attachments_to_remove.includes(this.current_record.attachment_filename)) {
                    this.attachments_to_remove.push(this.current_record.attachment_filename)
                }
                this.current_record.attachment_filename = ''
            },

            removeUnusedFiles() {                
                this.attachments_to_remove.forEach(filename => {
                    if(filename == this.current_record.attachment_filename) {
                        return
                    }

                    axios.delete('/api/company/' + this.current_company.id + '/upload/' + filename, {
                            data: {
                                path: this.dropzoneOptions.params.destination_path
                            }
                        })
                        .then(res => {
                            this.attachments_to_remove.splice(this.attachments_to_remove.indexOf(filename), 1);
                        })
                        .catch(err => {
                            this.$root.handleErrors(err.response)
                        })
                })
            }
        },

        watch: {
            current_client: function (newValue, oldValue) {
                this.search_string = ''
                this.getRecords()
                this.resetCurrentRecord()
            }
        }
    }
</script>