<template>
    <div class="row">
        <div class="col-md-12">
            <materials-crud></materials-crud>
        </div>
        <!--
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li :class="{ active: active_tab === 'materials' }">
                    <a href="#materials" data-toggle="tab" @click="active_tab = 'materials'">Materials</a>
                </li>
                <li :class="{ active: active_tab === 'material_categories' }">
                    <a href="#material_categories" data-toggle="tab" @click="active_tab = 'material_categories'">Categories</a>
                </li>
            </ul>
        </div>
        <div class="col-md-10">
            <div class="tab-content col-md-12">
                <div class="tab-pane" :class="{ active: active_tab === 'materials' }" id="materials">
                    <materials-crud></materials-crud>
                </div>
                <div class="tab-pane" :class="{ active: active_tab === 'material_categories' }" id="material_categories">
                    <material-categories-crud></material-categories-crud>
                </div>
            </div>
        </div>
        -->
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    
    export default {
        computed: {
            ...mapState({
                current_project: state => state.current_project,
                current_company: state => state.current_company
            })
        },

        props: {
            
        },

        data() {
            return {
                active_tab: 'materials',
            }
        },

        methods: {
            closeModal() {

            },
        }

    }
</script>