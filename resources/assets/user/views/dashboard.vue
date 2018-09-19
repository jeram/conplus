<template>
    <div class="app-body" id="view">
        <section class="content">
           <div class="padding">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Deposits</span>
                                <span class="info-box-number">4545,456,545.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Payments</span>
                                <span class="info-box-number">100,100.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                    <p class="text-center">
                        <strong>Goal Completion</strong>
                    </p>

                    <div class="progress-group">
                        <span class="progress-text">Floor</span>
                        <span class="progress-number"><b>160</b>/200</span>

                        <div class="progress sm">
                        <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Roof</span>
                        <span class="progress-number"><b>310</b>/400</span>

                        <div class="progress sm">
                        <div class="progress-bar progress-bar-red" style="width: 50%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Windows</span>
                        <span class="progress-number"><b>480</b>/800</span>

                        <div class="progress sm">
                        <div class="progress-bar progress-bar-green" style="width: 20%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Rooms</span>
                        <span class="progress-number"><b>250</b>/500</span>

                        <div class="progress sm">
                        <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    </div>
                </div>
            </div>
        </section>
		<!--
        <div class="padding">
            <div class="box effect8">
                <div class="box-body">
                    
                </div>
            </div>
        </div>

        <div class="padding">
            <div class="box effect8">
                <div class="box-body">
                    
                </div>
            </div>
        </div>-->
    </div>
</template>

<script>
    import auth from '../auth'

    export default {

        data() {
            return {
                auth: auth
            }
        },
        
        computed: {
            
        },

        mounted() {
			 
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