import Vuex from 'vuex'
import Vue from 'vue'
import VuexPersist from "vuex-persist/dist/index"

Vue.use(Vuex)

const vuexLocalStorage = new VuexPersist({
    key: 'Const_vuex', // The key to store the state on in the storage provider.
    storage: window.localStorage, // or window.sessionStorage or localForage
})

export default new Vuex.Store({

    state: {        
        projects: [],
        project_types: [],
        project_statuses: [],
        project_note_statuses: [],
        company_vendors: [],
        project_phases: [],
        project_material_statuses: [],
        project_payment_types: [],
        customers: [],
        deposit_types: [],
        companies: [],
        current_company: null,
        current_project: null,
    },

    getters: {
        projects: state => {
            return state.projects
        },
        companies: state => {
            return state.companies
        },
        getProjectTypes: state => {
            return state.project_types
        },
        getProjectNoteStatuses: state => {
            return state.project_note_statuses
        },
        getCompanyVendors: state => {
            return state.company_vendors
        },
        getProjectPhases: state => {
            return state.project_phases
        },
        getProjectMaterialStatuses: state => {
            return state.project_material_statuses
        },
        getPaymentProjectTypes: state => {
            return state.project_payment_types
        },
        getCustomers: state => {
            return state.customers
        },
        getDepositTypes: state => {
            return state.deposit_types
        },
    },
  
    actions: {
        newProject({commit}, project) {
            commit('NEW_PROJECT', project)
        },

        updateProject({commit}, project) {
            commit('UPDATE_PROJECT', project)
        },

        deleteProject({commit}, project) {
            commit('DELETE_PROJECT', project)
        },

        setProjects({commit}, projects) {
            commit('SET_PROJECTS', projects)
        },
      
        newCompany ({commit}, company) {
            commit('NEW_COMPANY', company)
        },

        setCompanies({commit}, companies) {
            commit('SET_COMPANIES', companies)
        },

        deleteCurrentCompany({commit}) {
            commit('DELETE_CURRENT_COMPANY')
        },

        setCurrentCompany({commit}, current_company) {
            commit('SET_CURRENT_COMPANY', current_company)
        },

        resetVuex({commit}) {
            commit('RESET_VUEX')
        },
    },
  
    mutations: {
        NEW_PROJECT(state, project) {
            state.projects.push(project)
        },

        UPDATE_PROJECT(state, project) {
            let founded = state.projects.find(cmp => cmp.id === project.id)
            if (founded) {
                Vue.set(state.projects, state.projects.indexOf(founded), project)
            }
        },

        DELETE_PROJECT(state, project) {
            let founded = state.projects.find(cmp => cmp.id === project.id)
            if (founded) {
                state.projects.splice(state.projects.indexOf(founded), 1)
            }
        },

        SET_PROJECTS(state, projects) {
            state.projects = projects
        },

        NEW_COMPANY (state, company) {
            state.companies.push(company)
        },

        SET_COMPANIES (state, companies) {
            state.companies = companies
        },

        DELETE_CURRENT_COMPANY (state) {
            state.current_company = null
        },

        SET_CURRENT_COMPANY (state, current_company) {
            state.current_company = current_company
        },
        
        SET_CURRENT_PROJECT (state, current_project) {
            state.current_project = current_project
        },

        RESET_VUEX (state) {
            state.campaigns = []
            state.projects = []
        },
    },
  
    plugins: [vuexLocalStorage.plugin]
})