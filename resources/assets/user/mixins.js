var user_mixin = {
    methods: {
        hasPermissionTo(permission) {
            // if user is logged out of the system when session expires
            
            if (!window.user.permissions) {
                return false
            }
    
            /*if (Array.isArray(permissions)) {
                for (let permission of permissions) {
                    if (!window.user.permissions.find(o => o == permission)) {
                        return false
                    }
                }
                return true
            } else {*/

                if(window.user.permissions.indexOf(permission) != -1) {  
                    return true
                }
                //return window.user.permissions.find(o => o == permissions)
                return false
            // }
        },
    }
}

export {user_mixin}