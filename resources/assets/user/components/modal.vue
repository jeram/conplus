<template>
    <div id="myModal" class="modal fade in show">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" @click="$emit('close')">&times;</button>
                    <h4 class="modal-title">
                        <slot name="header">Modal Header</slot>
                    </h4>
                </div>
                <slot name="body"><p>Some text in the modal.</p></slot>
                
                <!--
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button type="button" class="btn btn-default" @click="$emit('close')">Close</button>
                    </slot>
                </div>-->
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            
        },

        data() {
            return {
                //show: true,
            }
        },

        mounted() {
            //set datepickers
            if ( $('#myModal .modal-body').find('.datepicker').length > 0 ) {
                $('.datepicker').datepicker({
                    autoclose: true,
                    forceParse: false
                }).on( 'changeDate', function(e) {
                    console.log(e.date)
                    $(this).val(e.date)
                    
                });                
            }
        },

        methods: {
            closeModal() {

            },
        }

    }
</script>