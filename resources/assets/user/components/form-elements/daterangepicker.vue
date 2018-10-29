<script>
    export default {
        // template: '<input v-bind:value="value" />',
        template: '<button type="button" class="btn btn-default pull-right" v-bind:start="start" v-bind:end="end">' +
                        '<span>' +
                        '<i class="fa fa-calendar"></i> <span v-if="localStart==null && localEnd==null">All Time</span>' +
                        '<span v-else>{{localStart}} - {{localEnd}}</span>' +
                        '</span> ' +
                        '<i class="fa fa-caret-down"></i>' +
                    '</button>',

        props: ['start', 'end'],

        data() {
            return {
                localStart: this.start,
                localEnd: this.end,
            }
        },

        computed: {
        },

        mounted: function() {
            var self = this

            $(this.$el).daterangepicker(
                {
                    ranges   : {
                        'Today'       : [moment(), moment()],
                        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'All Time'    : [null, null]
                    },
                    //startDate: moment().subtract(29, 'days'),
                    // endDate  : moment()
                    // alwaysShowCalendars: true,
                    startDate:  typeof this.start != null ? moment(this.start, 'MMM D, YYYY') : null,
                    endDate  :  typeof this.end != null ? moment(this.end, 'MMM D, YYYY') : null,
                },
                function (_start, _end, _period) {
                    
                    self.localStart = _start.isValid() ? _start.format('MMM D, YYYY') : null
                    self.localEnd = _end.isValid() ? _end.format('MMM D, YYYY') : null                    
                    
                    self.$emit('update', self.localStart, self.localEnd)

                }
            )

            // fix Nan issue when custom range is selected having all time is the previously selected
            $(this.$el).on('showCalendar.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate
                var endDate = picker.endDate

                if (!startDate.isValid() && !endDate.isValid()) {
                    picker.startDate = moment().subtract(29, 'days')
                    picker.endDate = moment()

                    picker.hide()
                    picker.show()
                }
            });
        },
    }
</script>