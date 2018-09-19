<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>Equipments <small></small></h1>
        </section>

        <section class="content">
           <div class="padding">
               <div class="row">
                    <div class="col-md-6">
                        <equipments-crud @load_history="updateCurrentEquipment"></equipments-crud>
                    </div>
                    <div class="col-md-6">
                        <h3>Equipment History <small v-if="current_equipment != null">({{current_equipment.name}})</small></h3>
                        <equipment-history-crud v-if="current_equipment != null" :current_equipment="current_equipment"></equipment-history-crud>
                        <span v-else>Please select an equipment</span>
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
                current_equipment: null
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
            updateCurrentEquipment(equipment) {
                this.current_equipment = equipment
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