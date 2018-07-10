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

    resetPass(email, password, password_confirmation, token) {
        return axios.post('/dashboard/reset', {
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