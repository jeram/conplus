<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>Phases <small></small></h1>
        </section>

        <section class="content">
            <div class="row" style="margin-bottom:10px">
                <div class="col-md-12 text-right">
                    <a @click="showForm('add_record')" class="btn btn-success pull-right" v-if="!loading && available_percentage > 0"><i class="fa fa-plus"></i> Add</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" v-for="(phase, index) in project_phases">
                    <div class="info-box" v-bind:class="color_classes[index]">
                        <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{phase.label}}</span>
                            <span class="info-box-number">{{phase.total_cost_estimation}}</span>

                            <div class="progress">
                                <div class="progress-bar" v-bind:style="{width: phase.project_percentage + '%'}"></div>
                            </div>
                            <span class="progress-description">
                                {{phase.project_percentage}}%

                                <div class="pull-right">
                                    <a href="javascript:void(0)" class="btn-action" @click="showForm(phase)">
                                        <i class="fa fa-pencil" title="Edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn-action" @click="deleteRecord(phase)">
                                        <i class="fa fa-trash" title="Delete"></i>
                                    </a>
                                </div>
                            </span>
                        </div>
                        
                    </div>
                </div>
            </div>            
        </section>

        <modal v-if="show_form" @close="hideForm">
            <span slot="header" v-if="current_record.id == 0">New Phase</span>
            <span slot="header" v-else>{{ current_record.label }}</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Name</label>
                            <input type="text" v-validate="'required'" name="name" v-model="current_record.label" class="form-control">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="total_cost_estimation">Cost Estimation</label>
                            <input type="text" id="total_cost_estimation" name="total_cost_estimation" v-model="current_record.total_cost_estimation" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="total_cost_estimation">Phase Percentage ({{current_record.project_percentage}}%)</label>
                            <slider v-model="current_record.project_percentage" :max="available_percentage"></slider>
                        </div>
                        <!--
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <datepicker class="form-control pull-right" v-model="current_record.start_date"></datepicker>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="finish_date">End Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <datepicker class="form-control pull-right" v-model="current_record.finish_date"></datepicker>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" v-model="current_record.notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="hideForm">Close</button>
                        <input type="submit" class="btn btn-primary pull-right" :disabled="loading_btn" value="Submit">
                    </div> 
                </form>
            </div>
        </modal>
    </div>
</template>

<style>
    .btn-action {
        color: #ffffff;
        display: none;
    }
    .info-box:hover .btn-action {
        display: inline-block;
    }
</style>

<script>
    import auth from '../auth'
    import { mapState, mapActions } from 'vuex'

    export default {

        data() {
            return {
                auth: auth,
                loading: false,
                loading_btn: false,
                current_record: {
                    label: '',
                    total_cost_estimation: '',
                    start_date: '',
                    finish_date: '',
                    project_percentage: 0,
                },
                phases: [],
                show_form: false,
                available_percentage: 100,
                color_classes: ['bg-yellow', 'bg-green', 'bg-red', 'bg-light-blue', 'bg-aqua',  'bg-gray', 'bg-navy', 'bg-teal', 'bg-purple', 'bg-orange', 'bg-maroon', 'bg-black', 'bg-yellow-active', 'bg-green-active', 'bg-red-active', 'bg-light-blue-active', 'bg-aqua-active',  'bg-gray-active', 'bg-navy-active', 'bg-teal-active', 'bg-purple-active', 'bg-orange-active', 'bg-maroon-active', 'bg-black-active'],
            }
        },
        
        computed: {
            ...mapState({
                project_phases: state => state.project_phases,
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        created() {
            this.getRecords()
        },

        mounted() {

        },

        methods: {
            getRecords() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/phase')
                    .then(res => {
                        this.loading = false
                        this.setProjectPhases(res.data)

                        this.calculateAvailablePecentage()
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
                    this.available_percentage = object.project_percentage + this.available_percentage
                    this.current_record = object
                }
                this.show_form = true
            },

            hideForm() {
                this.show_form = false
                this.getRecords()
                this.calculateAvailablePecentage()
            },

            handleSubmit() {

                this.$validator.validate().then(result => {
                    if (!result) {

                        this.loading_btn = false
                        
                    } else {
                        this.loading_btn = true
                        if (this.current_record.id > 0) { // edit
                            return axios.put('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/phase/' + this.current_record.id, this.current_record)
                             .then(res => {
                                this.flash('User has been successfully updated', 'success')
                                this.loading_btn = false
                                this.show_form = false
                                this.getRecords()
                                this.calculateAvailablePecentage()
                                this.resetCurrentRecord()
                             })
                             .catch(err => {
                                this.loading_btn = false
                                this.$root.handleErrors(err.response)
                             })
                        } else { // add
                            return axios.post('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/phase', this.current_record)
                            .then(res => {
                                this.flash('User has been successfully added', 'success')
                                this.loading_btn = false
                                this.show_form = false
                                this.getRecords()
                                this.calculateAvailablePecentage()
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

                return axios.delete('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/phase/' + object.id)
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
                    label: '',
                    total_cost_estimation: '',
                    start_date: '',
                    finish_date: '',
                    project_percentage: 0,
                }
            },

            calculateAvailablePecentage() {
                if (this.project_phases.length > 0) {
                    this.available_percentage = 100
                    this.project_phases.forEach((phase, index) => {
                        this.available_percentage -= phase.project_percentage
                    })
                }
            },

            ...mapActions(['setProjectPhases'])
        },

        watch: {
        },

        beforeRouteEnter (to, from, next) {
            auth.check()
                .then((res) => {
                    next()
                })
                .catch((err) => {
                    next({name: 'login'})
                })
        }
    }
</script>