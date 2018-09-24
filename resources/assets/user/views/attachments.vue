<template>
    <div class="app-body" id="view">
        <section class="content-header">
            <h1>Attachments <small></small></h1>
        </section>

        <section class="content">
           <div class="padding">
                <vue-dropzone 
                    ref="myVueDropzone" 
                    id="dropzone" 
                    @vdropzone-success="fileUploaded"
                    @vdropzone-removed-file="removeFile"
                    :options="dropzoneOptions"></vue-dropzone>
            </div>
        </section>
    </div>
</template>

<style>
  
</style>


<script>
    import auth from '../auth'
    import { mapState, mapActions } from 'vuex'
    import vueDropzone from 'vue2-dropzone'

    export default {
        components: {
            vueDropzone
        },

        data() {
            return {
                auth: auth,
                dropzoneOptions: {
                    url: null,
                    thumbnailWidth: 150,
                    params: [],
                    maxFiles: 1,
                    acceptedFiles: 'image/*,application/pdf',
                    addRemoveLinks: true,
                    headers: { "Authorization": 'Bearer ' + localStorage.getItem('api_token') }
                },
                filename: null
            }
        },
        
        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        created() {
            this.dropzoneOptions.url = '/api/company/' + this.current_company.id + '/upload'
            this.dropzoneOptions.params = {
                        'destination_path': 'files/company/' + this.current_company.id + '/project/' + this.current_project.id,
                    }
        },

        mounted() {

        },

        methods: {
            fileUploaded(file, response) {
                this.filename = response.filename
            },

            removeFile(file, error, xhr) {
                let filename = file.name
                if (!confirm('Are you sure you want to delete this file?')) {
                    return false
                }

                return axios.delete('/api/company/' + this.current_company.id + '/upload/' + this.filename, {
                        data: {
                            path: 'files/company/' + this.current_company.id + '/project/' + this.current_project.id
                        }
                    })
                    .then(res => {
                        this.flash('User has been successfully deleted', 'success')
                    })
                    .catch(err => {
                        this.$root.handleErrors(err.response)
                    })
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