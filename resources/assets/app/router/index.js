import VueRouter from 'vue-router';

let routes = [
    {
        name: 'dashboard',
        path: '/',
        component: require('../views/dashboard'),
    },    
    /*{
        name: 'Campaign Settings',
        path: '/dashboard/campaigns/settings/:campaign_id',
        component: require('../views/campaign/settings'),
        props: true,
    },*/
    {
        path: '*',
        component: require('../views/errors/404'),
    }
];

export default new VueRouter({
    mode: 'history',
    routes
});