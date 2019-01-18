<template>
    <div class="page page-search m-4 sm:m-8">
        <top-nav></top-nav>
        <section class="mb-6 max-w-md mx-auto">
            <div class="flex mb-4 mt-6">
                <input ref="search"
                       v-model="query"
                       placeholder="e.g. tag:founder"
                       class="text font-100 flex-5 no-border mr-3 p-2"
                       v-shortkey="['enter']" @shortkey="search"
                >
                <a class="btn flex-1 px-5 py-2 text-center" style="flex-basis: 50px; flex-grow: inherit;" @click="search">
                    <span v-if="search_processing">Searching...</span>
                    <span v-else>Search</span>
                </a>
            </div>
        </section>
        <section class="max-w-md mx-auto" v-bind:class="{opacity50 : search_processing}">
            <search-result-card class="hoverable mb-4" v-for="user in users" v-bind:user="user" :key="user.id"></search-result-card>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                search_processing: false,
                query: null
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeyHandler);
            if (this.$route.params.query) {
                this.query = this.$route.params.query;
                this.search();
            } else {
                axios.get('/api/v1/users').then((response) => {
                    this.users = response.data;
                });
            }

            this.$refs.search.focus();
        },
        methods: {
            search() {
                this.search_processing = true;

                axios.get('/api/v1/users?q=' + this.query).then((response) => {
                    this.search_processing = false;
                    this.users = response.data;
                });
            },
            hotkeyHandler(e) {
                if (document.activeElement.tagName === 'INPUT') {
                    if (e.key === 'Enter') {
                        this.search();
                    } else if (e.key === 'Escape') {
                        this.$refs.search.blur();
                    }
                } else if (document.activeElement.tagName === 'BODY') {
                    if (e.key === '/') {
                        this.$refs.search.focus();
                    }
                }
            },
        }
    }
</script>
