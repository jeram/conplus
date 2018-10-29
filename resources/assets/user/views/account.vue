<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>Account Settings <small></small></h1>
        </section>

        <section class="content">
            <div class="padding">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-solid">
                            <!--<div class="box-header with-border">
                            <h3 class="box-title">Tools & Equipments</h3></div>-->
                            <div class="box-body text-left">
                                <form @submit.prevent="handleSubmit">
                                    <div class="form-group">
                                        <label for="label">Name</label>
                                        <input type="text" name="name" v-validate="'required'" v-model="current_record.name" class="form-control">
                                        <span class="text-danger">{{ errors.first('name') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="label">Email</label>
                                        <input type="email" readonly v-validate="'required|email'" v-model="current_record.email" class="form-control">
                                        <span class="text-danger">{{ errors.first('email') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="label">Password</label>
                                        <input type="password" v-model="current_record.password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="label">New Password</label>
                                        <input type="password" v-model="current_record.new_password" class="form-control">
                                    </div>
                                    <div class="form-group text-right">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
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
                loading_btn: false,
                current_record: {
                    id: window.user.id,
                    name: window.user.name,
                    email: window.user.email,
                    password: null,
                    new_password: null,
                }
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
            handleSubmit() {

                this.$validator.validate().then(result => {
                    if (!result) {

                        this.loading_btn = false
                        
                    } else {

                        return axios.put('/api/company/' + this.current_company.id + '/user/' + this.current_record.id, this.current_record)
                        .then(res => {
                            this.flash('Your Acccount has been successfully updated', 'success')
                            this.loading_btn = false

                            this.current_record.password = null
                            this.current_record.new_password = null
                        })
                        .catch(err => {
                            this.loading_btn = false
                            this.$root.handleErrors(err.response)
                        })
                       
                        
                    }
                });
                
            },
        },

        beforeRouteEnter (to, from, next) {
            auth.check()
                .then((res) => {
                    next()
                })
                .catch((err) => {
                    window.location.href = '/login'
                })
        }
    }
</script>