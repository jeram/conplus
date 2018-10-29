<template>
    <section>
        <div class="box-body">
            <form @submit.prevent="handleSubmit" v-if="hasPermissionTo('Can Edit Companies')">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" v-validate="'required'" name="name" v-model="current_company.name" class="form-control">
                    <span class="text-danger">{{ errors.first('name') }}</span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary pull-right" :disabled="loading_btn" value="Submit">
                </div>
            </form>
            <div v-else>
                <div class="form-group">
                    <label for="name">Name: </label>
                    {{current_company.name}}
                </div>
            </div>
            
        </div>
    </section>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import {user_mixin} from '../mixins'
    
    export default {
        mixins: [user_mixin],

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
                loading_btn: false,
            }
        },

        created() {
            // console.log(this.current_company)
        },

        methods: {
            handleSubmit() {

                this.$validator.validate().then(result => {
                    if (!result) {

                        this.loading_btn = false
                        
                    } else {

                        this.loading_btn = true
                        return axios.put('/api/company/' + this.current_company.id, this.current_company)
                        .then(res => {
                            this.flash('User has been successfully updated', 'success')
                            this.loading_btn = false

                            this.setCurrentCompany(res.data)
                        })
                        .catch(err => {
                            this.loading_btn = false
                            this.$root.handleErrors(err.response)
                        })
                       
                        
                    }
                });
                
            },

            ...mapActions(['setCurrentCompany'])
        }

    }
</script>