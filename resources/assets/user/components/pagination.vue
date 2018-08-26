<template>
    <div class="row">
        <div class="col-sm-5">
            <div class="note">Showing {{pagination.from}} to {{pagination.to}} of {{pagination.total}} entries</div>
        </div>
        <div class="col-sm-7">
            <div class="text-right">
                <ul class="pagination">
                    <li class="paginate_button previous" :class="pagination.current_page <= 1 ? 'disabled' : ''">
                        <a @click.prevent="changePage(pagination.current_page - 1)">Previous</a>
                    </li>

                    <li v-for="page in pages" class="paginate_button" :class="isCurrentPage(page) ? 'active' : ''" @click.prevent="changePage(page)">
                        <a @click.prevent="changePage(page)">{{ page }}</a>
                    </li>

                    <li class="paginate_button next" :class="pagination.current_page >= pagination.last_page ? 'disabled' : ''">
                        <a @click.prevent="changePage(pagination.current_page + 1)">Next</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--
    <nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">
        <a class="pagination-previous" @click.prevent="changePage(1)" :disabled="pagination.current_page <= 1">First page</a>
        <a class="pagination-previous" @click.prevent="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1">Previous</a>
        <a class="pagination-next" @click.prevent="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page">Next page</a>
        <a class="pagination-next" @click.prevent="changePage(pagination.last_page)" :disabled="pagination.current_page >= pagination.last_page">Last page</a>
        <ul class="pagination-list">
            <li v-for="page in pages">
                <a class="pagination-link" :class="isCurrentPage(page) ? 'is-current' : ''" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
        </ul>
    </nav>-->
</template>

<script>
    export default {
        props: ['pagination', 'offset'],
        methods: {
            isCurrentPage(page) {
                return this.pagination.current_page === page;
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = [];
                let from = this.pagination.current_page - Math.floor(this.offset / 2);
                if (from < 1) {
                    from = 1;
                }
                let to = from + this.offset - 1;
                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                while (from <= to) {
                    pages.push(from);
                    from++;
                }
                return pages;
            }
        }
    }
</script>