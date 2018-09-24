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
                            <th>Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0" v-for="(record, index) in records">
                            <td>{{record.name}}</td>
                            <td>{{record.unit.label}}</td>
                            <td>
                                <a class="btn btn-xs btn-info" @click="showForm(record)">Edit</a>
                                <a class="btn btn-xs btn-danger" @click="deleteRecord(record)">Delete</a>
                            </td>
                        </tr>
                        <tr v-if="records.length <= 0">
                            <td colspan="3"><em>No Record Found</em></td>
                        </tr>                        
                    </tbody>
                </table>
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Material</span>
            <span slot="header" v-else>Edit: {{ current_record.name }}</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Name</label>
                            <input type="text" v-validate="'required'" name="name" v-model="current_record.name" class="form-control">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="total_cost_estimation">Unit of Measurement</label>
                            <select v-model="current_record.unit_of_measurement_id" class="form-control col-md-2">
                                <option v-for="unit in units" :value="unit.id">{{unit.label}}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('unit_of_measurement_id') }}</span>
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

        props: {
            
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
                units: [],
                current_record: {
                    id: 0,
                    name: '',
                    unit_of_measurement_id: '',
                },
                showAddEditModal: false,
            }
        },

        mounted() {
            this.getRecords()
            this.getUnits()
        },

        methods: {
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/materials', {
                        params: {
                            export_type: 'data-table',
                            q: this.search_string,
                            page: this.pagination.current_page
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

            getUnits() {
                return axios.get('/api/company/' + this.current_company.id + '/unit_of_measurement')
                    .then(res => {
                        this.units = res.data
                        this.units_loading = false
                    })
                    .catch(function (err) {
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
                        return axios.post('/api/company/' + this.current_company.id + '/materials', this.current_record)
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
                });
                
            },

            deleteRecord(object) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    return false
                }

                return axios.delete('/api/company/' + this.current_company.id + '/materials/' + object.id)
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
                    unit_of_measurement_id: '',
                }
            },

            doSearch() {
                this.pagination.current_page = 1
                this.getRecords()
            }
        }

    }
</script>