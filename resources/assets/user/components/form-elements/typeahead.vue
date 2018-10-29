<script>
    export default {
        template: '<input v-bind:value="value.text" />',

       props: ['value','url'],

        data() {
            return {
            }
        },

        computed: {
        },

        mounted: function() {
            var self = this

            $(this.$el).typeahead({
                onSelect: function(item) {
                    self.$emit('input', item)
                },

                ajax: {
                    url: self.url,
                    timeout: 500,
                    displayField: "label",
                    triggerLength: 1,
                    method: "get",
                    loadingClass: "loading-circle",
                    preDispatch: function (query) {
                        // showLoadingMask(true);
                        return {
                            q: query
                        }
                    },
                    preProcess: function (data) {
                        // showLoadingMask(false);
                        if (data.success === false) {
                            // Hide the list, there was some error
                            return false;
                        }
                        return data;
                    }
                }
            }).bind('change blur', function () {
                self.$emit('input', {
                    value: 0,
                    text: $(this).val()
                })
                // console.log($(this).val());
                /*if (myData.valueCache.indexOf($(this).val()) === -1) {
                    $(this).val('');
                }*/
            });

            /*$(this.$el).bind('typeahead:change', function(ev, suggestion) {
                console.log('Selection: ' + suggestion);
            });*/
        },
        watch: {
            
        }
    }
</script>