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
                            <th>Date</th>
                            <th>Notes</th>
                            <th>Attachment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0 && !loading" v-for="(record, index) in records">
                            <td>{{record.amount | toCurrency}}</td>
                            <td>{{record.payment_date}}</td>
                            <td>{{record.notes}}</td>
                            <td>
                                <a target="_blank" :href="dropzoneOptions.params.destination_path + '/' + record.attachment_filename"><img 
                                    v-if="record.attachment_filename"
                                    :src="dropzoneOptions.params.destination_path + '/' + record.attachment_filename"
                                    style="height:50px"
                                    class="img-thumbnail" /></a>
                            </td>
                            <td>
                                <span v-if="record.type.label=='Paid'" class="label label-default">{{record.type.label}}</span>
                                <span v-else-if="record.type.label=='Pending'" class="label label-info">{{record.type.label}}</span>
                                <span v-else class="label label-success">{{record.type.label}}</span>
                            </td>
                            <td>
                                <a class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a class="btn btn-xs btn-danger" @click="deleteRecord(record)">Delete</a>
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
                <div class="row" v-if="total_amount > 0">
                    <div class="col-md-12 text-right">
                        <em>Total selected: {{total_amount | toCurrency}}</em>
                    </div>
                </div>
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Deposit</span>
            <span slot="header" v-else>Edit: Deposit Info}</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Amount</label>
                            <input type="number" name="amount" v-validate="'required'" v-model="current_record.amount" class="form-control" min="0" step="any">
                            <span class="text-danger">{{ errors.first('amount') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="label">Deposit Date</label>
                            <datepicker v-model="current_record.payment_date" class="form-control"></datepicker>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="label">Notes</label>
                                    <textarea v-model="current_record.notes" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="label">Status</label>
                                    <select v-validate="'required'" name="status" v-model="current_record.company_deposit_type_id" class="form-control">
                                        <option v-for="type in deposit_types" :value="type.id">{{type.label}}</option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('status') }}</span>
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
        components: {
            vueDropzone
        },

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
                pagination: {
                    'current_page': 1
                },
                records: [],
                deposit_types: [],
                current_record: null,
                total_amount: 0,
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
            this.getDepositTypes()
            this.getRecords()
            this.resetCurrentRecord()
        },

        methods: {
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/deposit', {
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
                        this.total_amount = res.data.total_amount
                    })
                    .catch(err => {
                        this.loading = false
                        this.$root.handleErrors(err.response)
                    })
            },

            getDepositTypes() {
                return axios.get('/api/company/' + this.current_company.id + '/deposit_type')
                            .then(res => {
                                this.deposit_types = res.data
                            })
                            .catch(err => {
                                this.$root.handleErrors(err.response)
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
                this.removeUnusedFiles()
                this.getRecords()
            },

            handleSubmit() {
                this.$validator.validate().then(result => {
                    if (!result) {

                        this.loading_btn = false
                        
                    } else {

                        this.loading_btn = true

                        if (this.current_record.id > 0) { // edit

                            return axios.put('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/deposit/' + this.current_record.id, this.current_record)
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
                            return axios.post('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/deposit', this.current_record)
                            .then(res => {
                                this.flash('Record has been successfully added', 'success')
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

                return axios.delete('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/deposit/' + object.id)
                    .then(res => {
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
                    amount: '',
                    payment_date: moment().format('MMM D, YYYY'),
                    company_deposit_type_id: 0,
                    notes: '',
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
        }
    }
</script>