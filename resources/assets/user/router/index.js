import VueRouter from 'vue-router';

let routes = [
    {
        name: 'login',
        path: '/login',
        //component: require('../views/dashboard'),
    },
    {
        name: 'Dashboard',
        path: '/',
        component: require('../views/dashboard'),
    },
    {
        name: 'Project',
        path: '/project/:project_id',
        component: require('../views/project'),
    },
    {
        name: 'Company',
        path: '/company/:company_id',
        component: require('../views/company'),
    },
    {
        name: 'Phases',
        path: '/phases',
        component: require('../views/phases'),
    },    
    {
        name: 'Project Materials',
        path: '/project-materials',
        component: require('../views/project-materials'),
    },
    {
        name: 'Attachments',
        path: '/attachments',
        component: require('../views/attachments'),
    },
    {
        name: 'Payments',
        path: '/payments',
        component: require('../views/payments'),
    },
    {
        name: 'Deposits',
        path: '/deposits',
        component: require('../views/deposits'),
    },
    /*
    {
        name: 'Materials Management',
        path: '/materials-management',
        component: require('../views/materials-management'),
    },
    {
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