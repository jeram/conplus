<template>
    <div class="app-body" id="view">
        <section class="content">
           <div class="padding"> 
                <div class="row">
                    <div class="col-md-6">
                        <!-- LEFT CONTAINER -->
                        <h2>Project Summary<small> {{current_project.name}}</small></h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Deposits</span>
                                        <span class="info-box-number">{{total_deposits | toCurrency}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-share"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Payments</span>
                                        <span class="info-box-number">{{total_payments | toCurrency}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Goal Completion</h3>
                                    </div>
                                    <div class="box-body text-left">
                                        <div class="progress-group" v-if="phases.length > 0"  v-bind:key="index" v-for="(record, index) in phases">
                                            <span class="progress-text">{{typeof record.phase !== 'undefined' ? record.phase.label : ''}}</span>
                                            <span class="progress-number">({{record.percentage}}%) <b>{{record.used}}</b>/{{record.total}}</span>

                                            <div class="progress sm">
                                            <div class="progress-bar progress-bar-aqua" :style="{width: record.percentage + '%'}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!--<total-deposit-vs-total-payments></total-deposit-vs-total-payments>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2>Company Summary<small> {{current_company.name}}</small></h2>
                        <!-- RIGHT CONTAINER -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Upcoming Payments</h3>
                                    </div>
                                    <div class="box-body text-left">
                                        <ul class="todo-list">
                                            <li v-if="upcoming_payments.length > 0" v-bind:key="index" v-for="(record, index) in upcoming_payments">
                                                <span class="text">{{record.amount | toCurrency}}</span>
                                                <span class="label label-warning">{{record.project.name}}</span>
                                                <small class="label label-success"><i class="fa fa-clock-o"></i> {{record.payment_date | daysRemaining}}</small>
                                                <div class="actions pull-right">
                                                    {{record.payment_date | toHumanDate}}
                                                </div>
                                            </li>
                                            <li v-else>
                                                No Upcoming payment for the next 30 days 
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
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
                total_deposits: 0,
                total_payments: 0,
                phases: [],
                upcoming_payments: [],
            }
        },
        
        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        mounted() {
            // Total Deposits
			axios.get('/api/report/get_total_project_deposits', {
                        params: {
                            project_id: this.current_project.id,
                            company_id: this.current_company.id,
                        }
                        
                    })
                    .then(res => {
                        this.total_deposits = res.data
                    })
                    .catch(err => {

                    })
            // Total Payments
			axios.get('/api/report/get_total_project_payments', {
                        params: {
                            project_id: this.current_project.id,
                            company_id: this.current_company.id,
                        }
                        
                    })
                    .then(res => {
                        this.total_payments = res.data
                    })
                    .catch(err => {

                    })

                    
            // Project Phases
			axios.get('/api/report/get_phase_progress', {
                        params: {
                            project_id: this.current_project.id,
                            company_id: this.current_company.id,
                        }
                        
                    })
                    .then(res => {
                        this.phases = res.data
                    })
                    .catch(err => {

                    })
            
                    
            // Upcoming Payments
			axios.get('/api/report/get_upcoming_payments', {
                        params: {
                            company_id: this.current_company.id,
                        }
                        
                    })
                    .then(res => {
                        this.upcoming_payments = res.data
                    })
                    .catch(err => {
                        
                    })

        },

        beforeRouteEnter (to, from, next) {
            auth.check()
                .then((res) => {
                    next()
                })
                .catch((err) => {
                    window.location.href = '/login'
                })
        }
    }
</script>