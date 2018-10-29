<template>
    <section class="charts">
        <!--<h3>pie-basic</h3>-->
        <vue-highcharts :options="pieOptions" ref="chart"></vue-highcharts>
    </section>
</template>
<script>
    import VueHighcharts from "vue2-highcharts"
    // https://www.npmjs.com/package/vue2-highcharts

    export default {
        components: {
            VueHighcharts
        },
        data() {
            return {
                pieOptions: {
                    chart: {
                        type: "pie",
                        options3d: {
                            enabled: true,
                            alpha: 45
                        }
                    },
                    title: {
                        text: 'Total Deposits Vs Total Payments'
                    },
                    subtitle: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    },
                    plotOptions: {
                        pie: {
                            // innerSize: 100,
                            // depth: 45
                        }
                    },
                    series: []
                }
            }
        },
        mounted() {
            this.loadData()
        },
        methods: {
            loadData() {
                let lineCharts = this.$refs.chart
                lineCharts.delegateMethod('showLoading', 'Loading...')
                setTimeout(() => {

                    let chartData = {
                        data: [
                            ["Remaining Deposit", 100-40],
                            ["Total Payments", 40],
                        ]
                    }
                    lineCharts.addSeries(chartData)
                    lineCharts.hideLoading()

                }, 2000)
            }
        }
    }
</script>
