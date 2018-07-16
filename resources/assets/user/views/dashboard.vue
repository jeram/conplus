<template>
    <div class="app-body" id="view">
        <div class="padding pb-0">
            dashboard
        </div>
		<!--
        <div class="padding">
            <div class="box effect8">
                <div class="box-body">
                    
                </div>
            </div>
        </div>

        <div class="padding">
            <div class="box effect8">
                <div class="box-body">
                    
                </div>
            </div>
        </div>-->
    </div>
</template>

<script>
    import auth from '../auth'

    export default {

        data() {
            return {
                auth: auth
            }
        },

        mounted() {
			//console.log(auth.user)
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