<template>
    <ul class="sidebar-menu" data-widget="tree">
        <router-link :to="{ name: 'Dashboard' }" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-desktop"></i> <span>Dashboard</span>
            </a>
        </router-link>
        <!--<li v-if="current_project.id != 0">
            <a href="#"><i class="fa fa-sticky-note-o"></i> <span>Notes</span></a>
        </li>        
        <li v-if="current_project.id != 0">
            <a href="#"><i class="fa fa-calendar"></i> <span>Schedules</span></a>
        </li>-->        
        <router-link v-if="this.hasPermissionTo('Can Manage Accounting') && current_project.id != 0" :to="{ name: 'Deposits' }" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-money"></i> <span>Deposits</span>
            </a>
        </router-link>

        <router-link v-if="this.hasPermissionTo('Can Manage Accounting') && current_project.id != 0" :to="{ name: 'Payments' }" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-share"></i> <span>Payments</span>
            </a>
        </router-link>
        <!--<router-link :to="{ name: 'Attachments' }" tag="li" exact-active-class="active" v-if="current_project.id != 0">
            <a>
                <i class="fa fa-folder-o"></i> <span>Attachments</span>
            </a>
        </router-link>-->
        <router-link v-if="this.hasPermissionTo('Can Manage Materials') && current_project.id != 0" :to="{ name: 'Project Materials' }" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-leaf"></i> <span>Project Materials</span>
            </a>
        </router-link>
        <router-link v-if="this.hasPermissionTo('Can Manage Phases') && current_project.id != 0" :to="{ name: 'Phases' }" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-tasks"></i> <span>Phases</span>
            </a>
        </router-link>
        <router-link v-if="this.hasPermissionTo('Can Manage Equipments')" :to="{ name: 'Equipments' }" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-truck"></i> <span>Tools & Equipments</span>
            </a>
        </router-link>
        <router-link v-if="current_project.id != 0" :to="{ name: 'Project', params: { project_id: current_project.id }}" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-wrench"></i> <span>Project</span>
            </a>
        </router-link>
        <router-link v-if="this.hasPermissionTo('Can Manage Trades')" :to="{ name: 'Trades' }" tag="li" exact-active-class="active" >
            <a>
                <i class="fa fa-rocket"></i> <span>Trades</span>
            </a>
        </router-link>
        <router-link :to="{ name: 'Company', params: { company_id: current_company.id }}" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-building-o"></i> <span>Company</span>
            </a>
        </router-link>
        <!--
        <li class="header">CONFIGURATION</li>
        <router-link :to="{ name: 'Materials' }" tag="li" exact-active-class="active">
            <a>
                <i class="fa fa-leaf"></i> <span>Materials Management</span>
            </a>
        </router-link>
        <li class="treeview">
            <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="#">Link in level 2</a></li>
                <li><a href="#">Link in level 2</a></li>
            </ul>
        </li>-->
    </ul>
</template>

<script>
    import auth from '../auth'
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
        
        data() {
            return {
                auth: auth
            }
        },
        
        created() {
            //console.log(hasPermissionTo('Can View Notes'))
            // console.log(this.current_project.id)
        },

        methods: {
        }
    }
</script>
