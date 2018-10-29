<script>

    export default {
        template: '<textarea/>',

        props: [ 'model'],

        computed: {
        },

        mounted: function() {
            var self = this
            $(this.$el).jsonTagEditor({
                initialTags: this.model,
                onChange: function(field, editor, tags) {
                    self.$emit('change', tags);
                },
            });
        },
        
        data() {
            return {
            }
        },
        
        created() {

        },

        methods: {

        },
        watch: {
            'model': function (newVal, oldVal) {
                //$(this.$el).jsonTagEditor('addTag', '{"id":122, "value":"tag100"}')
            }
        }
    }
</script>