import Vue from 'vue'

export default {

    user: {
        authenticated: false,
        profile: null
    },

    check() {
        if (localStorage.getItem('api_token') === null) {
            this.user.authenticated = false
            this.user.profile = null

            return Promise.reject()
        } else {
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('api_token')
            return axios.get('/dashboard/user')
                .then((res) => {
                    // success
                    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('api_token')
                    this.user.authenticated = true
                    this.user.profile = res.data.user

                    // Auth Events ???

                    return Promise.resolve(res)
                })
                .catch((err) => {
                    // error
                    this.user.authenticated = false
                    this.user.profile = null

                    return Promise.reject(err)
                })
        }

        // error
        this.user.authenticated = false
        this.user.profile = null

        return Promise.reject()
    },

    login(email, password, remember_me) {
        return axios.post('/dashboard/login', {
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

    logout() {
        return axios.post('/dashboard/logout')
            .then((res) => {
                // success
                localStorage.removeItem('api_token')
                localStorage.removeItem('impersonate')
                window.api_token = null
                window.axios.defaults.params = null

                return Promise.resolve(res)
            })
            .catch((err) => {
                // error
                return Promise.reject(err)
            })
    },

    register(name, email, password, agency_name, phone_number, timezone) {
        return axios.post('/dashboard/register', {
            name: name,
            email: email,
            password: password,
            agency_name: agency_name,
            phone_number: phone_number,
            timezone: timezone,
        })
            .then((res) => {
                // success
                return Promise.resolve(res)
            })
            .catch((err) => {
                // error
                return Promise.reject(err)
            })
    },

    forgotPass(email) {
        return axios.post('/dashboard/forgot', {
            email: email,
        })
            .then((res) => {
                // success
                return Promise.resolve(res)
            })
            .catch((err) => {
                // error
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