<template>
    <div>
        <div class="row">
            <div class="col-md-8">                
                <div class="form-group">
                    <label>
                        Project Statuses
                    </label>
                    <div class="overlay" v-if="project_status_loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <form v-on:submit.prevent="submitProjectStatuses">
                        <div class="form-group">
                            <json-tag-editor @change="updateProjectStatuses" v-if="!project_status_loading" :model="project_statuses"></json-tag-editor>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" :disabled="disable_pstatuses_btn" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h4>Project Statuses</h4>
                    <p>This will be the status for each project.</p>
                    <p>Example: 'In-progress', 'Completed'</p> 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>
                        Project Types
                    </label>
                    <div class="overlay" v-if="project_types_loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <form v-on:submit.prevent="submitProjectTypes">
                        <div class="form-group">
                            <json-tag-editor @change="updateProjectTypes" v-if="!project_types_loading" :model="project_types"></json-tag-editor>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" :disabled="disable_ptypes_btn" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h4>Project Types</h4>
                    <p>This will be the type for each project.</p>
                    <p>Example: 'Commercial Building', 'Residential'</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>
                        Project Note Statuses
                    </label>
                    <div class="overlay" v-if="project_note_statuses_loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <form v-on:submit.prevent="submitProjectNoteStatuses">
                        <div class="form-group">
                            <json-tag-editor @change="updateProjectNoteStatuses" v-if="!project_note_statuses_loading" :model="project_note_statuses"></json-tag-editor>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" :disabled="disable_pn_statuses_btn" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h4>Project Note Statuses</h4>
                    <p>This will be a status for each project note.</p>
                    <p>Example: 'To Do', 'Pending', 'For Followup'</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>
                        Project Material Statuses
                    </label>
                    <div class="overlay" v-if="project_material_statuses_loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <form v-on:submit.prevent="submitProjectMaterialStatuses">
                        <div class="form-group">
                            <json-tag-editor @change="updateProjectMaterialStatuses" v-if="!project_material_statuses_loading" :model="project_material_statuses"></json-tag-editor>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" :disabled="disable_pm_statuses_btn" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h4>Project Material Statuses</h4>
                    <p>This will be a status for each project material.</p>
                    <p>Example: 'Delivered on site', 'To Purchase'</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>
                        Payment Types
                    </label>
                    <div class="overlay" v-if="payment_types_loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <form v-on:submit.prevent="submitPaymentTypes">
                        <div class="form-group">
                            <json-tag-editor @change="updatePaymentTypes" v-if="!payment_types_loading" :model="company_payment_types"></json-tag-editor>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" :disabled="disable_payment_types_btn" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h4>Payment Types</h4>
                    <p>This will be a type for each payment.</p>
                    <p>Example: 'Paid', 'Pending'</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>
                        Deposit Types
                    </label>
                    <div class="overlay" v-if="deposit_types_loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <form v-on:submit.prevent="submitDepositTypes">
                        <div class="form-group">
                            <json-tag-editor @change="updateDepositTypes" v-if="!deposit_types_loading" :model="company_deposit_types"></json-tag-editor>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" :disabled="disable_deposit_types_btn" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h4>Deposit Types</h4>
                    <p>This will be a type for each deposit.</p>
                    <p>Example: 'Paid', 'Pending'</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>
                        Unit Of Measurements
                    </label>
                    <div class="overlay" v-if="units_loading">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <form v-on:submit.prevent="submitUnits">
                        <div class="form-group">
                            <json-tag-editor @change="updateUnits" v-if="!units_loading" :model="units"></json-tag-editor>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" :disabled="disable_units_btn" class="btn btn-primary pull-right">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h4>Unit Of Measurements</h4>
                    <p>This will be the units to use on construction materials.</p>
                    <p>Example: 'Gallons', 'Pcs'</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'

    export default {
        computed: {
            ...mapState({
                current_company: state => state.current_company
            })
        },

        props: {
            
        },

        data() {
            return {
                project_statuses: null,
                disable_pstatuses_btn: true,
                project_status_loading: true,

                project_types: null,
                disable_ptypes_btn: true,
                project_types_loading: true,

                project_note_statuses: null,
                disable_pn_statuses_btn: true,
                project_note_statuses_loading: true,

                project_material_statuses: null,
                disable_pm_statuses_btn: true,
                project_material_statuses_loading: true,

                company_payment_types: null,
                disable_payment_types_btn: true,
                payment_types_loading: true,

                company_deposit_types: null,
                disable_deposit_types_btn: true,
                deposit_types_loading: true,

                units: null,
                disable_units_btn: true,
                units_loading: true,
            }
        },

        created() {
            this.getProjectStatuses()
            this.getProjectTypes()
            this.getProjectNoteStatuses()
            this.getProjectMaterialStatuses()
            this.getPaymentTypes()
            this.getDepositTypes()
            this.getUnits()
        },

        methods: {
            /* Project Statuses */
            getProjectStatuses() {
                return axios.get('/api/company/' + this.current_company.id + '/project_status')
                            .then(res => {
                                let projectStatuses = []
                                
                                if (res.data.length > 0) {
                                    res.data.forEach((status_data, index) => {
                                        projectStatuses.push({
                                            id: status_data.id,
                                            value: status_data.label,
                                        })
                                    })
                                }
                                this.project_statuses = projectStatuses
                                this.project_status_loading = false
                            })
                            .catch(function (err) {

                            })
            },
            updateProjectStatuses(values) {
                this.project_statuses = values
                this.disable_pstatuses_btn = false
            },
            submitProjectStatuses() {
                this.disable_pstatuses_btn = true

                // convert values to model compatible
                let projectStatuses = []
                                
                if (this.project_statuses.length > 0) {
                    this.project_statuses.forEach((status_data, index) => {
                        projectStatuses.push({
                            id: status_data.id,
                            label: status_data.value,
                        })
                    })
                }
                this.project_statuses = projectStatuses

                return axios.post('/api/company/' + this.current_company.id + '/project_status', {project_statuses: this.project_statuses})
                     .then(res => {
                        this.disable_pstatuses_btn = false
                     })
                     .catch(err => {
                        this.disable_pstatuses_btn = false
                     })
            },

            /* Project Types */
            getProjectTypes() {
                return axios.get('/api/company/' + this.current_company.id + '/project_type')
                            .then(res => {
                                let projectTypes = []
                                
                                if (res.data.length > 0) {
                                    res.data.forEach((status_data, index) => {
                                        projectTypes.push({
                                            id: status_data.id,
                                            value: status_data.label,
                                        })
                                    })
                                }
                                this.project_types = projectTypes
                                this.project_types_loading = false
                            })
                            .catch(function (err) {

                            })
            },
            updateProjectTypes(values) {
                this.project_types = values
                this.disable_ptypes_btn = false
            },
            submitProjectTypes() {
                this.disable_ptypes_btn = true

                // convert values to model compatible
                let projectTypes = []
                                
                if (this.project_types.length > 0) {
                    this.project_types.forEach((types_data, index) => {
                        projectTypes.push({
                            id: types_data.id,
                            label: types_data.value,
                        })
                    })
                }
                this.project_types = projectTypes

                return axios.post('/api/company/' + this.current_company.id + '/project_type', {project_types: this.project_types})
                     .then(res => {
                        this.disable_ptypes_btn = false
                     })
                     .catch(err => {
                        this.disable_ptypes_btn = false
                     })
            },

            /* Project Note Statuses */
            getProjectNoteStatuses() {
                return axios.get('/api/company/' + this.current_company.id + '/project_note_status')
                            .then(res => {
                                let projectNoteStatuses = []
                                
                                if (res.data.length > 0) {
                                    res.data.forEach((status_data, index) => {
                                        projectNoteStatuses.push({
                                            id: status_data.id,
                                            value: status_data.label,
                                        })
                                    })
                                }
                                this.project_note_statuses = projectNoteStatuses
                                this.project_note_statuses_loading = false
                            })
                            .catch(function (err) {

                            })
            },
            updateProjectNoteStatuses(values) {
                this.project_note_statuses = values
                this.disable_pn_statuses_btn = false
            },
            submitProjectNoteStatuses() {
                this.disable_pn_statuses_btn = true

                // convert values to model compatible
                let projectNoteStatuses = []
                                
                if (this.project_note_statuses.length > 0) {
                    this.project_note_statuses.forEach((note_statuses_data, index) => {
                        projectNoteStatuses.push({
                            id: note_statuses_data.id,
                            label: note_statuses_data.value,
                        })
                    })
                }
                this.project_note_statuses = projectNoteStatuses

                return axios.post('/api/company/' + this.current_company.id + '/project_note_status', {project_note_statuses: this.project_note_statuses})
                     .then(res => {
                        this.disable_pn_statuses_btn = false
                     })
                     .catch(err => {
                        this.disable_pn_statuses_btn = false
                     })
            },

            /* Project Material Statuses */
            getProjectMaterialStatuses() {
                return axios.get('/api/company/' + this.current_company.id + '/project_material_status')
                            .then(res => {
                                let projectMaterialStatuses = []
                                
                                if (res.data.length > 0) {
                                    res.data.forEach((status_data, index) => {
                                        projectMaterialStatuses.push({
                                            id: status_data.id,
                                            value: status_data.label,
                                        })
                                    })
                                }
                                this.project_material_statuses = projectMaterialStatuses
                                this.project_material_statuses_loading = false
                            })
                            .catch(function (err) {

                            })
            },
            updateProjectMaterialStatuses(values) {
                this.project_material_statuses = values
                this.disable_pm_statuses_btn = false
            },
            submitProjectMaterialStatuses() {
                this.disable_pm_statuses_btn = true

                // convert values to model compatible
                let projectMaterialStatuses = []
                                
                if (this.project_material_statuses.length > 0) {
                    this.project_material_statuses.forEach((status_data, index) => {
                        projectMaterialStatuses.push({
                            id: status_data.id,
                            label: status_data.value,
                        })
                    })
                }
                this.project_material_statuses = projectMaterialStatuses

                return axios.post('/api/company/' + this.current_company.id + '/project_material_status', {project_material_statuses: this.project_material_statuses})
                     .then(res => {
                        this.disable_pm_statuses_btn = false
                     })
                     .catch(err => {
                        this.disable_pm_statuses_btn = false
                     })
            },

            /* Payment Types */
            getPaymentTypes() {
                return axios.get('/api/company/' + this.current_company.id + '/payment_type')
                            .then(res => {
                                let paymentTypes = []
                                
                                if (res.data.length > 0) {
                                    res.data.forEach((type_data, index) => {
                                        paymentTypes.push({
                                            id: type_data.id,
                                            value: type_data.label,
                                        })
                                    })
                                }
                                this.company_payment_types = paymentTypes
                                this.payment_types_loading = false
                            })
                            .catch(function (err) {

                            })
            },
            updatePaymentTypes(values) {
                this.company_payment_types = values
                this.disable_payment_types_btn = false
            },
            submitPaymentTypes() {
                this.disable_payment_types_btn = true

                // convert values to model compatible
                let paymentTypes = []
                                
                if (this.company_payment_types.length > 0) {
                    this.company_payment_types.forEach((type_data, index) => {
                        paymentTypes.push({
                            id: type_data.id,
                            label: type_data.value,
                        })
                    })
                }
                this.company_payment_types = paymentTypes

                return axios.post('/api/company/' + this.current_company.id + '/payment_type', {company_payment_types: this.company_payment_types})
                     .then(res => {
                        this.disable_payment_types_btn = false
                     })
                     .catch(err => {
                        this.disable_payment_types_btn = false
                     })
            },

            /* Deposit Types */
            getDepositTypes() {
                return axios.get('/api/company/' + this.current_company.id + '/deposit_type')
                            .then(res => {
                                let depositTypes = []
                                
                                if (res.data.length > 0) {
                                    res.data.forEach((type_data, index) => {
                                        depositTypes.push({
                                            id: type_data.id,
                                            value: type_data.label,
                                        })
                                    })
                                }
                                this.company_deposit_types = depositTypes
                                this.deposit_types_loading = false
                            })
                            .catch(function (err) {

                            })
            },
            updateDepositTypes(values) {
                this.company_deposit_types = values
                this.disable_deposit_types_btn = false
            },
            submitDepositTypes() {
                this.disable_deposit_types_btn = true

                // convert values to model compatible
                let depositTypes = []
                                
                if (this.company_deposit_types.length > 0) {
                    this.company_deposit_types.forEach((type_data, index) => {
                        depositTypes.push({
                            id: type_data.id,
                            label: type_data.value,
                        })
                    })
                }
                this.company_deposit_types = depositTypes

                return axios.post('/api/company/' + this.current_company.id + '/deposit_type', {company_deposit_types: this.company_deposit_types})
                     .then(res => {
                        this.disable_deposit_types_btn = false
                     })
                     .catch(err => {
                        this.disable_deposit_types_btn = false
                     })
            },

            /* Units */
            getUnits() {
                return axios.get('/api/company/' + this.current_company.id + '/unit_of_measurement')
                            .then(res => {
                                let units = []
                                
                                if (res.data.length > 0) {
                                    res.data.forEach((type_data, index) => {
                                        units.push({
                                            id: type_data.id,
                                            value: type_data.label,
                                        })
                                    })
                                }
                                this.units = units
                                this.units_loading = false
                            })
                            .catch(function (err) {

                            })
            },
            updateUnits(values) {
                this.units = values
                this.disable_units_btn = false
            },
            submitUnits() {
                this.disable_units_btn = true

                // convert values to model compatible
                let units = []
                                
                if (this.units.length > 0) {
                    this.units.forEach((unit_data, index) => {
                        units.push({
                            id: unit_data.id,
                            label: unit_data.value,
                        })
                    })
                }
                this.units = units

                return axios.post('/api/company/' + this.current_company.id + '/unit_of_measurement', {unit_of_measurements: this.units})
                     .then(res => {
                        this.disable_units_btn = false
                     })
                     .catch(err => {
                        this.disable_units_btn = false
                     })
            },

        }

    }
</script>