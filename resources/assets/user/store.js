import Vuex from 'vuex'
import Vue from 'vue'
import VuexPersist from "vuex-persist/dist/index"
import VeeValidate from 'vee-validate'

Vue.use(Vuex)
Vue.use(VeeValidate)

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
        
        setCurrentProject({commit}, current_project) {
            commit('SET_CURRENT_PROJECT', current_project)
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

        newProjectPhase({commit}, project_phase) {
            commit('NEW_PROJECT_PHASE', project_phase)
        },

        updateProjectPhase({commit}, project_phase) {
            commit('UPDATE_PROJECT_PHASE', project_phase)
        },

        deleteProjectPhase({commit}, project_phase) {
            commit('DELETE_PROJECT_PHASE', project_phase)
        },

        setProjectPhases({commit}, project_phases) {
            commit('SET_PROJECT_PHASES', project_phases)
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
        /*
        * PROJECTS
        */
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

        SET_CURRENT_PROJECT (state, current_project) {
            state.current_project = current_project
        },

        /*
        * COMPANIES
        */
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

        /*
        * PROJECT PHASES
        */
        NEW_PROJECT_PHASE(state, project_phase) {
            state.project_phases.push(project_phase)
        },

        UPDATE_PROJECT_PHASE(state, project_phase) {
            let founded = state.project_phases.find(cmp => cmp.id === project_phase.id)
            if (founded) {
                Vue.set(state.project_phases, state.project_phases.indexOf(founded), project_phase)
            }
        },

        DELETE_PROJECT_PHASE(state, project_phase) {
            let founded = state.project_phases.find(cmp => cmp.id === project_phase.id)
            if (founded) {
                state.project_phases.splice(state.project_phases.indexOf(founded), 1)
            }
        },

        SET_PROJECT_PHASES(state, project_phases) {
            state.project_phases = project_phases
        },

        RESET_VUEX (state) {
            state.campaigns = []
            state.projects = []
        },
    },
  
    plugins: [vuexLocalStorage.plugin]
})