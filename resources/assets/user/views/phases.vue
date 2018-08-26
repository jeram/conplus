<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>Phases <small></small></h1>
        </section>

        <modal v-if="showAddEditModal" @close="showAddEditModal = false">
            <span slot="header" v-if="current_phase.id == 0">New Phase</span>
            <span slot="header" v-else>{{ current_phase.label }}</span>
            <div slot="body">
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Name</label>
                            <input type="text" v-validate="'required'" name="label" v-model="current_phase.label" class="form-control">
                            <span class="text-danger">{{ errors.first('label') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="total_cost_estimation">Cost Estimation</label>
                            <input type="text" id="total_cost_estimation" name="total_cost_estimation" v-model="current_phase.total_cost_estimation" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="total_cost_estimation">Phase Percentage ({{current_phase.project_percentage}}%)</label>
                            <slider v-model="current_phase.project_percentage" :max="available_percentage"></slider>
                        </div>
                        <!--
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <datepicker class="form-control pull-right" v-model="current_phase.start_date"></datepicker>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="finish_date">End Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <datepicker class="form-control pull-right" v-model="current_phase.finish_date"></datepicker>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" v-model="current_phase.notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="showAddEditModal = false">Close</button>
                        <input type="submit" class="btn btn-primary pull-right" :disabled="loading_btn" value="Submit">
                    </div> 
                </form>
            </div>
        </modal>

        <section class="content">
            <div class="row">
                <div class="col-md-12 text-right">
                    <a @click="showForm('add_record')" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</a>
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
                            </span>
                        </div>
                    </div>
                </div>
            </div>            
        </section>
    </div>
</template>

<script>
    import auth from '../auth'
    import { mapState, mapActions } from 'vuex'

    export default {

        data() {
            return {
                auth: auth,
                loading: false,
                loading_btn: false,
                current_phase: {
                    label: '',
                    total_cost_estimation: '',
                    start_date: '',
                    finish_date: '',
                    project_percentage: 0,
                },
                phases: [],
                showAddEditModal: false,
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
            this.getPhases()
        },

        mounted() {

        },

        methods: {
            getPhases() {
                this.loading = true
                axios.get('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/phase')
                    .then(res => {
                        this.loading = false
                        this.setProjectPhases(res.data)

                        this.calculateAvailablePecentage()
                    })
                    .catch(function (err) {
                        this.loading = false
                    })
            },

            showForm(object) {
                if (object == 'add_record') {
                    this.resetCurrentPhase()
                    this.showAddEditModal = true

                } else {
                    
                    this.current_phase = object
                    this.showAddEditModal = true
                    
                }
            },

            handleSubmit() {

                this.$validator.validate().then(result => {
                    if (!result) {

                        this.loading_btn = false
                        
                    } else {
                        this.loading_btn = true
                        return axios.post('/api/company/' + this.current_company.id + '/project/' + this.current_project.id + '/phase', this.current_phase)
                             .then(res => {
                                this.loading_btn = false
                                this.showAddEditModal = false
                                this.getPhases()
                                this.calculateAvailablePecentage()
                                this.resetCurrentPhase()                                
                             })
                             .catch(err => {
                                console.log(err)
                                this.loading_btn = false
                             })
                    }
                });
                
            },

            resetCurrentPhase() {
                this.current_phase = {
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