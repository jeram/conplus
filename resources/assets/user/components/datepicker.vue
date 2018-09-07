<script>

    export default {
        template: '<input v-bind:value="value" />',

        props: ['value'],

        computed: {
        },

        mounted: function() {
            var self = this
            $(this.$el).datepicker({
                autoclose: true,
            }).on('changeDate', function(e) {
                self.$emit('input', e.format(0,"M d, yyyy"))
            });
        },
    }
</script>