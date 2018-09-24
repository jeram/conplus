<template>
    <div class="row">
        <div class="col-md-12">
            <materials-crud></materials-crud>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    
    export default {
        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        props: {
            
        },

        data() {
            return {
                active_tab: 'materials',
            }
        },

        methods: {
            closeModal() {

            },
        }

    }
</script>