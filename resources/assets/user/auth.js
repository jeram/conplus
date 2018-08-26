import Vue from 'vue'

export default {
    user: {
        authenticated: false,
        profile: null
    },

    check() {
		return axios.get('usercheck')
			.then((res) => {
				// success
				this.user.authenticated = true
				this.user.profile = res.data.user

				return Promise.resolve(res)
			})
			.catch((err) => {
				// error
				this.user.authenticated = false
				this.user.profile = null

				return Promise.reject(err)
			})
			
    },
    
    login(email, password, remember_me) {
        return axios.post('/auth/login', {
            email: email,
            password: password,
            remember_me: remember_me
        })
            .then((res) => {
                // success
                localStorage.setItem('api_token', res.data.meta.token)
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('api_token')

                return Promise.resolve(res)
            })
            .catch((err) => {
                // error
                return Promise.reject(err)
            })
    },

    resetPass(email, password, password_confirmation, token) {
        return axios.post('/auth/reset', {
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            token: token
        })
            .then((res) => {
                // success
                return Promise.resolve(res)
            })
            .catch((err) => {
                // error
                return Promise.reject(err)
            })
    }
}