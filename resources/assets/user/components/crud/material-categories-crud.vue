<template>
    <div>
        company form here
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
                //show: true,
            }
        },

        methods: {
            closeModal() {

            },
        }

    }
</script>