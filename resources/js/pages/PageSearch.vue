<template>
    <div class="page page-search">
        <section class="header centered mb-16">
            <router-link class="naked-link" to="/"><i class="fas fa-bolt font-200"></i></router-link>
        </section>
        <section class="mb-6 max-w-lg mx-auto">
            <div class="flex m-4">
                <input ref="search" placeholder="e.g. tag:founder" class="text font-100 flex-5 no-border mr-3 p-2">
                <a class="btn flex-1 px-5 py-2 text-center" style="flex-basis: 50px; flex-grow: inherit;" @click="search">
                    <span v-if="search_processing">Searching...</span>
                    <span v-else>Search</span>
                </a>
            </div>
        </section>
        <section class="max-w-lg mx-auto" v-bind:class="{opacity50 : search_processing}">
            <search-result-card class="hoverable" v-for="user in users" v-bind:user="user" :key="user.id"></search-result-card>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                search_processing: false
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/users').then(function(response) {
                self.users = response.data;
            });
        },
        methods: {
            search() {
                this.search_processing = true;

                axios.get('/api/v1/users?query=' + this.$refs.search.value).then((response) => {
                    this.search_processing = false;
                    this.users = response.data;
                });
            }
        }
    }
</script>
