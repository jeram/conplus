<template>
    <div class="app-body" id="view">
        <section class="content">
           <div class="padding">
               <div class="row">
                    <div class="col-md-5">
                        <h3>Clients <small></small></h3>
                        <clients-crud @load_trades="updateCurrentClient"></clients-crud>
                    </div>
                    <div class="col-md-7">
                        <h3>Trades <small v-if="current_client != null">({{current_client.name}})</small></h3>
                        <client-trades-crud v-if="current_client != null" :current_client="current_client"></client-trades-crud>
                        <span v-else>Please select a client</span>
                    </div>
               </div>
            </div>
        </section>
    </div>
</template>

<script>
    import auth from '../auth'
    import { mapState, mapActions } from 'vuex'

    export default {

        data() {
            return {
                auth: auth,
                loading: false,
                loading_btn: false,
                current_client: null
            }
        },
        
        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        mounted() {
			 
        },

        created() {
            
        },

        methods: {
            updateCurrentClient(client) {
                this.current_client = client
            }
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