<template>
    <input v-bind:value="value" data-slider-min="0" v-bind:data-slider-max="max"
                    data-slider-step="1" v-bind:data-slider-value="value" data-slider-orientation="horizontal"
                    data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue" />
</template>
<script>
    export default {

        props: ['value', 'max'],

        computed: {
        },

        mounted: function() {
            var self = this
            $(this.$el).slider().on('slideStop', function(ev){
                var newVal = $(self.$el).data('slider').getValue()
                self.$emit('input', newVal)
            })

            /*$(this.$el).datepicker({
                autoclose: true,
            }).on('changeDate', function(e) {
                self.$emit('input', e.format(0,"mm/dd/yyyy"))
            });*/
        },
    }
</script>