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
                            <th>Material</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="records.length > 0 && !loading" v-bind:key="index" v-for="(record, index) in records">
                            <td>{{record.label}}</td>
                            <td>{{record.quantity}}</td>
                            <td>{{record.price | toCurrency}}</td>
                            <td>{{record.total_price}}</td>
                            <td>
                                <span class="badge label-info label-md" title="To Purchase">
                                    {{record.to_order_qty}}
                                </span>&nbsp;
                                <span class="badge label-primary" title="Warehouse">
                                    {{record.warehouse_qty}}
                                </span>&nbsp;
                                <span class="badge label-warning" title="On site (unused)">
                                    {{record.on_site_unused_qty}}
                                </span>&nbsp;
                                <span class="badge label-success" title="On site (used)">
                                    {{record.used_qty}}
                                </span>
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
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getRecords()"></pagination>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <span class="badge label-info">
                    To Purchase
                </span>&nbsp;
                <span class="badge label-primary">
                    Warehouse
                </span>&nbsp;
                <span class="badge label-warning">
                    On Site (unused)
                </span>&nbsp;
                <span class="badge label-success">
                    On Site (used)
                </span>
            </div>
        </div>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Project Material</span>
            <span slot="header" v-else>Edit: {{ current_record.label }}</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Material</label>
                            <typeahead v-validate="'required'" v-model="current_record.material" :url="material_search_url" class="form-control"></typeahead>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="label">Quantity</label>
                                    <input type="number" v-validate="'required'" name="quantity" v-model="current_record.quantity" class="form-control" @input="calculateTotalPrice" min="0" step="any">
                                    <span class="text-danger">{{ errors.first('quantity') }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="label">Price</label>
                                    <input type="number" v-validate="''" @input="calculateTotalPrice" min="0" step="any" v-model="current_record.price" class="form-control">
                                    <span class="text-danger">{{ errors.first('price') }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="label">Total Price</label>
                                    <input type="number" v-model="current_record.total_price" readonly min="0" step="any" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend>Qty per Location <div class="pull-right text-sm" v-bind:class="{ 'text-danger': total_qty_per_location != current_record.quantity }" style="margin-top: 12px;">Total: {{total_qty_per_location}}</div></legend>
                                <div class="col-md-3">
                                    <label for="label">To Purchase</label>
                                    <input type="number" v-model="current_record.to_order_qty" min="0" step="any" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="label">Warehouse</label>
                                    <input type="number" v-model="current_record.warehouse_qty" min="0" step="any" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="label">On site (unused)</label>
                                    <input type="number" v-model="current_record.on_site_unused_qty" min="0" step="any" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="label">On site (used)</label>
                                    <input type="number" v-model="current_record.used_qty" min="0" step="any" class="form-control">
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <label for="label">Notes</label>
                            <textarea v-model="current_record.notes" class="form-control"></textarea>
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
                material_search_url: '',
                material_options: [],
                total_qty_per_location: 0,
                pagination: {
                    'current_page': 1
                },
                records: [],
                current_record: null,
            }
        },

        created() {
            this.material_search_url = '/api/company/' + this.current_company.id + '/materials?export_type=autocomplete'

            if (this.project_phase_id) {
                this.getRecords()
            }
            
            this.resetCurrentRecord()
        },

        methods: {
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/project_material', {
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

            showForm(object) {
                if (object == 'add_record') {
                    this.resetCurrentRecord()
                } else {
                    /*object.material = {
                        value: this.current_record.material_id,
                        text: this.current_record.label,
                    }*/
                    // console.log(object)

                    this.current_record = object

                    this.current_record.material = {
                        value: this.current_record.material_id,
                        text: this.current_record.label,
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

                        /*let qty_per_location = this.current_record.to_order_qty + this.current_record.warehouse_qty + this.current_record.on_site_unused_qty + this.current_record.used_qty

                        if (qty_per_location != this.current_record.quantity) {

                        }*/

                        this.loading_btn = true

                        if (this.current_record.id > 0) { // edit
                            console.log(this.current_record.material)
                            this.current_record.material_id = this.current_record.material.value
                            this.current_record.label = this.current_record.material.text

                            return axios.put('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/project_material/' + this.current_record.id, this.current_record)
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
                            return axios.post('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/project_material', this.current_record)
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

                return axios.delete('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/project_material/' + object.id)
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
                    project_phase_id: this.project_phase_id,
                    material: {
                        value: 0,
                        label: '',
                        text: ''
                    },
                    quantity: 0,
                    price: 0,
                    notes: '',
                    total_price: 0,
                    to_order_qty: 0,
                    warehouse_qty: 0,
                    on_site_unused_qty: 0,
                    used_qty: 0,
                    material_id: 0,
                    label: ''
                }
            },

            doSearch() {
                this.pagination.current_page = 1
                this.getRecords()
            },

            onSearch(search) {
                this.search(search, this)
            },

            /*search: _.debounce( (search, self) => {
                axios.get('/api/company/' + self.current_company.id + '/materials', {
                        params: {
                            export_type: 'select2-option',
                            q: search,
                            page: 1,
                        }
                    })
                    .then(res => {
                        //self.loading = false
                        self.material_options = res.data
                        //self.pagination = res.data.pagination
                    })
                    .catch(err => {
                        //self.loading = false
                        this.$root.handleErrors(err.response)
                    })
            }, 350),*/

            calculateTotalPrice() {
                this.current_record.total_price = parseFloat(this.current_record.quantity * this.current_record.price)
            },

            calculateTotalQtyPerLocation() {
                this.total_qty_per_location = (parseFloat(this.current_record.to_order_qty) || 0) + (parseFloat(this.current_record.warehouse_qty) || 0) + (parseFloat(this.current_record.on_site_unused_qty) || 0) + (parseFloat(this.current_record.used_qty) || 0)
            }
        },

        watch: {
            'project_phase_id': function (newVal, oldVal) {
                this.project_phase_id = newVal
                this.getRecords()
            },
            'current_record.material': function (newVal, oldVal) {
                this.current_record.material_id = newVal.value
                this.current_record.label = newVal.text

                // console.log(newVal)
                // console.log(this.current_record.material)
                // this.current_record.material_id = this.current_record.material.value
                // this.current_record.label = this.current_record.material.label
            },
            'current_record.to_order_qty': function (newVal, oldVal) {
                this.calculateTotalQtyPerLocation()
            },
            'current_record.warehouse_qty': function (newVal, oldVal) {
                this.calculateTotalQtyPerLocation()
            },
            'current_record.on_site_unused_qty': function (newVal, oldVal) {
                this.calculateTotalQtyPerLocation()
            },
            'current_record.used_qty': function (newVal, oldVal) {
                this.calculateTotalQtyPerLocation()
            },
        }
    }
</script>